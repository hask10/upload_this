<?php

namespace App\Http\Controllers\Backend\Appearance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:homepage'])->only(['hero']);
    }

    # homepage hero configuration
    public function hero()
    {
        return view('backend.pages.appearance.homepage.hero');
    }

    # homepage trusted by configuration
    public function trustedBy()
    {
        return view('backend.pages.appearance.homepage.trustedBy');
    }

    # homepage howItWorks
    public function howItWorks()
    {
        return view('backend.pages.appearance.homepage.howItWorks');
    }

    # homepage featureImages
    public function featureImages()
    {
        return view('backend.pages.appearance.homepage.featureImages');
    }

    # homepage cta
    public function cta()
    {
        return view('backend.pages.appearance.homepage.cta');
    }
}
