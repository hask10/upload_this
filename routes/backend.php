<?php

use App\Http\Controllers\Affiliate\AffiliateOverviewController;
use App\Http\Controllers\Affiliate\AffiliatePaymentsController;
use App\Http\Controllers\Affiliate\AffiliatePayoutConfigurationsController;
use App\Http\Controllers\Affiliate\ConfigurationsController;
use App\Http\Controllers\Affiliate\EarningHistoriesController;
use App\Http\Controllers\Affiliate\WithdrawRequestsController;
use App\Http\Controllers\Backend\AI\AiChatController;
use App\Http\Controllers\Backend\AI\GenerateCodesController;
use App\Http\Controllers\Backend\Appearance\ClientFeedbackController;
use App\Http\Controllers\Backend\Appearance\FooterController;
use App\Http\Controllers\Backend\Appearance\HeaderController;
use App\Http\Controllers\Backend\Appearance\HomepageController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CurrenciesController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\SubscribersController;
use App\Http\Controllers\Backend\CustomersController;
use App\Http\Controllers\Backend\StaffsController;
use App\Http\Controllers\Backend\Pages\PagesController;
use App\Http\Controllers\Backend\BlogSystem\TagsController;
use App\Http\Controllers\Backend\BlogSystem\BlogCategoriesController;
use App\Http\Controllers\Backend\BlogSystem\BlogsController;
use App\Http\Controllers\Backend\MediaManager\MediaManagerController;
use App\Http\Controllers\Backend\Newsletters\NewslettersController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Backend\AI\GenerateContentsController;
use App\Http\Controllers\Backend\AI\GenerateImagesController;
use App\Http\Controllers\Backend\AI\GenerateS2TController;
use App\Http\Controllers\Backend\Contacts\ContactUsMessagesController;
use App\Http\Controllers\Backend\faq\FaqsController;
use App\Http\Controllers\Backend\Folders\FoldersController;
use App\Http\Controllers\Backend\Projects\ProjectsController;
use App\Http\Controllers\Backend\Reports\ReportsController;
use App\Http\Controllers\Backend\Subscription\SubscriptionsController;
use App\Http\Controllers\Backend\Templates\CustomTemplateCategoriesController;
use App\Http\Controllers\Backend\Templates\CustomTemplatesController;
use App\Http\Controllers\Backend\Templates\PromptsConfigurationController;
use App\Http\Controllers\Backend\Templates\TemplatesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/

# common routes
Route::group(['prefix' => 'backend'], function () {
    # change settings
    Route::post('/change-currency', [CurrenciesController::class, 'changeCurrency'])->name('backend.changeCurrency');
    Route::post('/change-language', [LanguageController::class, 'changeLanguage'])->name('backend.changeLanguage');

    # package templates
    Route::get('/get-package-templates', [SubscriptionsController::class, 'getPackageTemplates'])->name('subscriptions.getPackageTemplates');
});

