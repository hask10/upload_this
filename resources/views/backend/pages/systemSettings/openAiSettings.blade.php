@extends('backend.layouts.master')

@section('title')
    {{ localize('Open AI Settings') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('Open AI Settings') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ localize('Open AI') }}</li>
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

                                <div class="mb-3">
                                    <label for="default_creativity"
                                        class="form-label">{{ localize('Default Creativity Level') }}
                                        <span class="text-danger ms-1">*</span></label>
                                    <input type="hidden" name="types[]" value="default_creativity">
                                    <select class="form-select select2" id="default_creativity" name="default_creativity"
                                        required>
                                        <option value="1" @if (getSetting('default_creativity') == '1') selected @endif>
                                            {{ localize('High') }}
                                        </option>
                                        <option value="0.5" @if (getSetting('default_creativity') == '0.5') selected @endif>
                                            {{ localize('Medium') }}
                                        </option>
                                        <option value="0" @if (getSetting('default_creativity') == '0') selected @endif>
                                            {{ localize('Low') }}
                                        </option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="default_tone"
                                        class="form-label">{{ localize('Default Tone Of Output Result') }}
                                        <span class="text-danger ms-1">*</span></label>
                                    <input type="hidden" name="types[]" value="default_tone">
                                    <select class="form-select select2" id="default_tone" name="default_tone" required>
                                        <option value="Friendly" @if (getSetting('default_tone') == 'Friendly') selected @endif>
                                            {{ localize('Friendly') }}</option>
                                        <option value="Luxury" @if (getSetting('default_tone') == 'Luxury') selected @endif>
                                            {{ localize('Luxury') }}
                                        </option>
                                        <option value="Relaxed" @if (getSetting('default_tone') == 'Relaxed') selected @endif>
                                            {{ localize('Relaxed') }}
                                        </option>
                                        <option value="Professional" @if (getSetting('default_tone') == 'Professional') selected @endif>
                                            {{ localize('Professional') }}</option>
                                        <option value="Casual" @if (getSetting('default_tone') == 'Casual') selected @endif>
                                            {{ localize('Casual') }}
                                        </option>
                                        <option value="Excited" @if (getSetting('default_tone') == 'Excited') selected @endif>
                                            {{ localize('Excited') }}
                                        </option>
                                        <option value="Bold" @if (getSetting('default_tone') == 'Bold') selected @endif>
                                            {{ localize('Bold') }}
                                        </option>
                                        <option value="Masculine" @if (getSetting('default_tone') == 'Masculine') selected @endif>
                                            {{ localize('Masculine') }}</option>
                                        <option value="Dramatic" @if (getSetting('default_tone') == 'Dramatic') selected @endif>
                                            {{ localize('Dramatic') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="default_number_of_results"
                                        class="form-label">{{ localize('Default Number Of Results') }}
                                        <span class="text-danger ms-1">*</span></label>
                                    <input type="hidden" name="types[]" value="default_number_of_results">
                                    <select class="form-select select2" id="default_number_of_results"
                                        name="default_number_of_results" required>
                                        <option value="1" @if (getSetting('default_number_of_results') == '1') selected @endif>1
                                        </option>
                                        <option value="2" @if (getSetting('default_number_of_results') == '2') selected @endif>2
                                        </option>
                                        <option value="3" @if (getSetting('default_number_of_results') == '3') selected @endif>3
                                        </option>
                                        <option value="4" @if (getSetting('default_number_of_results') == '4') selected @endif>4
                                        </option>
                                        <option value="5" @if (getSetting('default_number_of_results') == '5') selected @endif>5
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="default_max_result_length"
                                        class="form-label">{{ localize('Default Max Result Length') }}<span
                                            class="text-danger ms-1">*</span> <span class="ms-1 cursor-pointer"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="{{ localize('Insert -1 to make it unlimited') }}"><i
                                                data-feather="help-circle" class="icon-14"></i></span></label>
                                    <input type="hidden" name="types[]" value="default_max_result_length">
                                    <input type="number" id="default_max_result_length" name="default_max_result_length"
                                        class="form-control" value="{{ getSetting('default_max_result_length') }}"
                                        min="-1">
                                </div>

                                <div class="mb-3">
                                    <label for="ai_filter_bad_words" class="form-label">{{ localize('Bad Words') }}<span
                                            class="text-danger ms-1">*</span> <span class="ms-1 cursor-pointer"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="{{ localize('These words will be filtered from user inputs while generating contents') }}"><i
                                                data-feather="help-circle" class="icon-14"></i></span></label>
                                    <input type="hidden" name="types[]" value="ai_filter_bad_words">
                                    <textarea type="number" id="ai_filter_bad_words" name="ai_filter_bad_words" class="form-control">{{ getSetting('ai_filter_bad_words') }}</textarea>
                                    <small>* {{ localize('Comma Separated: One, Two') }}</small>
                                </div>

                                <div class="mb-3">
                                    <label for="enable_streamline"
                                        class="form-label">{{ localize('Enable Streamline Effect') }}</label>
                                    <input type="hidden" name="types[]" value="enable_streamline">
                                    <select id="enable_streamline" class="form-control text-uppercase select2"
                                        name="enable_streamline" data-toggle="select2">
                                        <option value="" disabled selected>{{ localize('Set streamline status') }}
                                        </option>
                                        <option value="1"
                                            {{ getSetting('enable_streamline') == '1' ? 'selected' : '' }}>
                                            {{ localize('Enable') }}</option>
                                        <option value="0"
                                            {{ getSetting('enable_streamline') == '0' ? 'selected' : '' }}>
                                            {{ localize('Disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--general settings-->


                        <!--ai model settings-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Open AI Model') }}</h5>
                                <div class="mb-3">
                                    <label for="default_open_ai_model"
                                        class="form-label">{{ localize('Default AI Model') }}
                                        <span class="text-danger ms-1">*</span> <a class="ms-1"
                                            href="https://platform.openai.com/docs/models/gpt-3-5" target="_blank"
                                            rel="noopener noreferrer"><i data-feather="info"
                                                class="icon-16"></i></a></label>
                                    <input type="hidden" name="types[]" value="default_open_ai_model">

                                    <select id="default_open_ai_model" class="form-control select2"
                                        name="default_open_ai_model">
                                        @foreach (\App\Models\OpenAiModel::all() as $openAiModel)
                                            <option value="{{ $openAiModel->key }}"
                                                @if (getSetting('default_open_ai_model') == $openAiModel->key) selected @endif>
                                                {{ $openAiModel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--ai model settings-->

                        <!--ai api key-->
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Open AI Secret Key') }}</h5>
                                <div class="mb-3">
                                    <label for="OPENAI_SECRET_KEY"
                                        class="form-label">{{ localize('Open AI Secret Key') }}<span
                                            class="text-danger ms-1">*</span></label>
                                    <input type="hidden" name="types[]" value="OPENAI_SECRET_KEY">
                                    <input type="text" id="OPENAI_SECRET_KEY" name="OPENAI_SECRET_KEY"
                                        class="form-control" placeholder="****************************************">
                                </div>
                            </div>
                        </div>
                        <!--ai api key-->

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
                                        <a href="#section-2">{{ localize('Open AI Model') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-3">{{ localize('Open AI Secret Key') }}</a>
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
