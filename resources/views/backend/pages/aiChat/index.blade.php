@extends('backend.layouts.master')

@section('title')
    {{ localize('AI Chat') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('AI Chat') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ localize('AI Chat') }}</li>
                                </ol>
                            </div>
                            <div class="tt-action">
                                <a href="{{ route('chat.experts') }}" class="btn btn-primary btn-sm py-2">
                                    {{ localize('Browse Experts') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div id="tt-ai-chat" class="d-flex" style="height: 65vh;">
                                <div class="tt-chat-left d-flex">
                                    <!-- ai chat expertise start -->
                                    <div class="tt-chat-users">
                                        <ul class="tt-chat-user-list list-unstyled mb-0 py-2 expert-list">
                                            @include('backend.pages.aiChat.inc.expert-list')
                                        </ul>
                                    </div>
                                    <!-- ai chat expertise end -->
                                </div>

                                <!-- chat right box start -->

                                <!-- chat right with preloader start -->
                                <div class="tt-chat-right d-flex w-100 d-none list-and-messages-wrapper-loader">
                                    <div class="tt-text-preloader tt-preloader-center">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>

                                <div class="tt-chat-right tt-custom-scrollbar d-flex w-100 list-and-messages-wrapper">
                                    @include('backend.pages.aiChat.inc.chat-right')
                                </div>
                                <!-- chat right box end -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


@section('scripts')
    @include('backend.pages.aiChat.inc.chat-scripts')
@endsection
