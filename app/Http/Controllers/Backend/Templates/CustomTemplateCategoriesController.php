<?php

namespace App\Http\Controllers\Backend\Templates;

use App\Http\Controllers\Controller;
use App\Models\CustomTemplate;
use App\Models\CustomTemplateCategory;
use Illuminate\Http\Request;
use Str;

class CustomTemplateCategoriesController extends Controller
{
    # category list
    public function index(Request $request)
    {
        $user = auth()->user();
        $searchKey = null;
        $categories = CustomTemplateCategory::oldest();

        if ($request->search != null) {
            $categories = $categories->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('custom_template_categories')) {
                abort(403);
            }
            $categories = $categories->where('user_id', auth()->user()->id)->orWhere('created_by', 'admin')->paginate(paginationNumber());
        } else {
            // subscription based
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

            $categories = $categories->where('user_id', auth()->user()->id)->paginate(paginationNumber());
        }


        return view('backend.pages.templates.custom.categories.index', compact('categories', 'searchKey'));
    }

    # category store
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->user_type == "admin" || $user->user_type == "staff") {
            if (!auth()->user()->can('custom_template_categories')) {
                abort(403);
            }
        } else {
            // subscription based
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
        }

        $category               = new CustomTemplateCategory;
        $category->name         = $request->name;
        $category->slug         = Str::slug($request->name);
        $category->icon         = $request->icon;
        $category->user_id      = $user->id;
        $category->created_by   = $user->user_type != "customer" ? "admin" : '';
        $category->save();

        flash(localize('Category has been inserted successfully'))->success();
        return redirect()->route('custom.templateCategories.index');
    }

    # edit category
    public function edit(Request $request, $id)
    {
        $user = auth()->user();
        $category = CustomTemplateCategory::where('id', $id);

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('custom_template_categories')) {
                abort(403);
            }
            $category = $category->where('user_id', auth()->user()->id)->orWhere('created_by', 'admin')->first();
        } else {
            // subscription based
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
            $category = $category->where('user_id', auth()->user()->id)->first();
        }

        if (is_null($category)) {
            abort(404);
        }
        return view('backend.pages.templates.custom.categories.edit', compact('category'));
    }

    # update category
    public function update(Request $request)
    {
        $user = auth()->user();
        $category = CustomTemplateCategory::where('id', (int) $request->id);

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('custom_template_categories')) {
                abort(403);
            }
            $category = $category->where('user_id', auth()->user()->id)->orWhere('created_by', 'admin')->first();
        } else {
            // subscription based
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
            $category = $category->where('user_id', auth()->user()->id)->first();
        }

        if (is_null($category)) {
            abort(404);
        }

        $category->name         = $request->name;
        $category->slug         = Str::slug($request->name);
        $category->icon         = $request->icon;
        $category->save();
        flash(localize('Category has been updated successfully'))->success();
        return back();
    }

    # delete category
    public function delete($id)
    {
        $user = auth()->user();
        $category = CustomTemplateCategory::where('id', $id);

        if (auth()->user()->user_type == "admin" || auth()->user()->user_type == "staff") {
            if (!auth()->user()->can('custom_template_categories')) {
                abort(403);
            }
            $category = $category->where('user_id', auth()->user()->id)->orWhere('created_by', 'admin')->first();
        } else {
            // subscription based
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
            $category = $category->where('user_id', auth()->user()->id)->first();
        }

        CustomTemplate::where('custom_template_category_id', $category->id)->delete();
        $category->delete();
        flash(localize('Category has been deleted successfully'))->success();
        return back();
    }
}
