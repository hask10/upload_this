<section class="tt-hero-section ptb-100 bg-image-hero bg-dark position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
                <div class="tt-hero-content-wrap text-center text-light my-5 z-10">
                    <h1 class="fw-bold display-4 text-light">
                        <div>{{ getSetting('hero_title') }}</div>
                        <div class="tt-text-gradient-primary">{{ getSetting('hero_colorful_title') }}</div>
                    </h1>
                    <p class="lead">{{ getSetting('hero_sub_title') }}</p>


                    @php
                        $starterActive = false;
                        $starter = \App\Models\SubscriptionPackage::isActive()
                            ->where('id', 1)
                            ->first();
                        if (!is_null($starter)) {
                            $starterActive = true;
                        }
                    @endphp

                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary mt-4">{{ localize('Start Writing') }}
                        @if ($starterActive)
                            - {{ localize('It\'s Free') }}
                        @endif
                    </a>
                </div>
                <div class="tt-hero-img my-5 parallax-element">
                    <img src="{{ uploadedAsset(getSetting('hero_background_image')) }}" alt=""
                        class="img-fluid position-relative tt-dashboard-img overflow-hidden rounded-4">
                    <div class="position-absolute tt-robot-img">
                        <img src="{{ uploadedAsset(getSetting('hero_animated_image')) }}" alt="robot"
                            class="parallax-item">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