# dashboard routes
Route::group(
    ['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']],
    function () {
        # dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('writebot.dashboard');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
        Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

        # subscriptions routes
        Route::group(['prefix' => 'subscriptions', 'middleware' => ['auth']], function () {
            # subscriptions
            Route::get('/', [SubscriptionsController::class, 'index'])->name('subscriptions.index');
            Route::get('/get-packages', [SubscriptionsController::class, 'indexTypePackages'])->name('subscriptions.indexTypePackages');
            Route::post('/update-packages', [SubscriptionsController::class, 'update'])->name('subscriptions.update');
            Route::get('/get-templates', [SubscriptionsController::class, 'getTemplates'])->name('subscriptions.getTemplates');

            Route::post('/update-package-templates', [SubscriptionsController::class, 'updateTemplates'])->name('subscriptions.updateTemplates');
            Route::get('/copy-package', [SubscriptionsController::class, 'copyPackage'])->name('subscriptions.copyPackage');
            Route::post('/copy-package', [SubscriptionsController::class, 'newPackage'])->name('subscriptions.newPackage');

            # histories 
            Route::get('/histories', [SubscriptionsController::class, 'indexHistory'])->name('subscriptions.histories.index');
        });


        # affiliate routes
        Route::group(['prefix' => 'affiliate', 'middleware' => ['auth', 'affiliate']], function () {
            # configurations 
            Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('affiliate.configurations')->middleware('admin');

            # overview 
            Route::get('/overview', [AffiliateOverviewController::class, 'index'])->name('affiliate.overview')->middleware('customer');
            Route::get('/configure-payouts', [AffiliatePayoutConfigurationsController::class, 'index'])->name('affiliate.payout.configure')->middleware('customer');
            Route::post('/configure-payouts', [AffiliatePayoutConfigurationsController::class, 'store'])->name('affiliate.payout.configureStore')->middleware('customer');

            # withdraw
            Route::get('/withdraw-requests', [WithdrawRequestsController::class, 'index'])->name('affiliate.withdraw.index');
            Route::post('/withdraw-requests', [WithdrawRequestsController::class, 'store'])->name('affiliate.withdraw.store');
            Route::post('/update-requests', [WithdrawRequestsController::class, 'update'])->name('affiliate.withdraw.update');

            # earning histories
            Route::get('/earning-histories', [EarningHistoriesController::class, 'index'])->name('affiliate.earnings.index');

            # payments
            Route::get('/payments', [AffiliatePaymentsController::class, 'index'])->name('affiliate.payments.index');
        });

        # document routes
        Route::group(['prefix' => 'documents', 'middleware' => ['auth']], function () {
            # folders 
            Route::get('/folders', [FoldersController::class, 'index'])->name('folders.index');
            Route::post('/add-folder', [FoldersController::class, 'store'])->name('folders.store');
            Route::get('/folders/{slug}', [FoldersController::class, 'show'])->name('folders.show');
            Route::get('/edit-folder/{slug}', [FoldersController::class, 'edit'])->name('folders.edit');
            Route::post('/update-folder', [FoldersController::class, 'update'])->name('folders.update');
            Route::get('/folders/delete/{id}', [FoldersController::class, 'delete'])->name('folders.delete');

            # projects
            Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
            Route::get('/edit-project/{slug}', [ProjectsController::class, 'edit'])->name('projects.edit');
            Route::post('/update-project', [ProjectsController::class, 'update'])->name('projects.update');
            Route::get('/projects/delete/{id}', [ProjectsController::class, 'delete'])->name('projects.delete');

            # project ajax
            Route::post('/move-to-folder-modal', [ProjectsController::class, 'moveToFolderModalOpen'])->name('projects.moveToFolderModal');
            Route::post('/move-to-folder', [ProjectsController::class, 'moveToFolder'])->name('projects.moveToFolder');
        });

        # templates routes
        Route::group(['prefix' => 'templates', 'middleware' => ['auth']], function () {

            # templates
            Route::get('/', [TemplatesController::class, 'index'])->name('templates.index');
            Route::get('/favorites', [TemplatesController::class, 'indexFavorite'])->name('templates.favorites');
            Route::post('/favorites', [TemplatesController::class, 'updateFavorite'])->name('templates.updateFavorite');
            Route::get('/popular', [TemplatesController::class, 'indexPopular'])->name('templates.popular');

            # prompts
            Route::get('/prompts', [PromptsConfigurationController::class, 'index'])->name('templates.prompts.index');
            Route::post('/prompts', [PromptsConfigurationController::class, 'show'])->name('templates.prompts.show');
            Route::post('/update-prompts', [PromptsConfigurationController::class, 'store'])->name('templates.prompts.store');

            # import templates
            Route::get('/import-templates', [TemplatesController::class, 'store'])->name('templates.store');

            # generate contents
            Route::post('/generate', [GenerateContentsController::class, 'generate'])->name('templates.generate');
            Route::get('/{template_code}', [TemplatesController::class, 'show'])->name('templates.show');
        });

        # custom templates routes
        Route::group(['prefix' => 'custom', 'middleware' => ['auth']], function () {
            # custom template categories
            Route::get('/template-categories', [CustomTemplateCategoriesController::class, 'index'])->name('custom.templateCategories.index');
            Route::post('/template-categories', [CustomTemplateCategoriesController::class, 'store'])->name('custom.templateCategories.store');
            Route::get('/template-categories/edit/{id}', [CustomTemplateCategoriesController::class, 'edit'])->name('custom.templateCategories.edit');
            Route::post('/template-categories/update-tag', [CustomTemplateCategoriesController::class, 'update'])->name('custom.templateCategories.update');
            Route::get('/template-categories/delete/{id}', [CustomTemplateCategoriesController::class, 'delete'])->name('custom.templateCategories.delete');

            # custom templates 
            Route::get('/templates', [CustomTemplatesController::class, 'index'])->name('custom.templates.index');
            Route::get('/create-template', [CustomTemplatesController::class, 'create'])->name('custom.templates.create');
            Route::post('/templates', [CustomTemplatesController::class, 'store'])->name('custom.templates.store');
            Route::get('/templates/edit/{id}', [CustomTemplatesController::class, 'edit'])->name('custom.templates.edit');
            Route::post('/templates/update-tag', [CustomTemplatesController::class, 'update'])->name('custom.templates.update');
            Route::get('/templates/delete/{id}', [CustomTemplatesController::class, 'delete'])->name('custom.templates.delete');

            # generate contents
            Route::post('/generate', [GenerateContentsController::class, 'generateCustom'])->name('custom.templates.generate');
            Route::get('/{template_code}', [TemplatesController::class, 'showCustom'])->name('custom.templates.show');
        });

        # chat
        Route::get('/ai-chat', [AiChatController::class, 'index'])->name('chat.index');
        Route::get('/ai-chat/experts', [AiChatController::class, 'indexExperts'])->name('chat.experts');
        Route::post('/ai-chat', [AiChatController::class, 'store'])->name('chat.store');
        Route::post('/ai-chat/update', [AiChatController::class, 'update'])->name('chat.update');
        Route::get('/ai-chat/delete/{id}', [AiChatController::class, 'delete'])->name('chat.delete');
        Route::post('/ai-chat/new-message', [AIChatController::class, 'newMessage'])->name('chat.newMessage');
        Route::get('/ai-chat/new-message-process', [AIChatController::class, 'process'])->name('chat.process');
        Route::post('/ai-chat/get-conversations', [AiChatController::class, 'getConversations'])->name('chat.getConversations');
        Route::post('/ai-chat/get-messages', [AiChatController::class, 'getMessages'])->name('chat.getMessages');

        # AI images
        Route::get('/generate-images', [GenerateImagesController::class, 'index'])->name('images.index');
        Route::post('/generate-images', [GenerateImagesController::class, 'generate'])->name('images.generate');
        Route::get('/delete-image/{id}', [GenerateImagesController::class, 'delete'])->name('images.delete');

        # AI code
        Route::get('/generate-code', [GenerateCodesController::class, 'index'])->name('codes.index');
        Route::post('/generate-code', [GenerateCodesController::class, 'generate'])->name('codes.generate');

        # s2t
        Route::get('/speech-to-text', [GenerateS2TController::class, 'index'])->name('s2t.index');
        Route::post('/speech-to-text', [GenerateS2TController::class, 'generate'])->name('s2t.generate');

        # contact us message
        Route::group(['prefix' => 'contacts'], function () {
            Route::get('/', [ContactUsMessagesController::class, 'index'])->name('admin.queries.index');
            Route::get('/mark-as-read/{id}', [ContactUsMessagesController::class, 'read'])->name('admin.queries.markRead');
        });

        # openAi settings 
        Route::get('/settings/open-ai', [SettingsController::class, 'openAi'])->name('admin.settings.openAi');

        # auth settings 
        Route::get('/settings/auth', [SettingsController::class, 'authSettings'])->name('admin.settings.authSettings');

        # otp settings 
        Route::get('/settings/otp', [SettingsController::class, 'otpSettings'])->name('admin.settings.otpSettings');

        # settings
        Route::post('/settings/env-key-update', [SettingsController::class, 'envKeyUpdate'])->name('admin.envKey.update');
        Route::get('/settings/general-settings', [SettingsController::class, 'index'])->name('admin.generalSettings');
        Route::get('/settings/smtp-settings', [SettingsController::class, 'smtpSettings'])->name('admin.smtpSettings.index');
        Route::post('/settings/test/smtp', [SettingsController::class, 'testEmail'])->name('admin.test.smtp');
        Route::post('/settings/update', [SettingsController::class, 'update'])->name('admin.settings.update');

        #payment methods 
        Route::get('/settings/payment-methods', [SettingsController::class, 'paymentMethods'])->name('admin.settings.paymentMethods');
        Route::post('/settings/update-payment-methods', [SettingsController::class, 'updatePaymentMethods'])->name('admin.settings.updatePaymentMethods');

        # social login
        Route::get('/settings/social-media-login', [SettingsController::class, 'socialLogin'])->name('admin.settings.socialLogin');
        Route::post('/settings/activation', [SettingsController::class, 'updateActivation'])->name('admin.settings.activation');

        # currencies  
        Route::get('/settings/currencies', [CurrenciesController::class, 'index'])->name('admin.currencies.index');
        Route::post('/settings/store-currency', [CurrenciesController::class, 'store'])->name('admin.currencies.store');
        Route::get('/settings/currencies/edit/{id}', [CurrenciesController::class, 'edit'])->name('admin.currencies.edit');
        Route::post('/settings/update-currency', [CurrenciesController::class, 'update'])->name('admin.currencies.update');
        Route::post('/settings/update-currency-status', [CurrenciesController::class, 'updateStatus'])->name('admin.currencies.updateStatus');

        # languages  
        Route::get('/settings/languages', [LanguageController::class, 'index'])->name('admin.languages.index');
        Route::post('/settings/store-language', [LanguageController::class, 'store'])->name('admin.languages.store');
        Route::get('/settings/languages/edit/{id}', [LanguageController::class, 'edit'])->name('admin.languages.edit');
        Route::post('/settings/update-language', [LanguageController::class, 'update'])->name('admin.languages.update');
        Route::post('/settings/update-language-status', [LanguageController::class, 'updateStatus'])->name('admin.languages.updateStatus');
        Route::post('/settings/update-language-default-status', [LanguageController::class, 'defaultLanguage'])->name('admin.languages.defaultLanguage');

        # localizations
        Route::get('/settings/languages/localizations/{id}', [LanguageController::class, 'showLocalizations'])->name('admin.languages.localizations');
        Route::post('/settings/languages/key-value-store', [LanguageController::class, 'key_value_store'])->name('admin.languages.key_value_store');

        # pages
        Route::group(['prefix' => 'pages'], function () {
            Route::get('/', [PagesController::class, 'index'])->name('admin.pages.index');
            Route::get('/add-page', [PagesController::class, 'create'])->name('admin.pages.create');
            Route::post('/add-page', [PagesController::class, 'store'])->name('admin.pages.store');
            Route::get('/edit/{id}', [PagesController::class, 'edit'])->name('admin.pages.edit');
            Route::post('/update-page', [PagesController::class, 'update'])->name('admin.pages.update');
            Route::get('/delete/{id}', [PagesController::class, 'delete'])->name('admin.pages.delete');
        });

        # faqs
        Route::get('/faqs', [FaqsController::class, 'index'])->name('admin.faqs.index');
        Route::post('/new-faq', [FaqsController::class, 'store'])->name('admin.faqs.store');
        Route::get('/faqs/edit/{id}', [FaqsController::class, 'edit'])->name('admin.faqs.edit');
        Route::post('/faqs/update-faq', [FaqsController::class, 'update'])->name('admin.faqs.update');
        Route::get('/faqs/delete/{id}', [FaqsController::class, 'delete'])->name('admin.faqs.delete');

        # testimonials
        Route::group(['prefix' => 'testimonials'], function () {
            Route::get('/', [ContactUsMessagesController::class, 'index'])->name('admin.testimonials.index');
            Route::get('/add', [ContactUsMessagesController::class, 'index'])->name('admin.testimonials.edit');
        });

        # customers
        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomersController::class, 'index'])->name('admin.customers.index');
            Route::post('/update-banned-customer', [CustomersController::class, 'updateBanStatus'])->name('admin.customers.updateBanStatus');
        });

        # tags
        Route::get('/tags', [TagsController::class, 'index'])->name('admin.tags.index');
        Route::post('/tag', [TagsController::class, 'store'])->name('admin.tags.store');
        Route::get('/tags/edit/{id}', [TagsController::class, 'edit'])->name('admin.tags.edit');
        Route::post('/tags/update-tag', [TagsController::class, 'update'])->name('admin.tags.update');
        Route::get('/tags/delete/{id}', [TagsController::class, 'delete'])->name('admin.tags.delete');

        # blog system
        Route::group(['prefix' => 'blogs'], function () {
            # blogs
            Route::get('/', [BlogsController::class, 'index'])->name('admin.blogs.index');
            Route::get('/add-blog', [BlogsController::class, 'create'])->name('admin.blogs.create');
            Route::post('/add-blog', [BlogsController::class, 'store'])->name('admin.blogs.store');
            Route::get('/edit/{id}', [BlogsController::class, 'edit'])->name('admin.blogs.edit');
            Route::post('/update-blog', [BlogsController::class, 'update'])->name('admin.blogs.update');
            Route::post('/update-popular', [BlogsController::class, 'updatePopular'])->name('admin.blogs.updatePopular');
            Route::post('/update-status', [BlogsController::class, 'updateStatus'])->name('admin.blogs.updateStatus');
            Route::get('/delete/{id}', [BlogsController::class, 'delete'])->name('admin.blogs.delete');

            # categories
            Route::get('/categories', [BlogCategoriesController::class, 'index'])->name('admin.blogCategories.index');
            Route::post('/category', [BlogCategoriesController::class, 'store'])->name('admin.blogCategories.store');
            Route::get('/categories/edit/{id}', [BlogCategoriesController::class, 'edit'])->name('admin.blogCategories.edit');
            Route::post('/categories/update-category', [BlogCategoriesController::class, 'update'])->name('admin.blogCategories.update');
            Route::get('/categories/delete/{id}', [BlogCategoriesController::class, 'delete'])->name('admin.blogCategories.delete');
        });

        # media manager 
        Route::get('/media-manager', [MediaManagerController::class, 'index'])->name('admin.mediaManager.index');

        # bulk-emails
        Route::controller(NewslettersController::class)->group(function () {
            Route::get('/bulk-emails', 'index')->name('admin.newsletters.index');
            Route::post('/bulk-emails/send', 'sendNewsletter')->name('admin.newsletters.send');
        });

        # subscribed users
        Route::get('/subscribers', [SubscribersController::class, 'index'])->name('admin.subscribers.index');
        Route::get('/subscribers/delete/{id}', [SubscribersController::class, 'delete'])->name('admin.subscribers.delete');

        # roles & permissions
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RolesController::class, 'index'])->name('admin.roles.index');
            Route::get('/add-role', [RolesController::class, 'create'])->name('admin.roles.create');
            Route::post('/add-role', [RolesController::class, 'store'])->name('admin.roles.store');
            Route::get('/update-role/{id}', [RolesController::class, 'edit'])->name('admin.roles.edit');
            Route::post('/update-role', [RolesController::class, 'update'])->name('admin.roles.update');
            Route::get('/delete-role/{id}', [RolesController::class, 'delete'])->name('admin.roles.delete');
        });

        # appearance
        Route::group(['prefix' => 'appearance'], function () {
            # homepage - hero
            Route::get('/homepage/hero', [HomepageController::class, 'hero'])->name('admin.appearance.homepage.hero');

            # homepage - trustedBy
            Route::get('/homepage/trusted-by', [HomepageController::class, 'trustedBy'])->name('admin.appearance.homepage.trustedBy');

            # homepage - howItWorks
            Route::get('/homepage/how-it-works', [HomepageController::class, 'howItWorks'])->name('admin.appearance.homepage.howItWorks');

            # homepage - featureImages
            Route::get('/homepage/feature-images', [HomepageController::class, 'featureImages'])->name('admin.appearance.homepage.featureImages');

            # homepage - cta
            Route::get('/homepage/cta', [HomepageController::class, 'cta'])->name('admin.appearance.homepage.cta');

            # client feedback
            Route::get('/homepage/client-feedback', [ClientFeedbackController::class, 'index'])->name('admin.appearance.homepage.clientFeedback');
            Route::post('/homepage/client-feedback', [ClientFeedbackController::class, 'store'])->name('admin.appearance.homepage.storeClientFeedback');
            Route::get('/homepage/client-feedback/edit/{id}', [ClientFeedbackController::class, 'edit'])->name('admin.appearance.homepage.editClientFeedback');
            Route::post('/homepage/client-feedback/update', [ClientFeedbackController::class, 'update'])->name('admin.appearance.homepage.updateClientFeedback');
            Route::get('/homepage/client-feedback/delete/{id}', [ClientFeedbackController::class, 'delete'])->name('admin.appearance.homepage.deleteClientFeedback');

            # header
            Route::get('/header', [HeaderController::class, 'index'])->name('admin.appearance.header');

            # footer
            Route::get('/footer', [FooterController::class, 'index'])->name('admin.appearance.footer');
        });

        # staffs
        Route::group(['prefix' => 'staffs'], function () {
            Route::get('/', [StaffsController::class, 'index'])->name('admin.staffs.index');
            Route::get('/add-staff', [StaffsController::class, 'create'])->name('admin.staffs.create');
            Route::post('/add-staff', [StaffsController::class, 'store'])->name('admin.staffs.store');
            Route::get('/update-staff/{id}', [StaffsController::class, 'edit'])->name('admin.staffs.edit');
            Route::post('/update-staff', [StaffsController::class, 'update'])->name('admin.staffs.update');
            Route::get('/delete-staff/{id}', [StaffsController::class, 'delete'])->name('admin.staffs.delete');
        });


        # reports
        Route::group(['prefix' => 'reports'], function () {
            Route::get('/words-generated', [ReportsController::class, 'words'])->name('admin.reports.words');
            Route::get('/codes-generated', [ReportsController::class, 'codes'])->name('admin.reports.codes');
            Route::get('/images-generated', [ReportsController::class, 'images'])->name('admin.reports.images');
            Route::get('/speech-to-text-generated', [ReportsController::class, 's2t'])->name('admin.reports.s2t');
            Route::get('/most-used-templates', [ReportsController::class, 'mostUsed'])->name('admin.reports.mostUsed');
            Route::get('/subscriptions', [ReportsController::class, 'subscriptions'])->name('admin.reports.subscriptions');
        });
    }
);
