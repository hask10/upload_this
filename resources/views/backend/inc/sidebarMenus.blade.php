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
        $subscriptionActiveRoutes = ['subscriptions.index', 'subscriptions.create', 'subscriptions.edit', 'subscriptions.histories.index'];
    @endphp
    @canany(['subscriptions', 'subscriptions_histories'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($subscriptionActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#subscription"
                aria-expanded="{{ areActiveRoutes($subscriptionActiveRoutes, 'true') }}" aria-controls="subscription"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="zap"></i></span>
                <span class="tt-nav-link-text">{{ localize('Subscriptions') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($subscriptionActiveRoutes, 'show') }}" id="subscription">
                <ul class="side-nav-second-level">

                    @can('subscriptions_histories')
                        <li class="{{ areActiveRoutes(['subscriptions.histories.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('subscriptions.histories.index') }}">{{ localize('Subscription Histories') }}</a>
                        </li>
                    @endcan

                    @can('subscriptions')
                        <li
                            class="{{ areActiveRoutes(['subscriptions.index', 'subscriptions.create', 'subscriptions.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('subscriptions.index') }}"
                                class="{{ areActiveRoutes(['subscriptions.index', 'subscriptions.create', 'subscriptions.edit']) }}">{{ localize('Subscription Packages') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    <!-- affiliate system -->
    @php
        $affiliateActiveRoutes = ['affiliate.configurations', 'affiliate.withdraw.index', 'affiliate.earnings.index', 'affiliate.payments.index'];
    @endphp
    @canany(['affiliate_configurations', 'affiliate_withdraw', 'affiliate_earning_histories'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($affiliateActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#affiliate"
                aria-expanded="{{ areActiveRoutes($affiliateActiveRoutes, 'true') }}" aria-controls="affiliate"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="percent"></i></span>
                <span class="tt-nav-link-text">{{ localize('Affiliate System') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($affiliateActiveRoutes, 'show') }}" id="affiliate">
                <ul class="side-nav-second-level">

                    @can('affiliate_configurations')
                        <li class="{{ areActiveRoutes(['affiliate.configurations'], 'tt-menu-item-active') }}">
                            <a href="{{ route('affiliate.configurations') }}">{{ localize('Configurations') }}</a>
                        </li>
                    @endcan

                    @if (getSetting('enable_affiliate_system') == '1')
                        @can('affiliate_withdraw')
                            <li class="{{ areActiveRoutes(['affiliate.withdraw.index'], 'tt-menu-item-active') }}">
                                <a href="{{ route('affiliate.withdraw.index') }}">{{ localize('Withdraw Requests') }}</a>
                            </li>
                        @endcan

                        @can('affiliate_earning_histories')
                            <li class="{{ areActiveRoutes(['affiliate.earnings.index'], 'tt-menu-item-active') }}">
                                <a href="{{ route('affiliate.earnings.index') }}">{{ localize('Earning Histories') }}</a>
                            </li>
                        @endcan

                        @can('affiliate_payment_histories')
                            <li class="{{ areActiveRoutes(['affiliate.payments.index'], 'tt-menu-item-active') }}">
                                <a href="{{ route('affiliate.payments.index') }}">{{ localize('Payment Histories') }}</a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </div>
        </li>
    @endcan


    @canany(['folders', 'projects'])
        <!-- documents -->
        <li class="side-nav-title side-nav-item nav-item mt-3">
            <span class="tt-nav-title-text text-muted">{{ localize('Manage Documents') }}</span>
        </li>

        @can('folders')
            <li
                class="side-nav-item nav-item {{ areActiveRoutes(['folders.index', 'folders.show', 'folders.edit', 'folders.update'], 'tt-menu-item-active') }}">
                <a href="{{ route('folders.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="folder-plus"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Folders') }}</span>
                </a>
            </li>
        @endcan

        @can('projects')
            <li
                class="side-nav-item nav-item {{ areActiveRoutes(['projects.index', 'projects.edit', 'projects.update'], 'tt-menu-item-active') }}">
                <a href="{{ route('projects.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="grid"></i></span>
                    <span class="tt-nav-link-text">{{ localize('All Projects') }}</span>
                </a>
            </li>
        @endcan
    @endcanany

    @canany(['ai_chat', 'chat.experts', 'templates', 'speech_to_text', 'generate_images', 'generate_code'])

        <!-- Templates -->
        <li class="side-nav-title side-nav-item nav-item mt-3">
            <span class="tt-nav-title-text text-muted">{{ localize('AI Tools') }}</span>
        </li>

        @can('ai_chat')
            <li class="side-nav-item nav-item {{ areActiveRoutes(['chat.index', 'chat.experts'], 'tt-menu-item-active') }}">
                <a href="{{ route('chat.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="message-square"></i></span>
                    <span class="tt-nav-link-text">{{ localize('AI Chat') }}</span>
                </a>
            </li>
        @endcan

        @can('templates')
            <li
                class="side-nav-item nav-item {{ areActiveRoutes(['templates.index', 'templates.show'], 'tt-menu-item-active') }}">
                <a href="{{ route('templates.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="layers"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Templates') }}</span>
                </a>
            </li>
        @endcan

        <!-- custom tempaltes -->
        @php
            $customTemplateActiveRoutes = ['custom.templateCategories.index', 'custom.templateCategories.edit', 'custom.templates.index', 'custom.templates.create', 'custom.templates.edit', 'custom.templates.show'];
        @endphp
        @canany(['custom_template_categories', 'custom_templates'])
            <li class="side-nav-item nav-item {{ areActiveRoutes($customTemplateActiveRoutes, 'tt-menu-item-active') }}">
                <a data-bs-toggle="collapse" href="#customTemplates"
                    aria-expanded="{{ areActiveRoutes($customTemplateActiveRoutes, 'true') }}" aria-controls="customTemplates"
                    class="side-nav-link tt-menu-toggle">
                    <span class="tt-nav-link-icon"><i data-feather="codepen"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Custom Templates') }}</span>
                </a>
                <div class="collapse {{ areActiveRoutes($customTemplateActiveRoutes, 'show') }}" id="customTemplates">
                    <ul class="side-nav-second-level">
                        @can('custom_template_categories')
                            <li
                                class="{{ areActiveRoutes(['custom.templateCategories.index', 'custom.templateCategories.edit'], 'tt-menu-item-active') }}">
                                <a href="{{ route('custom.templateCategories.index') }}">{{ localize('Categories') }}</a>
                            </li>
                        @endcan

                        @can('custom_templates')
                            <li
                                class="{{ areActiveRoutes(['custom.templates.index', 'custom.templates.create', 'custom.templates.edit', 'custom.templates.show'], 'tt-menu-item-active') }}">
                                <a href="{{ route('custom.templates.index') }}">{{ localize('All Templates') }}</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan


        @can('speech_to_text')
            <li class="side-nav-item nav-item">
                <a href="{{ route('s2t.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="mic"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Speech to Text') }}</span>
                </a>
            </li>
        @endcan


        @can('generate_images')
            <li class="side-nav-item nav-item">
                <a href="{{ route('images.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="image"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Generate Images') }}</span>
                </a>
            </li>
        @endcan

        @can('generate_code')
            <li class="side-nav-item nav-item">
                <a href="{{ route('codes.index') }}" class="side-nav-link">
                    <span class="tt-nav-link-icon"> <i data-feather="code"></i></span>
                    <span class="tt-nav-link-text">{{ localize('Generate Code') }}</span>
                </a>
            </li>
        @endcan

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

    @endcanany


    <!-- Report -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Reports') }}</span>
    </li>
    <!-- Report -->
    @php
        $reportsActiveRoutes = ['admin.reports.words', 'admin.reports.codes', 'admin.reports.images', 'admin.reports.s2t', 'admin.reports.mostUsed', 'admin.reports.subscriptions'];
    @endphp
    @canany(['words_report', 'codes_report', 'images_report', 's2t_report', 'most_used_templates',
        'subscriptions_reports'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($reportsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#reports"
                aria-expanded="{{ areActiveRoutes($reportsActiveRoutes, 'true') }}" aria-controls="reports"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="bar-chart"></i></span>
                <span class="tt-nav-link-text">{{ localize('Reports') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($reportsActiveRoutes, 'show') }}" id="reports">
                <ul class="side-nav-second-level">
                    @can('words_report')
                        <li class="{{ areActiveRoutes(['admin.reports.words'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.words') }}">{{ localize('Words Report') }}</a>
                        </li>
                    @endcan

                    @can('codes_report')
                        <li class="{{ areActiveRoutes(['admin.reports.codes'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.codes') }}">{{ localize('Codes Report') }}</a>
                        </li>
                    @endcan

                    @can('images_report')
                        <li class="{{ areActiveRoutes(['admin.reports.images'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.images') }}">{{ localize('Images Report') }}</a>
                        </li>
                    @endcan

                    @can('s2t_report')
                        <li class="{{ areActiveRoutes(['admin.reports.s2t'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.s2t') }}">{{ localize('Speech to Texts') }}</a>
                        </li>
                    @endcan

                    @can('most_used_templates')
                        <li class="{{ areActiveRoutes(['admin.reports.mostUsed'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.mostUsed') }}">{{ localize('Most Used Templates') }}</a>
                        </li>
                    @endcan

                    @can('subscriptions_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.subscriptions'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.subscriptions') }}">{{ localize('Subscriptions Reports') }}</a>
                        </li>
                    @endcan

                    {{-- @can('custom_template_categories')
                        <li
                            class="{{ areActiveRoutes(['custom.templateCategories.index', 'custom.templateCategories.edit'], 'tt-menu-item-active') }}">
                            <a
                                href="{{ route('custom.templateCategories.index') }}">{{ localize('Affiliate Commissions') }}</a>
                        </li>
                    @endcan --}}
                </ul>
            </div>
        </li>
    @endcan


    <!-- Users -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Manage Users') }}</span>
    </li>

    <!-- customers -->
    @can('customers')
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.customers.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="users"></i></span>
                <span class="tt-nav-link-text">{{ localize('Customers') }}</span>
            </a>
        </li>
    @endcan

    <!-- staffs -->
    @can('staffs')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.staffs.index', 'admin.staffs.create', 'admin.staffs.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.staffs.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="user-check"></i></span>
                <span class="tt-nav-link-text">{{ localize('Employee Staffs') }}</span>
            </a>
        </li>
    @endcan


    @canany(['contact_us_messages'])
        <!-- support -->
        <li class="side-nav-title side-nav-item nav-item mt-3">
            <span class="tt-nav-title-text text-muted">{{ localize('Support') }}</span>
        </li>

        <!-- conatact us -->
        @can('contact_us_messages')
            <li class="side-nav-item nav-item {{ areActiveRoutes(['admin.queries.index'], 'tt-menu-item-active') }}">
                <a href="{{ route('admin.queries.index') }}"
                    class="side-nav-link {{ areActiveRoutes(['admin.queries.index']) }}">
                    <span class="tt-nav-link-icon"><i data-feather="headphones"></i></span>
                    <span class="tt-nav-link-text">
                        <span>{{ localize('Queries') }}</span>

                        @php
                            $newMsgCount = \App\Models\ContactUsMessage::where('is_seen', 0)->count();
                        @endphp

                        @if ($newMsgCount > 0)
                            <small class="badge bg-danger">{{ localize('New') }}</small>
                        @endif
                    </span>
                </a>
            </li>
        @endcan
    @endcanany


    <!-- Contents -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Manage Contents') }}</span>
    </li>

    <!-- tags -->
    @php
        $tagsActiveRoutes = ['admin.tags.index', 'admin.tags.edit'];
    @endphp
    @can('tags')
        <li class="side-nav-item nav-item {{ areActiveRoutes($tagsActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.tags.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="tag"></i></span>
                <span class="tt-nav-link-text">{{ localize('Tags') }}</span>
            </a>
        </li>
    @endcan

    <!-- Blog Systems -->
    @php
        $blogActiveRoutes = ['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit', 'admin.blogCategories.index', 'admin.blogCategories.edit'];
    @endphp
    @canany(['blogs', 'blog_categories'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($blogActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#blogSystem"
                aria-expanded="{{ areActiveRoutes($blogActiveRoutes, 'true') }}" aria-controls="blogSystem"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="file-text"></i></span>
                <span class="tt-nav-link-text">{{ localize('Blogs') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($blogActiveRoutes, 'show') }}" id="blogSystem">
                <ul class="side-nav-second-level">
                    @can('blogs')
                        <li
                            class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogs.index') }}"
                                class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit']) }}">{{ localize('All Blogs') }}</a>
                        </li>
                    @endcan

                    @can('blog_categories')
                        <li
                            class="{{ areActiveRoutes(['admin.blogCategories.index', 'admin.blogCategories.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogCategories.index') }}">{{ localize('Categories') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan


    <!-- pages -->
    @php
        $pagesActiveRoutes = ['admin.pages.index', 'admin.pages.create', 'admin.pages.edit'];
    @endphp
    @can('pages')
        <li class="side-nav-item nav-item {{ areActiveRoutes($pagesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.pages.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="copy"></i></span>
                <span class="tt-nav-link-text">{{ localize('Pages') }}</span>
            </a>
        </li>
    @endcan

    <!-- faq -->
    @php
        $faqsActiveRoutes = ['admin.faqs.index', 'admin.faqs.edit'];
    @endphp
    @can('faqs')
        <li class="side-nav-item nav-item {{ areActiveRoutes($faqsActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.faqs.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="help-circle"></i></span>
                <span class="tt-nav-link-text">{{ localize('All FAQs') }}</span>
            </a>
        </li>
    @endcan


    <!-- media manager -->
    @can('media_manager')
        <li class="side-nav-item">
            <a href="{{ route('admin.mediaManager.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="folder"></i></span>
                <span class="tt-nav-link-text">{{ localize('Media Manager') }}</span>
            </a>
        </li>
    @endcan


    <!-- Promotions -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Manage Promotions') }}</span>
    </li>
    <!-- newsletter -->
    @php
        $newsletterActiveRoutes = ['admin.newsletters.index', 'admin.subscribers.index'];
    @endphp
    @canany(['newsletters', 'subscribers'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($newsletterActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#newsletter"
                aria-expanded="{{ areActiveRoutes($newsletterActiveRoutes, 'true') }}" aria-controls="newsletter"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="book-open"></i></span>
                <span class="tt-nav-link-text">{{ localize('Newsletters') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($newsletterActiveRoutes, 'show') }}" id="newsletter">
                <ul class="side-nav-second-level">

                    @can('newsletters')
                        <li class="{{ areActiveRoutes(['admin.newsletters.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.newsletters.index') }}"
                                class="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Bulk Emails') }}</a>
                        </li>
                    @endcan

                    @can('subscribers')
                        <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.subscribers.index') }}"
                                lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Subscribers') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    <!-- Settings -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text text-muted">{{ localize('Manage Settings') }}</span>
    </li>


    <!-- open AI -->
    @php
        $openAiActiveRoutes = ['admin.settings.openAi'];
    @endphp
    @can('open_ai')
        <li class="side-nav-item nav-item {{ areActiveRoutes($openAiActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.settings.openAi') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="aperture"></i></span>
                <span class="tt-nav-link-text">{{ localize('Open AI') }}</span>
            </a>
        </li>
    @endcan

    <!-- Appearance -->
    @php
        $appearanceActiveRoutes = ['admin.appearance.header', 'admin.appearance.homepage.hero', 'admin.appearance.homepage.trustedBy', 'admin.appearance.homepage.howItWorks', 'admin.appearance.homepage.featureImages', 'admin.appearance.homepage.clientFeedback', 'admin.appearance.homepage.editClientFeedback', 'admin.appearance.homepage.cta'];
        
        $homepageActiveRoutes = ['admin.appearance.homepage.hero', 'admin.appearance.homepage.trustedBy', 'admin.appearance.homepage.howItWorks', 'admin.appearance.homepage.featureImages', 'admin.appearance.homepage.clientFeedback', 'admin.appearance.homepage.editClientFeedback', 'admin.appearance.homepage.cta'];
    @endphp

    @canany(['homepage', 'about_us_page', 'header', 'footer'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($appearanceActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#Appearance"
                aria-expanded="{{ areActiveRoutes($appearanceActiveRoutes, 'true') }}" aria-controls="Appearance"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="layout"></i></span>
                <span class="tt-nav-link-text">{{ localize('Appearance') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($appearanceActiveRoutes, 'show') }}" id="Appearance">
                <ul class="side-nav-second-level">

                    @can('homepage')
                        <li class="{{ areActiveRoutes($homepageActiveRoutes, 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.homepage.hero') }}"
                                class="{{ areActiveRoutes($homepageActiveRoutes) }}">{{ localize('Homepage') }}</a>
                        </li>
                    @endcan

                    @can('header')
                        <li class="{{ areActiveRoutes(['admin.appearance.header'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.header') }}"
                                class="{{ areActiveRoutes(['admin.appearance.header']) }}">{{ localize('Header') }}</a>
                        </li>
                    @endcan

                    @can('footer')
                        <li class="{{ areActiveRoutes(['admin.appearance.footer'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.footer') }}"
                                class="{{ areActiveRoutes(['admin.appearance.footer']) }}">{{ localize('Footer') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcanany


    <!-- Roles & Permission -->
    @php
        $rolesActiveRoutes = ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'];
    @endphp
    @can('roles_and_permissions')
        <li class="side-nav-item nav-item {{ areActiveRoutes($rolesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="unlock"></i></span>
                <span class="tt-nav-link-text">{{ localize('Roles & Permissions') }}</span>
            </a>
        </li>
    @endcan


    <!-- system settings -->
    @php
        $settingsActiveRoutes = ['admin.generalSettings', 'admin.orderSettings', 'admin.timeslot.edit', 'admin.languages.index', 'admin.languages.edit', 'admin.currencies.index', 'admin.currencies.edit', 'admin.languages.localizations', 'admin.smtpSettings.index'];
    @endphp

    @canany(['smtp_settings', 'auth_settings', 'otp_settings', 'general_settings', 'payment_settings',
        'social_login_settings', 'currency_settings', 'language_settings'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($settingsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#systemSetting"
                aria-expanded="{{ areActiveRoutes($settingsActiveRoutes, 'true') }}" aria-controls="systemSetting"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="settings"></i></span>
                <span class="tt-nav-link-text">{{ localize('System Settings') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($settingsActiveRoutes, 'show') }}" id="systemSetting">
                <ul class="side-nav-second-level">

                    @can('auth_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.authSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.authSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.authSettings']) }}">{{ localize('Auth Settings') }}</a>
                        </li>
                    @endcan

                    @can('otp_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.otpSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.otpSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.otpSettings']) }}">{{ localize('OTP Settings') }}</a>
                        </li>
                    @endcan


                    @can('smtp_settings')
                        <li class="{{ areActiveRoutes(['admin.smtpSettings.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.smtpSettings.index') }}"
                                class="{{ areActiveRoutes(['admin.smtpSettings.index']) }}">{{ localize('SMTP Settings') }}</a>
                        </li>
                    @endcan

                    @can('general_settings')
                        <li class="{{ areActiveRoutes(['admin.generalSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.generalSettings') }}"
                                class="{{ areActiveRoutes(['admin.generalSettings']) }}">{{ localize('General Settings') }}</a>
                        </li>
                    @endcan

                    @can('payment_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.paymentMethods'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.paymentMethods') }}"
                                class="{{ areActiveRoutes(['admin.settings.paymentMethods']) }}">{{ localize('Payment Methods') }}</a>
                        </li>
                    @endcan

                    @can('social_login_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.socialLogin'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.socialLogin') }}"
                                class="{{ areActiveRoutes(['admin.settings.socialLogin']) }}">{{ localize('Social Media Login') }}</a>
                        </li>
                    @endcan

                    @can('language_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.languages.index') }}"
                                class="{{ areActiveRoutes(['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations']) }}">{{ localize('Multilingual Settings') }}</a>
                        </li>
                    @endcan

                    @can('currency_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.currencies.index') }}"
                                class="{{ areActiveRoutes(['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations']) }}">{{ localize('Multi Currency Settings') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan
</ul>
