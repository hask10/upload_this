<div class="tt-single-template d-flex flex-column h-100 position-relative">
    <div
        class="card flex-column h-100 tt-template-card tt-corner-shape border-0 @if (Auth::check() && auth()->user()->user_type == 'customer' && !in_array($template->id, $subscriptionTemplates)) tt-is-inactive @endif">

        @auth
            @php
                $user = auth()->user();
            @endphp
            @if ($user->user_type == 'customer')
                @if (!in_array($template->id, $subscriptionTemplates))
                    <span class="text-danger tt-inactive-plan" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{ localize('This template not included in your subscription plan') }}"><i
                            data-feather="info" class="icon-14"></i></span>
                @endif
            @endif
        @endauth

        <a @if (Auth::check() && auth()->user()->user_type == 'customer' && !in_array($template->id, $subscriptionTemplates)) href="javascript::void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="{{ localize('This template not included in your subscription plan') }}" @else href="{{ route('templates.show', $template->code) }}" @endif
            class="card-body d-flex flex-column h-100">
            <div class="tt-card-info mb-4">
                <div class="tt-template-icon mb-3">
                    <img src="{{ staticAsset('backend/assets/img/templates/' . $template->code . '.png') }}"
                        alt="" class="img-fluid">
                </div>
                <h3 class="h6">{{ $template->name }}</h3>
                <p class="mb-0">{{ $template->description }}
                </p>
            </div>
            <div class="mt-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fs-md text-muted d-block">
                        @auth
                            @if (auth()->user()->user_type != 'customer')
                                {{ formatWords($template->total_words_generated) }}
                            @else
                                {{ formatWords($template->templateUsage()->sum('total_used_words')) }}
                            @endif
                            {{ localize('Words Generated') }}

                        @endauth
                        @guest
                            {{ formatWords($template->total_words_generated) }}
                            {{ localize('Words Generated') }}
                        @endguest
                    </span>
                </div>
            </div>
        </a>

        @auth
            <button
                class="border-0 bg-transparent tt-template-edit position-absolute d-flex align-items-center favorite-template"
                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ localize('Favorite') }}"
                data-template="{{ $template->id }}">
                <i
                    class="{{ in_array($template->id, $favoritesArray) ? 'las' : 'lar' }}  la-heart fs-lg {{ in_array($template->id, $favoritesArray) ? 'text-success' : '' }}"></i>
            </button>
        @endauth
        @guest
            <a class="border-0 bg-transparent tt-template-edit position-absolute d-flex align-items-center"
                href="{{ route('login') }}">
                <i class="lar la-heart fs-lg"></i>
            </a>
        @endguest
    </div>
</div>
