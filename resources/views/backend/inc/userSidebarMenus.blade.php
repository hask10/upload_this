@php
    $user = auth()->user();
    $package = $user->SubscriptionPackage != null ? $user->SubscriptionPackage : new \App\Models\SubscriptionPackage();
@endphp

<ul class="tt-side-nav">

    <!-- dashboard -->
    <li class="side-nav-item nav-item">
        <a href="{{ route('writebot.dashboard') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"><i data-feather="pie-chart"></i></span>
            <span class="tt-nav-link-text">{{ localize('Dashboard') }}</span>
        </a>
    </li>

    <!-- subscriptions -->
    @php
        $subscriptionActiveRoutes = ['subscriptions.index'];
    @endphp
    <li class="side-nav-item nav-item {{ areActiveRoutes($subscriptionActiveRoutes, 'tt-menu-item-active') }}">
        <a data-bs-toggle="collapse" href="#subscription"
            aria-expanded="{{ areActiveRoutes($subscriptionActiveRoutes, 'true') }}" aria-controls="subscription"
            class="side-nav-link tt-menu-toggle">
            <span class="tt-nav-link-icon"><i data-feather="zap"></i></span>
            <span class="tt-nav-link-text">{{ localize('Subscriptions') }}</span>
        </a>
        <div class="collapse {{ areActiveRoutes($subscriptionActiveRoutes, 'show') }}" id="subscription">
            <ul class="side-nav-second-level">
                <li class="{{ areActiveRoutes(['subscriptions.histories.index'], 'tt-menu-item-active') }}">
                    <a href="{{ route('subscriptions.histories.index') }}">{{ localize('Subscription Histories') }}</a>
                </li>
                <li class="{{ areActiveRoutes(['subscriptions.index'], 'tt-menu-item-active') }}">
                    <a href="{{ route('subscriptions.index') }}"
                        class="{{ areActiveRoutes(['subscriptions.index']) }}">{{ localize('Subscription Packages') }}</a>
                </li>
            </ul>
        </div>
    </li>


    @if (getSetting('enable_affiliate_system') == '1')
        <!-- affiliate system -->
        @php
            $affiliateActiveRoutes = ['affiliate.overview', 'affiliate.payout.configure', 'affiliate.withdraw.index', 'affiliate.earnings.index', 'affiliate.payments.index'];
        @endphp
        <li class="side-nav-item nav-item {{ areActiveRoutes($affiliateActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#affiliate"
                aria-expanded="{{ areActiveRoutes($affiliateActiveRoutes, 'true') }}" aria-controls="affiliate"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="percent"></i></span>
                <span class="tt-nav-link-text">{{ localize('Affiliate System') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($affiliateActiveRoutes, 'show') }}" id="affiliate">
                <ul class="side-nav-second-level">

                    <li class="{{ areActiveRoutes(['affiliate.overview'], 'tt-menu-item-active') }}">
                        <a href="{{ route('affiliate.overview') }}">{{ localize('Overview') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['affiliate.payout.configure'], 'tt-menu-item-active') }}">
                        <a href="{{ route('affiliate.payout.configure') }}">{{ localize('Payout Configuration') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['affiliate.earnings.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('affiliate.earnings.index') }}">{{ localize('Earning Histories') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['affiliate.withdraw.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('affiliate.withdraw.index') }}">{{ localize('Withdraw Requests') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['affiliate.payments.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('affiliate.payments.index') }}">{{ localize('Payment Histories') }}</a>
                    </li>

                </ul>
            </div>
        </li>
    @endif

    <!-- documents -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Manage Documents') }}</span>
    </li>

    <li
        class="side-nav-item nav-item {{ areActiveRoutes(['folders.index', 'folders.show', 'folders.edit', 'folders.update'], 'tt-menu-item-active') }}">
        <a href="{{ route('folders.index') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"> <i data-feather="folder-plus"></i></span>
            <span class="tt-nav-link-text">{{ localize('Folders') }}</span>
        </a>
    </li>

    <li
        class="side-nav-item nav-item {{ areActiveRoutes(['projects.index', 'projects.edit', 'projects.update'], 'tt-menu-item-active') }}">
        <a href="{{ route('projects.index') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"> <i data-feather="grid"></i></span>
            <span class="tt-nav-link-text">{{ localize('All Projects') }}</span>
        </a>
    </li>

    <!-- Templates -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('AI Tools') }}</span>
    </li>


    @if ($package->allow_ai_chat == 1)
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['chat.index', 'chat.experts'], 'tt-menu-item-active') }}">
            <a href="{{ route('chat.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="message-square"></i></span>
                <span class="tt-nav-link-text">{{ localize('AI Chat') }}</span>
            </a>
        </li>
    @endif

    <li
        class="side-nav-item nav-item {{ areActiveRoutes(['templates.index', 'templates.show'], 'tt-menu-item-active') }}">
        <a href="{{ route('templates.index') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"> <i data-feather="layers"></i></span>
            <span class="tt-nav-link-text">{{ localize('Templates') }}</span>
        </a>
    </li>

    <!-- custom tempaltes -->
    @if ($package->allow_custom_templates == 1)
        @php
            $customTemplateActiveRoutes = ['custom.templateCategories.index', 'custom.templateCategories.edit', 'custom.templates.index', 'custom.templates.create', 'custom.templates.edit', 'custom.templates.show'];
        @endphp
        <li class="side-nav-item nav-item {{ areActiveRoutes($customTemplateActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#customTemplates"
                aria-expanded="{{ areActiveRoutes($customTemplateActiveRoutes, 'true') }}"
                aria-controls="customTemplates" class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="codepen"></i></span>
                <span class="tt-nav-link-text">{{ localize('Custom Templates') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($customTemplateActiveRoutes, 'show') }}" id="customTemplates">
                <ul class="side-nav-second-level">
                    <li
                        class="{{ areActiveRoutes(['custom.templateCategories.index', 'custom.templateCategories.edit'], 'tt-menu-item-active') }}">
                        <a href="{{ route('custom.templateCategories.index') }}">{{ localize('Categories') }}</a>
                    </li>

                    <li
                        class="{{ areActiveRoutes(['custom.templates.index', 'custom.templates.create', 'custom.templates.edit', 'custom.templates.show'], 'tt-menu-item-active') }}">
                        <a href="{{ route('custom.templates.index') }}">{{ localize('All Templates') }}</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif

    @if ($package->allow_speech_to_text == 1)
        <li class="side-nav-item nav-item">
            <a href="{{ route('s2t.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="mic"></i></span>
                <span class="tt-nav-link-text">{{ localize('Speech to Text') }}</span>
            </a>
        </li>
    @endif

    @if ($package->allow_images == 1)
        <li class="side-nav-item nav-item">
            <a href="{{ route('images.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="image"></i></span>
                <span class="tt-nav-link-text">{{ localize('Generate Images') }}</span>
            </a>
        </li>
    @endif

    @if ($package->allow_ai_code == 1)
        <li class="side-nav-item nav-item">
            <a href="{{ route('codes.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="code"></i></span>
                <span class="tt-nav-link-text">{{ localize('Generate Code') }}</span>
            </a>
        </li>
    @endif

    <!-- popular templates -->
    <li class="side-nav-item nav-item">
        <a href="{{ route('templates.popular') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"><i data-feather="award"></i></span>
            <span class="tt-nav-link-text">{{ localize('Popular Templates') }}</span>
        </a>
    </li>

    <!-- favorite templates -->
    <li class="side-nav-item nav-item">
        <a href="{{ route('templates.favorites') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"><i data-feather="heart"></i></span>
            <span class="tt-nav-link-text">{{ localize('Favorite Templates') }}</span>
        </a>
    </li>
</ul>
