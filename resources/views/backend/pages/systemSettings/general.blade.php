@extends('backend.layouts.master')

@section('title')
    {{ localize('General Settings') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">


            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('General Settings') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ localize('General Settings') }}</li>
                                </ol>
                            </div>
                            <div class="tt-action">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="pb-650">
                        @csrf

                        <!--general settings-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('General Informations') }}</h5>

                                <div class="mb-3">
                                    <label for="system_title" class="form-label">{{ localize('System Title') }}</label>
                                    <input type="hidden" name="types[]" value="system_title">
                                    <input type="text" id="system_title" name="system_title" class="form-control"
                                        value="{{ getSetting('system_title') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="title_separator"
                                        class="form-label">{{ localize('Browser Tab Title Separator') }}</label>
                                    <input type="hidden" name="types[]" value="title_separator">
                                    <input type="text" id="title_separator" name="title_separator" class="form-control"
                                        value="{{ getSetting('title_separator') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">{{ localize('Contact Email') }}</label>
                                    <input type="hidden" name="types[]" value="contact_email">
                                    <input type="text" id="contact_email" name="contact_email" class="form-control"
                                        value="{{ getSetting('contact_email') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">{{ localize('Contact Phone') }}</label>
                                    <input type="hidden" name="types[]" value="contact_phone">
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control"
                                        value="{{ getSetting('contact_phone') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="enable_preloader"
                                        class="form-label">{{ localize('Enable Preloader') }}</label>
                                    <input type="hidden" name="types[]" value="enable_preloader">
                                    <select id="enable_preloader" class="form-control text-uppercase select2"
                                        name="enable_preloader" data-toggle="select2">
                                        <option value="" disabled selected>{{ localize('Set preloader status') }}
                                        </option>
                                        <option value="1"
                                            {{ getSetting('enable_preloader') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                        <option value="0"
                                            {{ getSetting('enable_preloader') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--general settings-->



                        <!--logo settings-->
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Dashboard Logo & Favicon') }}</h5>
                                <div class="mb-3">
                                    <label for="admin_panel_logo"
                                        class="form-label">{{ localize('Dashboard Logo') }}</label>
                                    <input type="hidden" name="types[]" value="admin_panel_logo">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Dashboard Logo') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="admin_panel_logo"
                                                    value="{{ getSetting('admin_panel_logo') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="favicon" class="form-label">{{ localize('Favicon') }}</label>
                                    <input type="hidden" name="types[]" value="favicon">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Favicon') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="favicon"
                                                    value="{{ getSetting('favicon') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--logo settings-->

                        <!--maintenance mode settings-->
                        <div class="card mb-4" id="section-4">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Maintenance Mode') }}</h5>
                                <div class="mb-3">
                                    <label for="enable_maintenance_mode"
                                        class="form-label">{{ localize('Enable Maintenance Mode') }}</label>
                                    <input type="hidden" name="types[]" value="enable_maintenance_mode">
                                    <select id="enable_maintenance_mode" class="form-control text-uppercase select2"
                                        name="enable_maintenance_mode" data-toggle="select2">
                                        <option value="" disabled selected>{{ localize('Set maintenance mode') }}
                                        </option>
                                        <option value="1"
                                            {{ getSetting('enable_maintenance_mode') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                        <option value="0"
                                            {{ getSetting('enable_maintenance_mode') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--maintenance mode settings-->

                        <!--seo meta description start-->
                        <div class="card mb-4" id="section-5">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('SEO Meta Configuration') }}</h5>

                                <div class="mb-4">
                                    <label for="global_meta_title"
                                        class="form-label">{{ localize('Meta Title') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_title">
                                    <input type="text" name="global_meta_title" id="global_meta_title"
                                        placeholder="{{ localize('Type meta title') }}" class="form-control"
                                        value="{{ getSetting('global_meta_title') }}">
                                    <span class="fs-sm text-muted">
                                        {{ localize('Set a meta tag title. Recommended to be simple and unique.') }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <label for="global_meta_description"
                                        class="form-label">{{ localize('Meta Description') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_description">
                                    <textarea class="form-control" name="global_meta_description" id="global_meta_description" rows="4"
                                        placeholder="{{ localize('Type your meta description') }}">{{ getSetting('global_meta_description') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="global_meta_keywords"
                                        class="form-label">{{ localize('Meta Keywords') }}</label>

                                    <input type="hidden" name="types[]" value="global_meta_keywords">
                                    <textarea class="form-control" name="global_meta_keywords" id="global_meta_keywords" placeholder="Keyword, Keyword">{{ getSetting('global_meta_keywords') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Meta Image') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_image">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choose Meta Image') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="global_meta_image"
                                                    value="{{ getSetting('global_meta_image') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--seo meta description end-->

                        <!--Cookie Consent settings-->
                        <div class="card mb-4" id="section-6">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Cookie Consent') }}</h5>
                                <div class="mb-3">
                                    <label for="enable_cookie_consent"
                                        class="form-label">{{ localize('Show Cookie Consent') }}</label>
                                    <input type="hidden" name="types[]" value="enable_cookie_consent">
                                    <select id="enable_cookie_consent" class="form-control text-uppercase select2"
                                        name="enable_cookie_consent" data-toggle="select2">
                                        <option value="" disabled selected>
                                            {{ localize('Select an option') }}
                                        </option>
                                        <option value="1"
                                            {{ getSetting('enable_cookie_consent') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                        <option value="0"
                                            {{ getSetting('enable_cookie_consent') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="cookie_consent_text"
                                        class="form-label">{{ localize('Cookie Consent Text') }}</label>
                                    <input type="hidden" name="types[]" value="cookie_consent_text">
                                    <textarea name="cookie_consent_text" id="cookie_consent_text" class="editor form-control">{{ getSetting('cookie_consent_text') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--Cookie Consent settings-->

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i> {{ localize('Save Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Configure General Settings') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('General Information') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-3">{{ localize('Dashborad Logo & Favicon') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-4">{{ localize('Maintenance Mode') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-5">{{ localize('SEO Configuration') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-6">{{ localize('Cookie Consent') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection
