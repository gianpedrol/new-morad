@php
    $setting = App\Models\Setting::first();
@endphp


<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">{{ $setting->sidebar_lg_header }}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('admin.dashboard') }}">{{ $setting->sidebar_sm_header }}</a>
      </div>
      <ul class="sidebar-menu">
          <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>{{__('admin.Dashboard')}}</span></a></li>

          <li class="nav-item dropdown {{ Route::is('admin.all-order') || Route::is('admin.order-show') || Route::is('admin.pending-order') || Route::is('admin.pending-renew-order') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>{{__('admin.Orders')}}</span></a>

            <ul class="dropdown-menu">

              <li class="{{ Route::is('admin.all-order') || Route::is('admin.order-show') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.all-order') }}">{{__('admin.All Orders')}}</a></li>

              <li class="{{ Route::is('admin.pending-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-order') }}">{{__('admin.Pending Payment')}}</a></li>

              <li class="{{ Route::is('admin.pending-renew-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-renew-order') }}">{{__('admin.Pending Renew Payment')}}</a></li>

            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.property-type.*') || Route::is('admin.property-purpose.*') || Route::is('admin.nearest-location.*') || Route::is('admin.aminity.*') || Route::is('admin.package.*') || Route::is('admin.property.*') || Route::is('admin.agent-property') || Route::is('admin.property-review') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas far fa-building"></i><span>{{__('admin.Real Estate')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.property.create') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.property.create') }}">{{__('admin.Create Property')}}</a></li>

                <li class="{{ Route::is('admin.property.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.property.index') }}">{{__('admin.My Property')}}</a></li>

              <li class="{{ Route::is('admin.agent-property') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.agent-property') }}">{{__('admin.Agent Property')}}</a></li>


              <li class="{{ Route::is('admin.property-type.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.property-type.index') }}">{{__('admin.Property Type')}}</a></li>

              <li class="{{ Route::is('admin.property-purpose.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.property-purpose.index') }}">{{__('admin.Property Purpose')}}</a></li>

              <li class="{{ Route::is('admin.nearest-location.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.nearest-location.index') }}">{{__('admin.Nearest Location')}}</a></li>

              <li class="{{ Route::is('admin.aminity.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.aminity.index') }}">{{__('admin.Aminities')}}</a></li>

              <li class="{{ Route::is('admin.package.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.package.index') }}">{{__('admin.Package')}}</a></li>



              <li class="{{ Route::is('admin.property-review') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.property-review') }}">{{__('admin.Property Review')}}</a></li>



            </ul>
          </li>


          <li class="nav-item dropdown {{ Route::is('admin.country.*') || Route::is('admin.state.*') || Route::is('admin.city.*') || Route::is('admin.city-import-page') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker-alt"></i><span>{{__('admin.Locations')}}</span></a>

            <ul class="dropdown-menu">
                <li class=""><a class="nav-link" href="{{ route('admin.city.create') }}">{{__('admin.Create City')}}</a></li>

                <li class="{{ Route::is('admin.city.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.city.index') }}">{{__('admin.City List')}}</a></li>

            </ul>
          </li>


          <li class="{{ Route::is('admin.payment-method') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.payment-method') }}"><i class="fas fa-dollar-sign"></i> <span>{{__('admin.Payment Method')}}</span></a></li>


          <li class="nav-item dropdown {{ Route::is('admin.assign-package') || Route::is('admin.create-user') || Route::is('admin.our-agent') || Route::is('admin.agent-show') || Route::is('admin.send-email-to-all-agent') || Route::is('admin.send-email-to-all-user') || Route::is('admin.regular-user') || Route::is('admin.regular-user-show') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>{{__('admin.Users')}}</span></a>
            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.create-user') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.create-user') }}">{{__('admin.Create User')}}</a></li>

                <li class="{{ Route::is('admin.assign-package') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.assign-package') }}">{{__('admin.Assign Package')}}</a></li>

                <li class="{{ Route::is('admin.our-agent') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.our-agent') }}">{{__('admin.Our Agent')}}</a></li>

                <li class="{{ Route::is('admin.regular-user') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.regular-user') }}">{{__('admin.Regular User')}}</a></li>
            </ul>
          </li>

         <!-- <li class="nav-item dropdown {{ Route::is('admin.career.*') || Route::is('admin.career-request')  ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('admin.Career')}}</span></a>

            <ul class="dropdown-menu">

              <li class="{{ Route::is('admin.career.*') || Route::is('admin.career-request') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.career.index') }}">{{__('admin.Job Offer')}}</a></li>


            </ul>
          </li>-->

          <li class="nav-item dropdown {{  Route::is('admin.maintainance-mode') ||   Route::is('admin.home-page') || Route::is('admin.banner-image.index') || Route::is('admin.homepage-one-visibility') || Route::is('admin.cart-bottom-banner') || Route::is('admin.seo-setup') || Route::is('admin.menu-visibility') || Route::is('admin.product-detail-page') || Route::is('admin.default-avatar') || Route::is('admin.subscription-banner') || Route::is('admin.testimonial.*') || Route::is('admin.our-team.*') || Route::is('admin.counter.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-globe"></i><span>{{__('admin.Manage Website')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.seo-setup') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seo-setup') }}">{{__('admin.SEO Setup')}}</a></li>


                <li class="{{ Route::is('admin.maintainance-mode') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.maintainance-mode') }}">{{__('admin.Maintainance Mode')}}</a></li>


                <li class="{{ Route::is('admin.banner-image.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.banner-image.index') }}">{{__('admin.Banner Image')}}</a></li>

                <li class="{{ Route::is('admin.default-avatar') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.default-avatar') }}">{{__('admin.Default Avatar')}}</a></li>

            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.footer.*') || Route::is('admin.social-link.*') || Route::is('admin.footer-link.*') || Route::is('admin.second-col-footer-link') || Route::is('admin.third-col-footer-link') || Route::is('admin.topbar-contact') || Route::is('admin.menu-visibility') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('admin.Header & Footer')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.menu-visibility') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.menu-visibility') }}">{{__('admin.Menu Visibility')}}</a></li>

                <li class="{{ Route::is('admin.topbar-contact') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.topbar-contact') }}">{{__('admin.Topbar Contact')}}</a></li>

                <li class="{{ Route::is('admin.social-link.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.social-link.index') }}">{{__('admin.Social Link')}}</a></li>

            </ul>
          </li>



          <li class="nav-item dropdown {{ Route::is('admin.about-us.*') || Route::is('admin.custom-page.*') || Route::is('admin.terms-and-condition.*') || Route::is('admin.privacy-policy.*') || Route::is('admin.faq.*') || Route::is('admin.error-page.*') || Route::is('admin.contact-us.*') || Route::is('admin.homepage') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-columns"></i><span>{{__('admin.Pages')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.homepage') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.homepage') }}">{{__('admin.Homepage')}}</a></li>

                <li class="{{ Route::is('admin.about-us.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.about-us.index') }}">{{__('admin.About Us')}}</a></li>

                <li class="{{ Route::is('admin.contact-us.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-us.index') }}">{{__('admin.Contact Us')}}</a></li>

                <li class="{{ Route::is('admin.terms-and-condition.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.terms-and-condition.index') }}">{{__('admin.Terms And Conditions')}}</a></li>

                <li class="{{ Route::is('admin.privacy-policy.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.privacy-policy.index') }}">{{__('admin.Privacy Policy')}}</a></li>

                <li class="{{ Route::is('admin.faq.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.faq.index') }}">{{__('admin.FAQ')}}</a></li>

                <li class="{{ Route::is('admin.error-page.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.error-page.index') }}">{{__('admin.Error Page')}}</a></li>


            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.blog-category.*') || Route::is('admin.blog.*') || Route::is('admin.popular-blog.*') || Route::is('admin.blog-comment.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('admin.Blogs')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.blog-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog-category.index') }}">{{__('admin.Categories')}}</a></li>

                <li class="{{ Route::is('admin.blog.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog.index') }}">{{__('admin.Blogs')}}</a></li>

                <li class="{{ Route::is('admin.popular-blog.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.popular-blog.index') }}">{{__('admin.Popular Blogs')}}</a></li>

                <li class="{{ Route::is('admin.blog-comment.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog-comment.index') }}">{{__('admin.Comments')}}</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.email-configuration') || Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-envelope"></i><span>{{__('admin.Email Configuration')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.email-configuration') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-configuration') }}">{{__('admin.Setting')}}</a></li>

                <li class="{{ Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-template') }}">{{__('admin.Email Template')}}</a></li>
            </ul>
          </li>


          <li class="nav-item dropdown {{ Route::is('admin.languages.*') || Route::is('admin.admin-language') || Route::is('admin.admin-validation-language') || Route::is('admin.website-language') || Route::is('admin.website-validation-language') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('admin.Language')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.languages.*') || Route::is('admin.admin-language') || Route::is('admin.admin-validation-language') || Route::is('admin.website-language') || Route::is('admin.website-validation-language') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.languages.index') }}">{{__('admin.Manage Language')}}</a></li>
            </ul>
          </li>

          <li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.general-setting') }}"><i class="fas fa-cog"></i> <span>{{__('admin.Setting')}}</span></a></li>

          @php
              $logedInAdmin = Auth::guard('admin')->user();
          @endphp

          <li class="{{ Route::is('admin.subscriber') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.subscriber') }}"><i class="fas fa-fire"></i> <span>{{__('admin.Subscribers')}}</span></a></li>

          <li class="{{ Route::is('admin.contact-message') || Route::is('admin.show-contact-message') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-message') }}"><i class="fas fa-fa fa-envelope"></i> <span>{{__('admin.Contact Message')}}</span></a></li>


          <li class="{{ Route::is('admin.staff.*')  ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.staff.index') }}"><i class="fas fa-users"></i> <span>{{__('admin.Staff List')}}</span></a></li>

          @if ($logedInAdmin->admin_type == 1)
            <li class="{{ Route::is('admin.admin.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.admin.index') }}"><i class="fas fa-user"></i> <span>{{__('admin.Admin list')}}</span></a></li>
          @endif

        </ul>

    </aside>
  </div>
