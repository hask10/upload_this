<div class="card h-100 rounded-4 package-card">
    <div class="card-body">
        <div class="tt-pricing-plan">
            <div class="tt-plan-name">
                @if ($package->is_featured)
                    <div class="tt-featured-badge text-end">
                        <span class="badge pe-3">{{ localize('Featured') }}</span>
                    </div>
                @endif
                <h5 class="mb-1">{{ $package->title }}</h5>
                <span class="text-muted">{{ $package->description }}</span>
            </div>
            <div class="tt-price-wrap d-flex align-items-center justify-content-between my-4">
                @if ($package->package_type == 'starter')
                    <div class="fs-1 fw-bold">
                        {{ localize('Free') }}
                    </div>
                @else
                    <div class="fs-1 fw-bold">

                        @if ((float) $package->price == 0.0)
                            {{ localize('Free') }}
                        @else
                            {{ formatPrice($package->price) }}
                        @endif
                    </div>
                @endif

            </div>

            {{-- action btns --}}
            @if ($package->package_type == 'starter')
                @guest
                    @include('frontend.default.pages.partials.home.subscribe-btn', [
                        'package' => $package,
                        'name' => 'Get Started',
                        'disabled' => false,
                    ])
                @endguest

                @auth
                    @if (auth()->user()->subscription_package_id == null)
                        @include('frontend.default.pages.partials.home.subscribe-btn', [
                            'package' => $package,
                            'name' => 'Get Started',
                            'disabled' => false,
                        ])
                    @else
                        @include('frontend.default.pages.partials.home.subscribe-btn', [
                            'package' => $package,
                            'name' => 'Applied on Regirsation',
                            'disabled' => true,
                        ])
                    @endif
                @endauth
            @else
                @if (Auth::check() && auth()->user()->subscription_package_id == $package->id)
                    @include('frontend.default.pages.partials.home.subscribe-btn', [
                        'package' => $package,
                        'name' => 'Renew Package',
                        'disabled' => false,
                    ])
                @else
                    @guest
                        @include('frontend.default.pages.partials.home.subscribe-btn', [
                            'package' => $package,
                            'name' => 'Get Started',
                            'disabled' => false,
                        ])
                    @endguest

                    @auth
                        @include('frontend.default.pages.partials.home.subscribe-btn', [
                            'package' => $package,
                            'name' => 'Subscribe',
                            'disabled' => false,
                        ])
                    @endauth
                @endif
            @endif
            {{-- action btns --}}

        </div>

        <div class="tt-pricing-feature">
            <ul class="tt-pricing-feature list-unstyled rounded mb-0">
                @php
                    $packageTemplatesCounter = $package->subscription_package_templates()->count();
                    
                @endphp

                @if ($package->show_open_ai_model == 1)
                    <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                            class="me-1">{{ optional($package->openai_model)->name }}</strong>{{ localize('Open AI Model') }}
                    </li>
                @endif

                <li>
                    <i data-feather="check-circle" class="icon-14 me-2 text-success"></i>
                    <a href="javascript::void(0);" class="text-underline text-dark" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" onclick="getPackageTemplates({{ $package->id }})">
                        <strong class="me-1">{{ $packageTemplatesCounter }}</strong> {{ localize('AI Templates') }}
                    </a>
                </li>

                <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                        class="me-1">{{ $package->total_words_per_month }}</strong>{{ localize('Words per month') }}
                </li>

                <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                        class="me-1">{{ $package->total_images_per_month }}</strong>{{ localize('Images per month') }}
                </li>

                <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                        class="me-1">{{ $package->total_speech_to_text_per_month }}</strong>{{ localize('Speech to Text per month') }}
                </li>

                <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i><strong
                        class="me-1">{{ $package->speech_to_text_filesize_limit }}
                        MB</strong>{{ localize('Audio file size limit') }}
                </li>

                <li><i
                        @if ($package->allow_ai_chat == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('AI Chat') }}
                </li>

                <li><i
                        @if ($package->allow_images == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('AI Images') }}
                </li>

                <li><i
                        @if ($package->allow_ai_code == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('AI Code') }}
                </li>

                <li><i
                        @if ($package->allow_speech_to_text == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('Speech to Text') }}
                </li>

                <li><i
                        @if ($package->allow_custom_templates == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('Custom Templates') }}
                </li>

                <li><i
                        @if ($package->has_live_support == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('Live Support') }}
                </li>

                <li><i
                        @if ($package->has_free_support == 1) data-feather="check-circle" class="icon-14 me-2 text-success" @else  data-feather="x-circle" class="icon-14 me-2 text-danger" @endif></i>{{ localize('Free Support') }}
                </li>


                @php
                    $otherFeatures = explode(',', $package->other_features);
                @endphp
                @if ($package->other_features)
                    @foreach ($otherFeatures as $feature)
                        <li><i data-feather="check-circle" class="icon-14 me-2 text-success"></i>{{ $feature }}
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
