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
          <li class="{{ Route::is('staff.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('staff.dashboard') }}"><i class="fas fa-home"></i> <span>{{__('admin.Dashboard')}}</span></a></li>

          <li class="nav-item dropdown {{ Route::is('staff.property.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas far fa-building"></i><span>{{__('admin.Real Estate')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('staff.property.create') ? 'active' : '' }}"><a class="nav-link" href="{{ route('staff.property.create') }}">{{__('admin.Create Property')}}</a></li>

                <li class="{{ Route::is('staff.property.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('staff.property.index') }}">{{__('admin.My Property')}}</a></li>

            </ul>
          </li>

        </ul>

    </aside>
  </div>
