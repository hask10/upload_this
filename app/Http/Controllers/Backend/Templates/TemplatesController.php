<?php

namespace App\Http\Controllers\Backend\Templates;

use App\Http\Controllers\Controller;
use App\Imports\TemplatesImport;
use App\Models\CustomTemplate;
use App\Models\FavoriteTemplate;
use App\Models\Language;
use App\Models\SubscriptionPackageTemplate;
use App\Models\Template;
use App\Models\TemplateGroup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TemplatesController extends Controller
{
    # all templates
    public function index(Request $request)
    {
        $searchKey = null;
        $templates      = Template::query();

        if ($request->search != null) {
            $templates = $templates->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        $templateGroups = TemplateGroup::get();
        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('templates')) {
                abort(403);
            }
            $templates = $templates->get();
        } else {
            $templates = $templates->isActive()->get();
        }

        $favoritesArray = FavoriteTemplate::where('user_id', auth()->user()->id)->select('template_id')->distinct()->pluck('template_id')->toArray();

        return view('backend.pages.templates.index', [
            'templates'         => $templates,
            'templateGroups'    => $templateGroups,
            'favoritesArray'    => $favoritesArray,
            'searchKey'         => $searchKey
        ]);
    }

    # favorite popular
    public function indexPopular()
    {

        $templates = Template::orderBy('total_words_generated', 'DESC')->take(12);

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('templates')) {
                abort(403);
            }
            $templates = $templates->get();
        } else {
            $templates = $templates->isActive()->get();
        }

        $favoritesArray = FavoriteTemplate::where('user_id', auth()->user()->id)->select('template_id')->distinct()->pluck('template_id')->toArray();

        return view('backend.pages.templates.popular', [
            'templates'         => $templates,
            'favoritesArray'    => $favoritesArray
        ]);
    }

    # favorite templates
    public function indexFavorite()
    {
        $favoritesArray = FavoriteTemplate::where('user_id', auth()->user()->id)->select('template_id')->distinct()->pluck('template_id')->toArray();

        $templates = Template::whereIn('id', $favoritesArray);

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('templates')) {
                abort(403);
            }
            $templates = $templates->get();
        } else {
            $templates = $templates->isActive()->get();
        }

        return view('backend.pages.templates.favorites', [
            'templates'         => $templates,
            'favoritesArray'    => $favoritesArray
        ]);
    }

    # store / update templates from excel :: only for developers use
    public function store()
    {
        $file = public_path('/import/templates.xlsx');
        Excel::import(new TemplatesImport, $file);
    }


    # template view
    public function show($template_code)
    {
        $template = Template::where('code', $template_code)->first();

        if (empty($template)) {
            abort(404);
        }

        # check if template is available in subscription package
        if (auth()->user()->user_type == "customer") {
            $package = auth()->user()->subscriptionPackage;
            $subscriptionTemplate = SubscriptionPackageTemplate::where('template_id', $template->id)->where('subscription_package_id', $package->id)->first();

            if (empty($subscriptionTemplate)) {
                flash(localize('This template is not available in your subscription plan, please upgrade to get access.'))->error();
                return redirect()->route('templates.index');
            }
        } else {
            if (!auth()->user()->can('templates')) {
                abort(403);
            }
        }

        # proceed to view
        $languages = Language::isActive()->latest()->get();
        return view('backend.pages.templates.generate-contents', [
            'template'  => $template,
            'languages' => $languages
        ]);
    }

    # template view
    public function showCustom($template_code)
    {
        $template = CustomTemplate::where('code', $template_code)->first();
        if (empty($template)) {
            abort(404);
        }

        $user = auth()->user();

        # check if template is available in subscription package
        if ($user->user_type == "customer") {

            if ($user->subscription_package_id == null) {
                flash(localize('Please upgrade your subscription plan'))->error();
                return redirect()->route('writebot.dashboard');
            }

            $package = $user->subscriptionPackage;

            //  check if allow_custom_templates is enabled
            if ((int) $package->allow_custom_templates == 0) {
                flash(localize('Custom template is not available in this package, please upgrade you plan'))->error();
                return redirect()->route('writebot.dashboard');
            }
        } else {
            if (!auth()->user()->can('custom_templates')) {
                abort(403);
            }
        }

        # proceed to view 
        $languages = Language::isActive()->latest()->get();
        return view('backend.pages.templates.custom-generate-contents', [
            'template'  => $template,
            'languages' => $languages
        ]);
    }

    # update favorite
    public function updateFavorite(Request $request)
    {
        $existing = FavoriteTemplate::where('template_id', $request->templateId)->where('user_id', auth()->user()->id)->first();
        $data = [];
        if (is_null($existing)) {
            $favorite = new FavoriteTemplate;
            $favorite->template_id = $request->templateId;
            $favorite->user_id = auth()->user()->id;
            $favorite->save();
            $data['message'] = localize('Added to favorite templates');
        } else {
            $existing->delete();
            $data['message'] = localize('Removed from favorite templates');
        }
        return $data;
    }
}
