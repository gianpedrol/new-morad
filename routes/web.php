<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\WEB\Admin\DashboardController;
use App\Http\Controllers\WEB\Admin\Auth\AdminLoginController;
use App\Http\Controllers\WEB\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\WEB\Admin\AdminProfileController;
use App\Http\Controllers\WEB\Admin\SubscriberController;
use App\Http\Controllers\WEB\Admin\EmailConfigurationController;
use App\Http\Controllers\WEB\Admin\EmailTemplateController;
use App\Http\Controllers\WEB\Admin\AdminController;
use App\Http\Controllers\WEB\Admin\ContactMessageController;
use App\Http\Controllers\WEB\Admin\SettingController;
use App\Http\Controllers\WEB\Admin\BlogCategoryController;
use App\Http\Controllers\WEB\Admin\BlogController;
use App\Http\Controllers\WEB\Admin\PopularBlogController;
use App\Http\Controllers\WEB\Admin\BlogCommentController;
use App\Http\Controllers\WEB\Admin\ContentController;
use App\Http\Controllers\WEB\Admin\SliderController;
use App\Http\Controllers\WEB\Admin\ServiceController;
use App\Http\Controllers\WEB\Admin\PartnerController;
use App\Http\Controllers\WEB\Admin\TestimonialController;
use App\Http\Controllers\WEB\Admin\OverviewController;
use App\Http\Controllers\WEB\Admin\FooterController;
use App\Http\Controllers\WEB\Admin\FooterSocialLinkController;
use App\Http\Controllers\WEB\Admin\AboutUsController;
use App\Http\Controllers\WEB\Admin\ContactPageController;
use App\Http\Controllers\WEB\Admin\CustomPageController;
use App\Http\Controllers\WEB\Admin\TermsAndConditionController;
use App\Http\Controllers\WEB\Admin\PrivacyPolicyController;
use App\Http\Controllers\WEB\Admin\FaqController;
use App\Http\Controllers\WEB\Admin\ErrorPageController;
use App\Http\Controllers\WEB\Admin\PropertyTypeController;
use App\Http\Controllers\WEB\Admin\PropertyPurposeController;
use App\Http\Controllers\WEB\Admin\NearestLocationController;
use App\Http\Controllers\WEB\Admin\AminityController;
use App\Http\Controllers\WEB\Admin\PackageController;
use App\Http\Controllers\WEB\Admin\CityController;
use App\Http\Controllers\WEB\Admin\AdminPropertyController;
use App\Http\Controllers\WEB\Admin\OrderController;
use App\Http\Controllers\WEB\Admin\PaymentMethodController;
use App\Http\Controllers\WEB\Admin\CareerController;
use App\Http\Controllers\WEB\Admin\HomePageController;
use App\Http\Controllers\WEB\Admin\CustomerController;
use App\Http\Controllers\WEB\Admin\BreadcrumbController;
use App\Http\Controllers\WEB\Admin\StaffController;
use App\Http\Controllers\WEB\Admin\MenuVisibilityController;
use App\Http\Controllers\WEB\Admin\CountryController;
use App\Http\Controllers\WEB\Admin\CountryStateController;
use App\Http\Controllers\WEB\Admin\FooterLinkController;
use App\Http\Controllers\WEB\Admin\LanguageController;



// staff panel

use App\Http\Controllers\WEB\Staff\Auth\StaffLoginController;
use App\Http\Controllers\WEB\Staff\Auth\StaffForgotPasswordController;
use App\Http\Controllers\WEB\Staff\StaffDashboardController;
use App\Http\Controllers\WEB\Staff\StaffPropertyController;
use App\Http\Controllers\WEB\Staff\StaffProfileController;



// public controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WEB\User\PaymentController;
use App\Http\Controllers\WEB\User\PaypalController;
use App\Http\Controllers\WEB\User\UserDashboardController;
use App\Http\Controllers\WEB\User\UserHomeController;
use App\Http\Controllers\WEB\User\WishlistController;
use App\Http\Controllers\WEB\User\UserOrderController;
use App\Http\Controllers\WEB\User\PropertyController;
use App\Http\Controllers\WEB\Auth\LoginController;
use App\Http\Controllers\WEB\Auth\RegisterController;
use App\Http\Controllers\WEB\Auth\ForgotPasswordController;

use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\AddressCotroller;
use App\Http\Controllers\WEB\Seller\Auth\SellerLoginController;
use App\Http\Controllers\WEB\Seller\Auth\SellerForgotPasswordController;

