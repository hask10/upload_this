<?php

namespace App\Http\Controllers\Backend\faq;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    # get all faqs
    public function index(Request $request)
    {
        $searchKey = null;
        $faqs = Faq::oldest();
        if ($request->search != null) {
            $faqs = $faqs->where('question', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        $faqs = $faqs->paginate(paginationNumber());
        return view('backend.pages.faqs.index', compact('faqs', 'searchKey'));
    }

    # faq store
    public function store(Request $request)
    {
        $faq = new Faq;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        flash(localize('FAQ has been added successfully'))->success();
        return redirect()->route('admin.faqs.index');
    }

    # edit faq
    public function edit(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        return view('backend.pages.faqs.edit', compact('faq'));
    }

    # update Faq
    public function update(Request $request)
    {
        $faq = Faq::findOrFail($request->id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        flash(localize('Faq has been updated successfully'))->success();
        return back();
    }

    # delete Faq
    public function delete($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        flash(localize('Faq has been deleted successfully'))->success();
        return back();
    }
}
