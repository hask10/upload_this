<?php

namespace App\Http\Controllers\Backend\Reports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubscriptionHistory;
use App\Models\Template;
use App\Models\TemplateUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class ReportsController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:words_report'])->only('words');
        $this->middleware(['permission:codes_report'])->only('codes');
        $this->middleware(['permission:images_report'])->only('images');
        $this->middleware(['permission:s2t_report'])->only('s2t');
        $this->middleware(['permission:most_used_templates'])->only('mostUsed');
        $this->middleware(['permission:subscriptions_reports'])->only('subscriptions');
    }

    # words reports 
    public function words(Request $request)
    {

        $usage = TemplateUsage::latest()->where('custom_template_id', null);

        # conditional   
        if (Str::contains($request->date_range, 'to') && $request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
        } else {
            $date_var = [date("d-m-Y", strtotime('7 days ago')), date("d-m-Y", strtotime('today'))];
        }

        $usage = $usage->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))->where('created_at', '<=',  date("Y-m-d", strtotime($date_var[1]) + 86400000));

        $totalWordsGenerated = $usage->sum('total_used_words');
        $usage = $usage->paginate(paginationNumber());
        return view('backend.pages.reports.words', compact('usage', 'date_var', 'totalWordsGenerated'));
    }

    # codes reports 
    public function codes(Request $request)
    {

        $usage = Project::latest()->where('content_type', 'code');

        # conditional   
        if (Str::contains($request->date_range, 'to') && $request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
        } else {
            $date_var = [date("d-m-Y", strtotime('7 days ago')), date("d-m-Y", strtotime('today'))];
        }

        $usage = $usage->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))->where('created_at', '<=',  date("Y-m-d", strtotime($date_var[1]) + 86400000));

        $totalWordsGenerated = $usage->count();
        $usage = $usage->paginate(paginationNumber());
        return view('backend.pages.reports.codes', compact('usage', 'date_var', 'totalWordsGenerated'));
    }

    # images reports 
    public function images(Request $request)
    {
        $usage = Project::latest()->where('content_type', 'image');

        # conditional   
        if (Str::contains($request->date_range, 'to') && $request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
        } else {
            $date_var = [date("d-m-Y", strtotime('7 days ago')), date("d-m-Y", strtotime('today'))];
        }

        $usage = $usage->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))->where('created_at', '<=',  date("Y-m-d", strtotime($date_var[1]) + 86400000));

        $totalWordsGenerated = $usage->count();
        $usage = $usage->paginate(paginationNumber());
        return view('backend.pages.reports.images', compact('usage', 'date_var', 'totalWordsGenerated'));
    }

    # s2t reports 
    public function s2t(Request $request)
    {
        $usage = Project::latest()->where('content_type', 'speech_to_text');

        # conditional   
        if (Str::contains($request->date_range, 'to') && $request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
        } else {
            $date_var = [date("d-m-Y", strtotime('7 days ago')), date("d-m-Y", strtotime('today'))];
        }

        $usage = $usage->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))->where('created_at', '<=',  date("Y-m-d", strtotime($date_var[1]) + 86400000));

        $totalWordsGenerated = $usage->count();
        $usage = $usage->paginate(paginationNumber());
        return view('backend.pages.reports.s2t', compact('usage', 'date_var', 'totalWordsGenerated'));
    }

    # most used templates reports 
    public function mostUsed(Request $request)
    {
        $searchKey  = null;
        $order = 'DESC';

        if ($request->order == "ASC") {
            $order = 'ASC';
        }

        $usage = Template::orderBy('total_words_generated', $order);

        if ($request->search != null) {
            $usage      = $usage->where('name', 'like', '%' . $request->search . '%');
            $searchKey  = $request->search;
        }

        $totalWordsGenerated = $usage->count();
        $usage = $usage->paginate(paginationNumber(30));
        return view('backend.pages.reports.mostUsedTemplates', compact('usage', 'order', 'searchKey'));
    }

    # subscriptions reports 
    public function subscriptions(Request $request)
    {

        $searchKey = null;
        $histories = SubscriptionHistory::latest();

        if ($request->search != null) {
            $userIds = User::where('name', 'like', '%' . $request->search . '%')->pluck('id');
            $histories = $histories->whereIn('user_id', $userIds);
            $searchKey = $request->search;
        }

        # conditional   
        if (Str::contains($request->date_range, 'to') && $request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
        } else {
            $date_var = [date("d-m-Y", strtotime('7 days ago')), date("d-m-Y", strtotime('today'))];
        }

        $histories = $histories->where('created_at', '>=', date("Y-m-d", strtotime($date_var[0])))->where('created_at', '<=',  date("Y-m-d", strtotime($date_var[1]) + 86400000));

        $totalPrice = $histories->sum('price');

        $histories = $histories->paginate(paginationNumber());
        return view('backend.pages.reports.subscriptions', compact('histories', 'searchKey', 'date_var', 'totalPrice'));
    }
}
