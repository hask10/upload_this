@extends('backend.layouts.master')

@section('title')
    {{ localize('Generate Images') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('Generate Images') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item">{{ localize('AI Images') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <div class="card flex-column h-100">
                        <div class="card-header p-3 p-md-4 p-lg-5">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-lg-8 col-md-9 col-12">
                                    <!-- image generate form -->
                                    <form action="#" class="header-search-form generate-images-form" method="POST">
                                        @csrf
                                        <div class="row justify-content-between align-items-center pb-3">
                                            <div class="col-auto flex-grow-1">
                                                <div class="tt-promt-fild">
                                                    <div
                                                        class="d-flex align-items-center tt-advance-options cursor-pointer">
                                                        <label for="tt-advance-options"
                                                            class="form-label cursor-pointer mb-0 btn btn-outline-secondary btn-sm rounded-pill"><span
                                                                class="fw-bold tt-promot-number fw-bold me-1"><span
                                                                    class="me-1 cursor-pointer" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="{{ localize('Choose style, mood, resolution, number of results') }}"><i
                                                                        data-feather="help-circle"
                                                                        class="icon-14"></i></span></span>{{ localize('Advance Options') }}
                                                            <span><i data-feather="plus"
                                                                    class="icon-16 text-primary ms-2"></i></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                @if (auth()->user()->user_type == 'customer')
                                                    <div
                                                        class="d-flex align-items-center flex-column used-words-percentage">
                                                        @include('backend.pages.templates.inc.used-images-percentage')
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- advance options -->
                                        <div class="card mb-3" id="tt-advance-options">
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <div class="col-lg-3">
                                                        <div class="form-input">
                                                            <label for="style"
                                                                class="form-label">{{ localize('Image Style') }}
                                                                <span class="ms-1 cursor-pointer" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="{{ localize('Style of the image will be as selected') }}"><i
                                                                        data-feather="help-circle"
                                                                        class="icon-14"></i></span>
                                                            </label>
                                                            <select class="form-select select2" id="style"
                                                                name="style">
                                                                <option value="">{{ localize('None') }}</option>
                                                                <option value="Abstract">{{ localize('Abstract') }}
                                                                </option>
                                                                <option value="Realstic">{{ localize('Realstic') }}
                                                                </option>
                                                                <option value="Cartoon">{{ localize('Cartoon') }}</option>
                                                                <option value="Digital Art">{{ localize('Digital Art') }}
                                                                </option>
                                                                <option value="Illustration">{{ localize('Illustration') }}
                                                                </option>
                                                                <option value="Photography">{{ localize('Photography') }}
                                                                </option>
                                                                <option value="3D Render">{{ localize('3D Render') }}
                                                                </option>
                                                                <option value="Pencil Drawing">
                                                                    {{ localize('Pencil Drawing') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-input">
                                                            <label for="mood" class="form-label">{{ localize('Mood') }}
                                                                <span class="ms-1 cursor-pointer" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="{{ localize('Mood of the image will be as selected') }}"><i
                                                                        data-feather="help-circle"
                                                                        class="icon-14"></i></span>
                                                            </label>
                                                            <select class="form-select select2" id="mood"
                                                                name="mood">
                                                                <option value="">{{ localize('None') }}</option>
                                                                <option value="Angry">{{ localize('Angry') }}
                                                                </option>
                                                                <option value="Agressive">{{ localize('Agressive') }}
                                                                </option>

                                                                <option value="Calm">{{ localize('Calm') }}
                                                                </option>
                                                                <option value="Cheerful">{{ localize('Cheerful') }}
                                                                </option>
                                                                <option value="Chilling">{{ localize('Chilling') }}
                                                                </option>
                                                                <option value="Dark">{{ localize('Dark') }}
                                                                </option>
                                                                <option value="Happy">{{ localize('Happy') }}
                                                                </option>
                                                                <option value="Sad">{{ localize('Sad') }}
                                                                </option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-input"
                                                            @if (env('DEMO_MODE') == 'On') data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        data-bs-title="{{ localize('Disabled in demo') }}" @endif>
                                                            <label for="resolution"
                                                                class="form-label">{{ localize('Image Resolution') }}
                                                                <span class="ms-1 cursor-pointer" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="{{ localize('Select image resolution size that you need') }}"><i
                                                                        data-feather="help-circle"
                                                                        class="icon-14"></i></span>
                                                            </label>
                                                            <select class="form-select select2" id="resolution"
                                                                name="resolution" required
                                                                @if (env('DEMO_MODE') == 'On') disabled @endif>
                                                                <option value="256x256">{{ localize('Small [256x256]') }}
                                                                </option>
                                                                <option value="512x512">{{ localize('Medium [512x512]') }}
                                                                </option>
                                                                <option value="1024x1024">
                                                                    {{ localize('Large [1024x1024]') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-input"
                                                            @if (env('DEMO_MODE') == 'On') data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="{{ localize('Disabled in demo') }}" @endif>
                                                            <label for="num_of_results"
                                                                class="form-label">{{ localize('Number of Results') }}
                                                                <span class="ms-1 cursor-pointer" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-title="{{ localize('Select how many variations of result you want') }}"><i
                                                                        data-feather="help-circle"
                                                                        class="icon-14"></i></span>
                                                            </label>
                                                            <select class="form-select select2" id="num_of_results"
                                                                name="num_of_results" required
                                                                @if (env('DEMO_MODE') == 'On') disabled @endif>
                                                                <option value="1"
                                                                    @if (getSetting('default_number_of_results') == '1') selected @endif>
                                                                    1
                                                                </option>
                                                                <option value="2"
                                                                    @if (getSetting('default_number_of_results') == '2') selected @endif>
                                                                    2
                                                                </option>
                                                                <option value="3"
                                                                    @if (getSetting('default_number_of_results') == '3') selected @endif>
                                                                    3
                                                                </option>
                                                                <option value="4"
                                                                    @if (getSetting('default_number_of_results') == '4') selected @endif>
                                                                    4
                                                                </option>
                                                                <option value="5"
                                                                    @if (getSetting('default_number_of_results') == '5') selected @endif>
                                                                    5
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- image generate form -->
                                        <div class="input-group">
                                            <input
                                                class="form-control border border-2 border-primary rounded-pill rounded-end"
                                                type="text" id="title" name="title"
                                                placeholder="{{ localize('Type your image title or description that you are looking for') }}"
                                                required>
                                            <div class="input-group-append">
                                                <button type="submit"
                                                    class="btn btn-link bg-primary border border-2 border-primary text-light rounded-pill rounded-start btn-create-content"><i
                                                        class="flaticon-search translate-middle-y"></i>{{ localize('Generate Image') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column h-100">
                            <h5 class="mb-4">{{ localize('Generated Image Result') }}</h5>
                            <div class="row g-3 tt-image-gallery ai-images-wrapper">
                                @include('backend.pages.templates.inc.images-list', [
                                    'images' => $images,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    @include('backend.pages.templates.inc.template-scripts')
@endsection