Route::group([
    'prefix' => 'auth',
    'middleware' => 'translation'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => ['demo', 'XSS', 'translation']], function () {

    Route::group(['middleware' => ['maintainance']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about.us');
        Route::get('/career', [HomeController::class, 'career'])->name('career');
        Route::get('/show-career/{slug}', [HomeController::class, 'show_career'])->name('show-career');
        Route::post('/store-career-application', [HomeController::class, 'store_career_application'])->name('store-career-application');

        Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
        Route::get('/blog-details/{slug}', [HomeController::class, 'blogDetails'])->name('blog.details');
        Route::get('/blog-category/{slug}', [HomeController::class, 'blogCategory'])->name('blog.category');
        Route::get('/blog-search', [HomeController::class, 'blogSearch'])->name('blog.search');
        Route::post('/blog-comment/{id}', [HomeController::class, 'blogComment'])->name('blog.comment');
        Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
        Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact.us');
        Route::post('contact-message', [ContactController::class, 'sendMessage'])->name('contact.message');
        Route::get('terms-and-conditions', [HomeController::class, 'termsCondition'])->name('terms-and-conditions');
        Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::get('subscribe-us', [HomeController::class, 'subscribeUs'])->name('subscribe-us');
        Route::get('subscription-verify/{token}', [HomeController::class, 'subscriptionVerify'])->name('subscription.verify');
        Route::get('page/{slug}', [HomeController::class, 'customPage'])->name('custom.page');
        Route::get('agents', [HomeController::class, 'agent'])->name('agents');
        Route::get('agent', [HomeController::class, 'agentDetails'])->name('agent.show');

        Route::get('/pricing-plan', [HomeController::class, 'pricingPlan'])->name('pricing.plan');
        Route::get('/properties', [HomeController::class, 'properties'])->name('properties');
        Route::get('/property/{slug}', [HomeController::class, 'propertDetails'])->name('property.details');
        Route::get('search-property', [HomeController::class, 'searchPropertyPage'])->name('search-property');

        Route::post('user-contact-message', [ContactController::class, 'messageForUser'])->name('user.contact.message');

        Route::get('/download-listing-file/{file}', [HomeController::class, 'downloadListingFile'])->name('download-listing-file');
        Route::get('set-language', [HomeController::class, 'setLanguage'])->name('set-language');

        //user profile section
        Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
            Route::get('dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('my-properties', [PropertyController::class, 'index'])->name('my.properties');
            Route::get('create-property', [PropertyController::class, 'create'])->name('create.property');
            Route::post('store-property', [PropertyController::class, 'store'])->name('property.store');
            Route::get('edit-property/{id}', [PropertyController::class, 'edit'])->name('property.edit');
            Route::get('property-slider-img/{id}', [PropertyController::class, 'propertySliderImage'])->name('property-slider-img');
            Route::get('property-delete-pdf/{id}', [PropertyController::class, 'deletePdfFile'])->name('property-delete-pdf');
            Route::get('exist-nearest-location/{id}', [PropertyController::class, 'existNearestLocation'])->name('exist-nearest-location');
            Route::post('update-property/{id}', [PropertyController::class, 'update'])->name('property.update');
            Route::get('delete-property/{id}', [PropertyController::class, 'destroy'])->name('property.delete');
            Route::get('exist-property-slider-img/{id}', [PropertyController::class, 'existSliderImage'])->name('exist-property-slider-img');
            Route::get('find-exist-nearest-location/{id}', [PropertyController::class, 'findExistNearestLocation'])->name('find-exist-nearest-location');

            Route::get('/pricing-plan', [UserHomeController::class, 'pricingPlan'])->name('pricing.plan');

            Route::get('my-review', [UserHomeController::class, 'myReview'])->name('my-review');
            Route::get('delete-review', [UserHomeController::class, 'deleteReview'])->name('delete-review');

            Route::get('client-review', [UserHomeController::class, 'clientReview'])->name('client-review');
            Route::post('store-review', [UserHomeController::class, 'storeReview'])->name('store-review');
            Route::get('edit-review/{id}', [UserHomeController::class, 'editReview'])->name('edit-review');
            Route::post('update-review/{id}', [UserHomeController::class, 'updateReview'])->name('update-review');
            Route::get('delete-review/{id}', [UserHomeController::class, 'deleteReview'])->name('delete-review');
            Route::get('my-profile', [UserHomeController::class, 'profile'])->name('my-profile');
            Route::post('update-profile', [UserHomeController::class, 'updateProfile'])->name('update.profile');
            Route::post('update-password', [UserHomeController::class, 'updatePassword'])->name('update.password');
            Route::post('update-profile-banner', [UserHomeController::class, 'updateProfileBanner'])->name('update.profile.banner');

            Route::get('my-wishlist', [WishlistController::class, 'wishlist'])->name('my-wishlist');
            Route::get('add-to-wishlist/{id}', [WishlistController::class, 'create'])->name('add.to.wishlist');
            Route::get('delete-wishlist/{id}', [WishlistController::class, 'delete'])->name('delete.wishlist');

            Route::get('renew-package/{id}', [PaymentController::class, 'renew_package'])->name('purchase.renew');


            Route::get('purchase-package/{id}', [PaymentController::class, 'purchase'])->name('purchase.package');

            Route::post('bank-payment', [PaymentController::class, 'bankPayment'])->name('bank-payment');

            Route::get('paypal/{id}', [PaypalController::class, 'store'])->name('paypal');
            Route::get('paypal-payment-success', [PaypalController::class, 'paymentSuccess']);
            Route::get('paypal-payment-cancled', [PaypalController::class, 'paymentCancled']);
            Route::post('stripe-payment/{id}', [PaymentController::class, 'stripePayment'])->name('stripe.payment');
            Route::get('my-order', [UserOrderController::class, 'index'])->name('my-order');
            Route::get('order-details/{id}', [UserOrderController::class, 'show'])->name('order.details');
            Route::get('package', [UserHomeController::class, 'ListingPackage'])->name('package');
            Route::post('razorpay-payment/{id}', [PaymentController::class, 'razorPay'])->name('razorpay-payment');
            Route::post('flutterwave-payment', [PaymentController::class, 'flutterWavePayment'])->name('flutterwave-payment');
            Route::post('paystack-payment', [PaymentController::class, 'paystackPayment'])->name('paystack-payment');
            Route::get('mollie-payment/{id}', [PaymentController::class, 'molliePayment'])->name('mollie-payment');
            Route::get('/mollie-payment-success', [PaymentController::class, 'molliePaymentSuccess'])->name('mollie-payment-success');
            Route::get('/pay-with-instamojo/{id}', [PaymentController::class, 'payWithInstamojo'])->name('pay-with-instamojo');
            Route::get('/instamojo-response', [PaymentController::class, 'instamojoResponse'])->name('instamojo-response');

            Route::post('/pay-with-mercadopago', [PaymentController::class,   'payWithMercadoPago'])->name('pay-with-mercadopago');


            Route::group(['as' => 'renew.', 'prefix' => 'renew'], function () {
                Route::post('bank-payment', [PaymentController::class, 'renewBankPayment'])->name('bank-payment');

                Route::get('paypal/{id}', [PaypalController::class, 'renew_store'])->name('paypal');
                Route::get('paypal-payment-success', [PaypalController::class, 'renew_paymentSuccess']);
                Route::get('paypal-payment-cancled', [PaypalController::class, 'paymentCancled']);
                Route::post('stripe-payment/{id}', [PaymentController::class, 'renewStripePayment'])->name('stripe.payment');
                Route::get('my-order', [UserOrderController::class, 'index'])->name('my-order');
                Route::get('order-details/{id}', [UserOrderController::class, 'show'])->name('order.details');
                Route::get('package', [UserHomeController::class, 'ListingPackage'])->name('package');
                Route::post('razorpay-payment/{id}', [PaymentController::class, 'renewRazorPay'])->name('razorpay-payment');
                Route::post('flutterwave-payment', [PaymentController::class, 'renewFlutterWavePayment'])->name('flutterwave-payment');
                Route::post('paystack-payment', [PaymentController::class, 'renewPaystackPayment'])->name('paystack-payment');
                Route::get('mollie-payment/{id}', [PaymentController::class, 'renewMolliePayment'])->name('mollie-payment');
                Route::get('/mollie-payment-success', [PaymentController::class, 'renewMolliePaymentSuccess'])->name('mollie-payment-success');
                Route::get('/pay-with-instamojo/{id}', [PaymentController::class, 'renewPayWithInstamojo'])->name('pay-with-instamojo');
                Route::get('/instamojo-response', [PaymentController::class, 'renewInstamojoResponse'])->name('instamojo-response');

                Route::post('/pay-with-mercadopago', [PaymentController::class,   'payWithMercadoPago'])->name('pay-with-mercadopago');
            });
        });



        // user custom auth route
        Route::get('register', [RegisterController::class, 'userRegisterPage'])->name('register');
        Route::post('register', [RegisterController::class, 'storeRegister'])->name('register');
        Route::get('user-verify/{token}', [RegisterController::class, 'userVerify'])->name('user.verify');
        Route::get('login', [LoginController::class, 'userLoginPage'])->name('login');
        Route::post('login', [LoginController::class, 'storeLogin'])->name('login');
        Route::get('logout', [LoginController::class, 'userLogout'])->name('logout');
        Route::get('forget-password', [ForgotPasswordController::class, 'forgetPassForm'])->name('forget.password');
        Route::post('send-forget-password', [ForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('store-reset-password/{token}', [ForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');

        Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login-google');
        Route::get('/callback/google', [LoginController::class, 'googleCallBack'])->name('callback-google');

        Route::get('login/facebook', [LoginController::class, 'redirectToFacebook'])->name('login-facebook');
        Route::get('/callback/facebook', [LoginController::class, 'facebookCallBack'])->name('callback-facebook');
    });





    //user profile section
    Route::group(['as' => 'staff.', 'prefix' => 'staff'], function () {
        // login route

        Route::get('/', [StaffLoginController::class, 'staffLoginForm'])->name('login');
        Route::get('login', [StaffLoginController::class, 'staffLoginForm'])->name('login');
        Route::post('login', [StaffLoginController::class, 'storeLoginInfo'])->name('login');

        Route::get('/logout', [StaffLoginController::class, 'staffLogout'])->name('logout');

        Route::get('forget-password', [StaffForgotPasswordController::class, 'forgetPassword'])->name('forget-password');
        Route::post('send-forget-password', [StaffForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [StaffForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('store-reset-password/{token}', [StaffForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');

        // // manage admin profile
        Route::get('profile', [StaffProfileController::class, 'profile'])->name('profile');
        Route::put('update-profile', [StaffProfileController::class, 'updateProfile'])->name('update.profile');


        Route::get('dashboard', [StaffDashboardController::class, 'dashboard'])->name('dashboard');

        Route::resource('property', StaffPropertyController::class);
        Route::put('property-status/{id}', [StaffPropertyController::class, 'changeStatus'])->name('property.status');
        Route::get('property-slider-img/{id}', [StaffPropertyController::class, 'propertySliderImage'])->name('property-slider-img');
        Route::get('property-delete-pdf/{id}', [StaffPropertyController::class, 'deletePdfFile'])->name('property-delete-pdf');
        Route::get('exist-nearest-location/{id}', [StaffPropertyController::class, 'existNearestLocation'])->name('exist-nearest-location');
    });


    // start admin routes
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

        // start auth route
        Route::get('login', [AdminLoginController::class, 'adminLoginPage'])->name('login');
        Route::post('login', [AdminLoginController::class, 'storeLogin'])->name('login');
        Route::post('logout', [AdminLoginController::class, 'adminLogout'])->name('logout');
        Route::get('forget-password', [AdminForgotPasswordController::class, 'forgetPassword'])->name('forget-password');
        Route::post('send-forget-password', [AdminForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('password-store/{token}', [AdminForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');
        // end auth route

        Route::get('/', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('profile-update', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber');
        Route::delete('delete-subscriber/{id}', [SubscriberController::class, 'destroy'])->name('delete-subscriber');
        Route::post('specification-subscriber-email/{id}', [SubscriberController::class, 'specificationSubscriberEmail'])->name('specification-subscriber-email');
        Route::post('each-subscriber-email', [SubscriberController::class, 'eachSubscriberEmail'])->name('each-subscriber-email');

        Route::get('email-configuration', [EmailConfigurationController::class, 'index'])->name('email-configuration');
        Route::put('update-email-configuraion', [EmailConfigurationController::class, 'update'])->name('update-email-configuraion');

        Route::get('email-template', [EmailTemplateController::class, 'index'])->name('email-template');
        Route::get('edit-email-template/{id}', [EmailTemplateController::class, 'edit'])->name('edit-email-template');
        Route::put('update-email-template/{id}', [EmailTemplateController::class, 'update'])->name('update-email-template');

        Route::resource('admin', AdminController::class);
        Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin-status');

        Route::get('contact-message', [ContactMessageController::class, 'index'])->name('contact-message');
        Route::get('show-contact-message/{id}', [ContactMessageController::class, 'show'])->name('show-contact-message');
        Route::delete('delete-contact-message/{id}', [ContactMessageController::class, 'destroy'])->name('delete-contact-message');
        Route::put('enable-save-contact-message', [ContactMessageController::class, 'handleSaveContactMessage'])->name('enable-save-contact-message');


        Route::get('clear-database', [SettingController::class, 'showClearDatabasePage'])->name('clear-database');
        Route::delete('clear-database', [SettingController::class, 'clearDatabase'])->name('clear-database');

        Route::get('general-setting', [SettingController::class, 'index'])->name('general-setting');
        Route::put('update-general-setting', [SettingController::class, 'updateGeneralSetting'])->name('update-general-setting');

        Route::put('update-theme-color', [SettingController::class, 'updateThemeColor'])->name('update-theme-color');

        Route::put('update-logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');
        Route::put('update-cookie-consent', [SettingController::class, 'updateCookieConset'])->name('update-cookie-consent');
        Route::put('update-google-recaptcha', [SettingController::class, 'updateGoogleRecaptcha'])->name('update-google-recaptcha');
        Route::put('update-facebook-comment', [SettingController::class, 'updateFacebookComment'])->name('update-facebook-comment');
        Route::put('update-tawk-chat', [SettingController::class, 'updateTawkChat'])->name('update-tawk-chat');
        Route::put('update-google-analytic', [SettingController::class, 'updateGoogleAnalytic'])->name('update-google-analytic');
        Route::put('update-custom-pagination', [SettingController::class, 'updateCustomPagination'])->name('update-custom-pagination');
        Route::put('update-social-login', [SettingController::class, 'updateSocialLogin'])->name('update-social-login');
        Route::put('update-facebook-pixel', [SettingController::class, 'updateFacebookPixel'])->name('update-facebook-pixel');
        Route::put('update-pusher', [SettingController::class, 'updatePusher'])->name('update-pusher');

        Route::resource('blog-category', BlogCategoryController::class);
        Route::put('blog-category-status/{id}', [BlogCategoryController::class, 'changeStatus'])->name('blog.category.status');
        Route::get('/blog-category/translation/{id}/{code}', [BlogCategoryController::class, 'editTranslation'])->name('blog-category.translation');
        Route::post('/blog-category/translation/{id}/{code}', [BlogCategoryController::class, 'updateTranslation'])->name('blog-category.translation.update');

        Route::resource('blog', BlogController::class);
        Route::get('/blog/translation/{id}/{code}', [BlogController::class, 'editTranslation'])->name('blog.translation');
        Route::post('/blog/translation/{id}/{code}', [BlogController::class, 'updateTranslation'])->name('blog.translation.update');
        Route::put('blog-status/{id}', [BlogController::class, 'changeStatus'])->name('blog.status');

        Route::resource('popular-blog', PopularBlogController::class);
        Route::put('popular-blog-status/{id}', [PopularBlogController::class, 'changeStatus'])->name('popular-blog.status');

        Route::resource('blog-comment', BlogCommentController::class);
        Route::put('blog-comment-status/{id}', [BlogCommentController::class, 'changeStatus'])->name('blog-comment.status');

        Route::get('seo-setup', [ContentController::class, 'seoSetup'])->name('seo-setup');
        Route::put('update-seo-setup/{id}', [ContentController::class, 'updateSeoSetup'])->name('update-seo-setup');
        Route::get('get-seo-setup/{id}', [ContentController::class, 'getSeoSetup'])->name('get-seo-setup');

        Route::resource('slider', SliderController::class);
        Route::put('slider-status/{id}', [SliderController::class, 'changeStatus'])->name('slider-status');
        Route::get('/slider/translation/{id}/{code}', [SliderController::class, 'editTranslation'])->name('slider.translation');
        Route::post('/slider/translation/{id}/{code}', [SliderController::class, 'updateTranslation'])->name('slider.translation.update');

        Route::resource('service', ServiceController::class);
        Route::put('service-status/{id}', [ServiceController::class, 'changeStatus'])->name('service.status');
        Route::get('/service/translation/{id}/{code}', [ServiceController::class, 'editTranslation'])->name('service.translation');
        Route::post('/service/translation/{id}/{code}', [ServiceController::class, 'updateTranslation'])->name('service.translation.update');

        Route::resource('our-team', PartnerController::class);
        Route::get('/our-team/translation/{id}/{code}', [PartnerController::class, 'editTranslation'])->name('our-team.translation');
        Route::post('/our-team/translation/{id}/{code}', [PartnerController::class, 'updateTranslation'])->name('our-team.translation.update');
        Route::put('our-team-status/{id}', [PartnerController::class, 'changeStatus'])->name('our-team.status');

        Route::resource('testimonial', TestimonialController::class);
        Route::put('testimonial-status/{id}', [TestimonialController::class, 'changeStatus'])->name('testimonial.status');
        Route::get('/testimonial/translation/{id}/{code}', [TestimonialController::class, 'editTranslation'])->name('testimonial.translation');
        Route::post('/testimonial/translation/{id}/{code}', [TestimonialController::class, 'updateTranslation'])->name('testimonial.translation.update');

        Route::resource('counter', OverviewController::class);
        Route::put('counter-status/{id}', [OverviewController::class, 'changeStatus'])->name('counter.status');
        Route::get('/counter/translation/{id}/{code}', [OverviewController::class, 'editTranslation'])->name('counter.translation');
        Route::post('/counter/translation/{id}/{code}', [OverviewController::class, 'updateTranslation'])->name('counter.translation.update');

        Route::get('topbar-contact', [ContentController::class, 'headerPhoneNumber'])->name('topbar-contact');
        Route::put('update-topbar-contact', [ContentController::class, 'updateHeaderPhoneNumber'])->name('update-topbar-contact');

        Route::resource('footer', FooterController::class);
        Route::get('/footer/translation/{id}/{code}', [FooterController::class, 'editTranslation'])->name('footer.translation');
        Route::post('/footer/translation/{id}/{code}', [FooterController::class, 'updateTranslation'])->name('footer.translation.update');

        Route::resource('social-link', FooterSocialLinkController::class);

        Route::resource('about-us', AboutUsController::class);
        Route::get('/about-us/translation/{id}/{code}', [AboutUsController::class, 'editTranslation'])->name('about-us.translation');
        Route::post('/about-us/translation/{id}/{code}', [AboutUsController::class, 'updateTranslation'])->name('about-us.translation.update');

        Route::resource('contact-us', ContactPageController::class);

        Route::resource('custom-page', CustomPageController::class);
        Route::put('custom-page-status/{id}', [CustomPageController::class, 'changeStatus'])->name('custom-page.status');
        Route::get('/custom-page/translation/{id}/{code}', [CustomPageController::class, 'editTranslation'])->name('custom-page.translation');
        Route::post('/custom-page/translation/{id}/{code}', [CustomPageController::class, 'updateTranslation'])->name('custom-page.translation.update');

        Route::resource('terms-and-condition', TermsAndConditionController::class);
        Route::get('/terms-and-condition/translation/{id}/{code}', [TermsAndConditionController::class, 'editTranslation'])->name('terms-and-condition.translation');
        Route::post('/terms-and-condition/translation/{id}/{code}', [TermsAndConditionController::class, 'updateTranslation'])->name('terms-and-condition.translation.update');

        Route::resource('privacy-policy', PrivacyPolicyController::class);
        Route::get('/privacy-policy/translation/{id}/{code}', [PrivacyPolicyController::class, 'editTranslation'])->name('privacy-policy.translation');
        Route::post('/privacy-policy/translation/{id}/{code}', [PrivacyPolicyController::class, 'updateTranslation'])->name('privacy-policy.translation.update');

        Route::resource('faq', FaqController::class);
        Route::get('/faq/translation/{id}/{code}', [FaqController::class, 'editTranslation'])->name('faq.translation');
        Route::post('/faq/translation/{id}/{code}', [FaqController::class, 'updateTranslation'])->name('faq.translation.update');
        Route::put('faq-status/{id}', [FaqController::class, 'changeStatus'])->name('faq-status');

        Route::resource('property-type', PropertyTypeController::class);
        Route::put('property-type-status/{id}', [PropertyTypeController::class, 'changeStatus'])->name('property-type-status');
        Route::get('/property-type/translation/{id}/{code}', [PropertyTypeController::class, 'editTranslation'])->name('property-type.translation');
        Route::post('/property-type/translation/{id}/{code}', [PropertyTypeController::class, 'updateTranslation'])->name('property-type.translation.update');

        Route::resource('property-purpose', PropertyPurposeController::class);
        Route::put('property-purpose-status/{id}', [PropertyPurposeController::class, 'changeStatus'])->name('property-purpose-status');
        Route::get('/property-purpose/translation/{id}/{code}', [PropertyPurposeController::class, 'editTranslation'])->name('property-purpose.translation');
        Route::post('/property-purpose/translation/{id}/{code}', [PropertyPurposeController::class, 'updateTranslation'])->name('property-purpose.translation.update');

        Route::resource('nearest-location', NearestLocationController::class);
        Route::put('nearest-location-status/{id}', [NearestLocationController::class, 'changeStatus'])->name('nearest-location-status');
        Route::get('/nearest-location/translation/{id}/{code}', [NearestLocationController::class, 'editTranslation'])->name('nearest-location.translation');
        Route::post('/nearest-location/translation/{id}/{code}', [NearestLocationController::class, 'updateTranslation'])->name('nearest-location.translation.update');

        Route::resource('aminity', AminityController::class);
        Route::put('aminity-status/{id}', [AminityController::class, 'changeStatus'])->name('aminity-status');
        Route::get('/aminity/translation/{id}/{code}', [AminityController::class, 'editTranslation'])->name('aminity.translation');
        Route::post('/aminity/translation/{id}/{code}', [AminityController::class, 'updateTranslation'])->name('aminity.translation.update');

        Route::resource('city', CityController::class);
        Route::put('city-status/{id}', [CityController::class, 'changeStatus'])->name('city-status');
        Route::get('/city/translation/{id}/{code}', [CityController::class, 'editTranslation'])->name('city.translation');
        Route::post('/city/translation/{id}/{code}', [CityController::class, 'updateTranslation'])->name('city.translation.update');

        Route::get('city-import-page', [CityController::class, 'city_import_view'])->name('city-import-page');
        Route::get('city-export', [CityController::class, 'export'])->name('city-export');
        Route::post('city-import', [CityController::class, 'import'])->name('city-import');

        Route::resource('package', PackageController::class);
        Route::put('package-status/{id}', [PackageController::class, 'changeStatus'])->name('package-status');
        Route::get('/package/translation/{id}/{code}', [PackageController::class, 'editTranslation'])->name('package.translation');
        Route::post('/package/translation/{id}/{code}', [PackageController::class, 'updateTranslation'])->name('package.translation.update');

        Route::resource('property', AdminPropertyController::class);
        Route::get('/property/translation/{id}/{code}/{type?}', [AdminPropertyController::class, 'editTranslation'])->name('property.translation');
        Route::post('/property/translation/{id}/{code}/{type?}', [AdminPropertyController::class, 'updateTranslation'])->name('property.translation.update');
        Route::get('agent-property', [AdminPropertyController::class, 'agentProperty'])->name('agent-property');
        Route::put('property-status/{id}', [AdminPropertyController::class, 'changeStatus'])->name('property-status');

        Route::get('exist-nearest-location/{id}', [AdminPropertyController::class, 'existNearestLocation'])->name('exist-nearest-location');
        Route::get('property-slider-img/{id}', [AdminPropertyController::class, 'propertySliderImage'])->name('property-slider-img');
        Route::get('property-delete-pdf/{id}', [AdminPropertyController::class, 'deletePdfFile'])->name('property-delete-pdf');
        Route::get('property-review', [AdminPropertyController::class, 'propertyReview'])->name('property-review');
        Route::put('property-review-status/{id}', [AdminPropertyController::class, 'reviewChangeStatus'])->name('property-review-status');
        Route::delete('property-review-delete/{id}', [AdminPropertyController::class, 'reviewDelete'])->name('property-review-delete');

        Route::get('all-order', [OrderController::class, 'index'])->name('all-order');
        Route::get('pending-order', [OrderController::class, 'pendingOrder'])->name('pending-order');
        Route::get('pending-renew-order', [OrderController::class, 'pendingRenewOrder'])->name('pending-renew-order');
        Route::get('renew-order-show/{id}', [OrderController::class, 'renew_show'])->name('renew-order-show');
        Route::get('order-show/{id}', [OrderController::class, 'show'])->name('order-show');
        Route::delete('delete-order/{id}', [OrderController::class, 'destroy'])->name('delete-order');
        Route::get('approved-payment/{id}', [OrderController::class, 'approve_payment'])->name('approved-payment');
        Route::get('approved-renew-payment/{id}', [OrderController::class, 'approve_renew_payment'])->name('approved-renew-payment');

        Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method');
        Route::put('update-paypal', [PaymentMethodController::class, 'updatePaypal'])->name('update-paypal');
        Route::put('update-stripe', [PaymentMethodController::class, 'updateStripe'])->name('update-stripe');
        Route::put('update-razorpay', [PaymentMethodController::class, 'updateRazorpay'])->name('update-razorpay');
        Route::put('update-bank', [PaymentMethodController::class, 'updateBank'])->name('update-bank');
        Route::put('update-mollie', [PaymentMethodController::class, 'updateMollie'])->name('update-mollie');
        Route::put('update-paystack', [PaymentMethodController::class, 'updatePayStack'])->name('update-paystack');
        Route::put('update-flutterwave', [PaymentMethodController::class, 'updateflutterwave'])->name('update-flutterwave');
        Route::put('update-instamojo', [PaymentMethodController::class, 'updateInstamojo'])->name('update-instamojo');
        Route::put('update-cash-on-delivery', [PaymentMethodController::class, 'updateCashOnDelivery'])->name('update-cash-on-delivery');
        Route::put('update-mercadopago', [PaymentMethodController::class, 'updateMercadoPago'])->name('update-mercadopago');

        Route::resource('career', CareerController::class);
        Route::put('career-status/{id}', [CareerController::class, 'changeStatus'])->name('career-status');
        Route::get('career-request/{id}', [CareerController::class, 'careerRequest'])->name('career-request');

        Route::get('homepage', [HomePageController::class, 'homepage'])->name('homepage');
        Route::put('update-homepage', [HomePageController::class, 'updateHomepage'])->name('update-homepage');
        Route::get('/homepage/translation/{id}/{code}', [HomePageController::class, 'editTranslation'])->name('homepage.translation');
        Route::post('/homepage/translation/{id}/{code}', [HomePageController::class, 'updateTranslation'])->name('homepage.translation.update');

        Route::resource('banner-image', BreadcrumbController::class);

        Route::get('create-user', [CustomerController::class, 'create_user'])->name('create-user');
        Route::post('store-user', [CustomerController::class, 'store_user'])->name('store-user');

        Route::get('assign-package', [CustomerController::class, 'assign_package'])->name('assign-package');
        Route::post('store-assign-package', [CustomerController::class, 'store_assign_package'])->name('store-assign-package');



        Route::get('our-agent', [CustomerController::class, 'index'])->name('our-agent');
        Route::get('agent-show/{id}', [CustomerController::class, 'show'])->name('agent-show');

        Route::get('regular-user', [CustomerController::class, 'regular_user'])->name('regular-user');
        Route::get('regular-user-show/{id}', [CustomerController::class, 'reqular_user_show'])->name('regular-user-show');



        Route::put('user-status/{id}', [CustomerController::class, 'changeStatus'])->name('user-status');
        Route::delete('user-delete/{id}', [CustomerController::class, 'destroy'])->name('user-delete');
        Route::get('pending-user-list', [CustomerController::class, 'pendingCustomerList'])->name('pending-user-list');
        Route::get('send-email-to-all-user', [CustomerController::class, 'sendEmailToAllUser'])->name('send-email-to-all-user');
        Route::post('send-mail-to-all-user', [CustomerController::class, 'sendMailToAllUser'])->name('send-mail-to-all-user');


        Route::get('send-email-to-all-agent', [CustomerController::class, 'sendEmailToAllAgent'])->name('send-email-to-all-agent');
        Route::post('send-mail-to-all-agent', [CustomerController::class, 'sendMailToAllAgent'])->name('send-mail-to-all-agent');


        Route::post('send-mail-to-single-user/{id}', [CustomerController::class, 'sendMailToSingleUser'])->name('send-mail-to-single-user');

        Route::resource('staff', StaffController::class);
        Route::put('staff-status/{id}', [StaffController::class, 'changeStatus'])->name('staff.status');


        Route::get('menu-visibility', [MenuVisibilityController::class, 'index'])->name('menu-visibility');
        Route::put('update-menu-visibility', [MenuVisibilityController::class, 'update'])->name('update-menu-visibility');
        Route::get('/menu-visibility/translation/{code}', [MenuVisibilityController::class, 'editTranslation'])->name('menu-visibility.translation');
        Route::post('/menu-visibility/translation/{code}', [MenuVisibilityController::class, 'updateTranslation'])->name('menu-visibility.translation.update');


        Route::resource('error-page', ErrorPageController::class);

        Route::get('general-setting', [SettingController::class, 'index'])->name('general-setting');
        Route::put('update-general-setting', [SettingController::class, 'updateGeneralSetting'])->name('update-general-setting');

        Route::put('update-theme-color', [SettingController::class, 'updateThemeColor'])->name('update-theme-color');

        Route::put('update-logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');
        Route::put('update-cookie-consent', [SettingController::class, 'updateCookieConset'])->name('update-cookie-consent');
        Route::put('update-google-recaptcha', [SettingController::class, 'updateGoogleRecaptcha'])->name('update-google-recaptcha');
        Route::put('update-facebook-comment', [SettingController::class, 'updateFacebookComment'])->name('update-facebook-comment');
        Route::put('update-tawk-chat', [SettingController::class, 'updateTawkChat'])->name('update-tawk-chat');
        Route::put('update-google-analytic', [SettingController::class, 'updateGoogleAnalytic'])->name('update-google-analytic');
        Route::put('update-custom-pagination', [SettingController::class, 'updateCustomPagination'])->name('update-custom-pagination');
        Route::put('update-social-login', [SettingController::class, 'updateSocialLogin'])->name('update-social-login');
        Route::put('update-facebook-pixel', [SettingController::class, 'updateFacebookPixel'])->name('update-facebook-pixel');
        Route::put('update-pusher', [SettingController::class, 'updatePusher'])->name('update-pusher');

        Route::get('maintainance-mode', [ContentController::class, 'maintainanceMode'])->name('maintainance-mode');
        Route::put('maintainance-mode-update', [ContentController::class, 'maintainanceModeUpdate'])->name('maintainance-mode-update');

        Route::get('default-avatar', [ContentController::class, 'defaultAvatar'])->name('default-avatar');
        Route::post('update-default-avatar', [ContentController::class, 'updateDefaultAvatar'])->name('update-default-avatar');



        Route::resource('country', CountryController::class);
        Route::put('country-status/{id}', [CountryController::class, 'changeStatus'])->name('country-status');

        Route::resource('state', CountryStateController::class);
        Route::put('state-status/{id}', [CountryStateController::class, 'changeStatus'])->name('state-status');

        // language routes
        Route::resource('languages', LanguageController::class)->except('show');
        Route::get('admin-language/{code}', [LanguageController::class, 'adminLnagugae'])->name('admin-language');
        Route::post('update-admin-language/{code}', [LanguageController::class, 'updateAdminLanguage'])->name('update-admin-language');

        Route::get('admin-validation-language/{code}', [LanguageController::class, 'adminValidationLnagugae'])->name('admin-validation-language');
        Route::post('update-admin-validation-language/{code}', [LanguageController::class, 'updateAdminValidationLnagugae'])->name('update-admin-validation-language');


        Route::get('website-language/{code}', [LanguageController::class, 'websiteLanguage'])->name('website-language');
        Route::post('update-language/{code}', [LanguageController::class, 'updateLanguage'])->name('update-language');

        Route::get('website-validation-language/{code}', [LanguageController::class, 'websiteValidationLanguage'])->name('website-validation-language');
        Route::post('update-validation-language/{code}', [LanguageController::class, 'updateValidationLanguage'])->name('update-validation-language');

        Route::post('translate-all-language', [LanguageController::class, 'translateAll'])->name('translateAll');
    });
});
