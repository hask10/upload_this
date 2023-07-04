@foreach ($packages as $package)
    <div class="col-12 col-lg-4">
        <input type="hidden" value="{{ $package->id }}" class="package_id">
        <div class="card h-100 package-card">
            <div class="card-body">
                <div class="tt-pricing-plan">
                    {{-- name & desc --}}
                    <div class="tt-plan-name">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 tt_update_text" data-name="package-name-{{ $package->id }}">
                                {{ $package->title }}
                            </h5>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-name-{{ $package->id }}" data-feather="edit-3"></i></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted tt_update_text"
                                data-name="package-description-{{ $package->id }}">{{ $package->description }}</span>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-description-{{ $package->id }}"
                                    data-feather="edit-3"></i></span>
                        </div>
                    </div>

                    {{-- price --}}
                    <div class="tt-price-wrap d-flex align-items-center justify-content-between mt-4 mb-3">
                        @if ($package->package_type == 'starter')
                            <div class="monthly-price fs-1 fw-bold">
                                {{ localize('Free') }}
                            </div>
                        @else
                            <div class="monthly-price fs-1 fw-bold">
                                $<span class="tt_update_text" onkeypress="nonNumericFilter()"
                                    data-name="package-price-{{ $package->id }}">{{ $package->price }}</span>
                                <sup><span class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ localize('Set $0 to make it free') }}"><i
                                            data-feather="help-circle" class="icon-14"></i></span></sup>
                            </div>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-price-{{ $package->id }}" data-feather="edit-3"></i></span>
                        @endif
                    </div>
                </div>

                <div class="tt-pricing-feature">
                    <ul class="tt-pricing-feature list-unstyled rounded mb-0">

                        <li class="d-flex justify-content-between align-items-center">
                            @php
                                $packageTemplatesCounter = $package->subscription_package_templates()->count();
                            @endphp

                            <span><i data-feather="check-circle"
                                    class="icon-14 me-2 text-success"></i><strong>{{ $packageTemplatesCounter }}</strong>
                                {{ localize('AI Templates') }} </span>
                            <span><i class="cursor-pointer icon-14" data-name="package-template-{{ $package->id }}"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" data-feather="edit-3"
                                    onclick="getPackageTemplates(this)"></i></span>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                                    class="tt_update_text" data-name="package-words-{{ $package->id }}"
                                    onkeypress="nonNumericFilter()">{{ $package->total_words_per_month }}</strong>
                                {{ localize('Words per month') }}</span>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-words-{{ $package->id }}" data-feather="edit-3"></i></span>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                                    class="tt_update_text" data-name="package-images-{{ $package->id }}"
                                    onkeypress="nonNumericFilter()">{{ $package->total_images_per_month }}</strong>
                                {{ localize('Images per month') }}</span>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-images-{{ $package->id }}" data-feather="edit-3"></i></span>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                                    class="tt_update_text" data-name="package-speech-to-text-{{ $package->id }}"
                                    onkeypress="nonNumericFilter()">{{ $package->total_speech_to_text_per_month }}</strong>
                                {{ localize('Speech to Text per month') }}</span>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-speech-to-text-{{ $package->id }}"
                                    data-feather="edit-3"></i></span>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                                    class="tt_update_text" data-name="package-audio-size-{{ $package->id }}"
                                    onkeypress="nonNumericFilter()">{{ $package->speech_to_text_filesize_limit }}</strong>
                                MB {{ localize('Audio file size limit') }}</span>
                            <span><i class="tt_editable cursor-pointer icon-14"
                                    data-name="package-audio-size-{{ $package->id }}"
                                    data-feather="edit-3"></i></span>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="allow_ai_chat-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Allow AI Chat') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    id="allow_ai_chat-{{ $package->id }}"
                                    data-name="allow_ai_chat-{{ $package->id }}"
                                    @if ($package->allow_ai_chat == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="allow_images-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Allow AI Images') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    id="allow_images-{{ $package->id }}"
                                    data-name="allow_images-{{ $package->id }}"
                                    @if ($package->allow_images == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="allow_ai_code-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Allow AI Code') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    id="allow_ai_code-{{ $package->id }}"
                                    data-name="allow_ai_code-{{ $package->id }}"
                                    @if ($package->allow_ai_code == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="allow_speech_to_text-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Speech to Text') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    data-name="allow_speech_to_text-{{ $package->id }}"
                                    id="allow_speech_to_text-{{ $package->id }}"
                                    @if ($package->allow_speech_to_text == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="allow_custom_templates-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Custom Templates') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    data-name="allow_custom_templates-{{ $package->id }}"
                                    id="allow_custom_templates-{{ $package->id }}"
                                    @if ($package->allow_custom_templates == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="has_live_support-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Live Support') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    data-name="has_live_support-{{ $package->id }}"
                                    id="has_live_support-{{ $package->id }}"
                                    @if ($package->has_live_support == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="has_free_support-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Free Support') }}</label></span>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    id="has_free_support-{{ $package->id }}"
                                    data-name="has_free_support-{{ $package->id }}"
                                    @if ($package->has_free_support == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center">
                            <span><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><label
                                    for="is_featured-{{ $package->id }}"
                                    class="cursor-pointer">{{ localize('Is Featured?') }}</label></span>
                            <div class="form-check form-switch ms-2">
                                <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                    id="is_featured-{{ $package->id }}" data-name="is_featured-{{ $package->id }}"
                                    @if ($package->is_featured == 1) checked @endif>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center ">
                                <i data-feather="check-circle" class="icon-14 me-2 text-success"></i>
                                <select class="form-select py-1 package_open_ai_model" name="openai_model_id"
                                    onchange="handleModelChange(this)">
                                    <option value="" disabled>{{ localize('Select Open AI Model') }}</option>
                                    @foreach ($openAiModels as $openAiModel)
                                        <option value="{{ $openAiModel->id }}"
                                            @if ($package->openai_model_id == $openAiModel->id) selected @endif>
                                            {{ $openAiModel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="ms-3 d-flex align-items-center justify-content-end">
                                <span><label for="show_open_ai_model-{{ $package->id }}"
                                        class="cursor-pointer">{{ localize('Show?') }}</label></span>
                                <div class="form-check form-switch ms-2">
                                    <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                                        id="show_open_ai_model-{{ $package->id }}"
                                        data-name="show_open_ai_model-{{ $package->id }}"
                                        @if ($package->show_open_ai_model == 1) checked @endif>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex flex-column align-items-start">
                            <div class="w-100 d-flex align-items-center">
                                <i data-feather="check-circle" class="icon-14 me-2 text-success"></i>
                                <input class="form-control other_features" type="text"
                                    placeholder="{{ localize('Type additional features') }}"
                                    value="{{ $package->other_features }}" />
                            </div>
                            <small class="text-muted ps-4">*
                                {{ localize('Comma separated: Feature A,Feature B') }}</small>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex align-items-center">

                    <span class="ms-1"><label for="is_active-{{ $package->id }}"
                            class="cursor-pointer">{{ localize('Is Active?') }}</label></span>
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input cursor-pointer tt_editable"
                            id="is_active-{{ $package->id }}" data-name="is_active-{{ $package->id }}"
                            @if ($package->is_active == 1) checked @endif>
                    </div>
                </div>
                @if ($package->package_type == 'starter')
                    <small class="text-muted">*
                        {{ localize('If active, this will be applied to new user\'s registration.') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
@endforeach

<div class="col-12 col-lg-4 min-h-400">
    <div class="card h-100 tt-add-more-card justify-content-center">
        <div class="card-body text-center">
            <button type="button" class="btn btn-primary rounded-circle btn-icon" onclick="showNewModal(this)"><i
                    data-feather="plus"></i></button>
        </div>
    </div>
</div>
