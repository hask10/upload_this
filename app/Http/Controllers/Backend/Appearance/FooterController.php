<?php

namespace App\Http\Controllers\Backend\Appearance;

use App\Http\Controllers\Controller;
use App\Models\Page;

class FooterController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:footer'])->only('index');
    }

    # website footer configuration
    public function index()
    {
        $pages = Page::latest()->get();
        return view('backend.pages.appearance.footer', compact('pages'));
    }
}
