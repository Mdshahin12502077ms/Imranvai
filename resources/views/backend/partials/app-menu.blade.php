 <!-- ========== App Menu ========== -->
 <div class="app-menu navbar-menu">
     <!-- LOGO -->
     <div class="navbar-brand-box">
         <!-- Dark Logo-->
         <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
             <span class="logo-sm">
                 @if (!empty($adminSetting->mini_logo))
                     <img src="{{ asset($adminSetting->mini_logo) }}" alt="Logo" height="22">
                 @endif
             </span>
             <span class="logo-lg">
                 @if (!empty($adminSetting->logo))
                     <img src="{{ asset($adminSetting->logo) }}" alt="Logo" height="35">
                 @endif
             </span>
         </a>
         <!-- Light Logo-->
         <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
             <span class="logo-sm">
                 @if (!empty($adminSetting->mini_logo))
                     <img src="{{ asset($adminSetting->mini_logo) }}" alt="Logo" height="22">
                 @endif
             </span>
             <span class="logo-lg">
                 @if (!empty($adminSetting->logo))
                     <img src="{{ asset($adminSetting->logo) }}" alt="Logo" height="35">
                 @endif
             </span>
         </a>
         <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
             <i class="ri-record-circle-line"></i>
         </button>
     </div>

     <!-- sidebar-user -->
     <div class="dropdown sidebar-user m-1 rounded">
         <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <span class="d-flex align-items-center gap-2">
                 <img class="rounded header-profile-user" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('backend/assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                 <span class="text-start">
                     <span class="d-block fw-medium sidebar-user-name-text">{{ auth()->user()->name }}</span>
                     <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                 </span>
             </span>
         </button>
         <div class="dropdown-menu dropdown-menu-end">
             <!-- item-->
             <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}!</h6>
             <a class="dropdown-item" href="{{ route('admin.profile-settings.edit') }}"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                     class="align-middle">Profile</span></a>
             <!-- Logout -->
             <form method="POST" action="{{ route('logout') }}">
                 @csrf
                 <button type="submit" class="dropdown-item">
                     <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                     <span class="align-middle" data-key="t-logout">Logout</span>
                 </button>
             </form>
         </div>
     </div>

     <!-- sidebar -->
     <div id="scrollbar">
         <div class="container-fluid">

             <div id="two-column-menu">
             </div>
             <ul class="navbar-nav" id="navbar-nav">

                 <!--  Menu -->
                 <li class="menu-title"><span data-key="t-menu">Menu</span></li>


                 {{-- Landing Page Menu --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.landing-page.*') ? '' : 'collapsed' }}" href="#sidebarLandingPage" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.landing-page.*') ? 'true' : 'false' }}" aria-controls="sidebarLandingPage">
                         <i class="ri-layout-top-line"></i> <span>Landing Page</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.landing-page.*') ? 'show' : '' }}" id="sidebarLandingPage">
                         <ul class="nav nav-sm flex-column">
                             <li class="nav-item">
                                 <a href="{{ route('admin.landing-page.banners.index') }}" class="nav-link {{ request()->routeIs('admin.landing-page.banners.*') ? 'active' : '' }}">
                                     Banners
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.landing-page.taglines.index') }}" class="nav-link {{ request()->routeIs('admin.landing-page.taglines.*') ? 'active' : '' }}">
                                     Taglines
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.landing-page.features.index') }}" class="nav-link {{ request()->routeIs('admin.landing-page.features.*') ? 'active' : '' }}">
                                     Features
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.landing-page.why-ev.index') }}" class="nav-link {{ request()->routeIs('admin.landing-page.why-ev.*') ? 'active' : '' }}">
                                     Why EV Systems
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 {{-- Category Menu --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.categories.*') ? '' : 'collapsed' }}" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}" aria-controls="sidebarCategory">
                         <i class="ri-folder-line"></i> <span>Category</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="sidebarCategory">
                         <ul class="nav nav-sm flex-column">
                             <li class="nav-item">
                                 <a href="{{ route('admin.categories.create') }}" class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                                     Add Category
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                     All Categories
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 {{-- Product Menu --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.products.*') ? '' : 'collapsed' }}" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}" aria-controls="sidebarProduct">
                         <i class="ri-folder-line"></i> <span>Product</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.products.*') ? 'show' : '' }}" id="sidebarProduct">
                         <ul class="nav nav-sm flex-column">

                             <li class="nav-item">
                                 <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                     All Products
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 {{-- product variation --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.product-variations.*') ? '' : 'collapsed' }}" href="#sidebarProductVariation" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.product-variations.*') ? 'true' : 'false' }}" aria-controls="sidebarProductVariation">
                         <i class="ri-folder-line"></i> <span>Product Variation</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.product-variations.*') ? 'show' : '' }}" id="sidebarProductVariation">
                         <ul class="nav nav-sm flex-column">

                             <li class="nav-item">
                                 <a href="{{ route('admin.product-variations.index') }}" class="nav-link {{ request()->routeIs('admin.product-variations.index') ? 'active' : '' }}">
                                     All Product Variations
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

                 {{--order--}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.orders.*') ? '' : 'collapsed' }}" href="#sidebarOrder" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}" aria-controls="sidebarOrder">
                         <i class="ri-folder-line"></i> <span>Order</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.orders.*') ? 'show' : '' }}" id="sidebarOrder">
                         <ul class="nav nav-sm flex-column">

                             <li class="nav-item">
                                 <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                     All Orders
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>


                 {{-- Built Every Condition --}}

                 {{-- Every Condition  --}}


                 {{-- Product Condition --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.productCondition.*') ? '' : 'collapsed' }}" href="#sidebarProductCondition" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.productCondition.*') ? 'true' : 'false' }}" aria-controls="sidebarProductCondition">
                         <i class="ri-folder-line"></i> <span>Product Condition</span>
                     </a>
                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.productCondition.*') ? 'show' : '' }}" id="sidebarProductCondition">
                         <ul class="nav nav-sm flex-column">
                             <li class="nav-item">
                                 <a href="{{ route('admin.productCondition.index') }}" class="nav-link {{ request()->routeIs('admin.productCondition.*') ? 'active' : '' }}">
                                    All Product Conditions
                                 </a>
                             </li>

                         </ul>
                     </div>
                 </li>

                 {{-- Contact Messages --}}
                 <li class="nav-item">
                     <a href="{{ route('admin.contact-messages.index') }}" class="nav-link menu-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                         <i class="ri-mail-send-line"></i> <span>Contact Messages</span>
                     </a>
                 </li>

                 {{-- Settings --}}
                 <li class="menu-title"><span data-key="t-menu">Settings</span></li>

                 {{-- Settings Section --}}
                 <li class="nav-item">
                     <a class="nav-link menu-link {{ request()->routeIs('admin.profile-settings.*') || request()->routeIs('admin.managers.*') || request()->routeIs('admin.social-settings.*') || request()->routeIs('admin.paypal-settings.*') || request()->routeIs('admin.stripe-settings.*') || request()->routeIs('admin.admin-settings.*') || request()->routeIs('admin.system-settings.*') || request()->routeIs('admin.mail-settings.*') ? '' : 'collapsed' }}"
                         href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                         aria-expanded="{{ request()->routeIs('admin.profile-settings.*') || request()->routeIs('admin.managers.*') || request()->routeIs('admin.social-settings.*') || request()->routeIs('admin.paypal-settings.*') || request()->routeIs('admin.stripe-settings.*') || request()->routeIs('admin.admin-settings.*') || request()->routeIs('admin.system-settings.*') || request()->routeIs('admin.mail-settings.*') ? 'true' : 'false' }}"
                         aria-controls="sidebarSettings">
                         <i class="ri-settings-3-line"></i> <span>Settings</span>
                     </a>

                     <div class="collapse menu-dropdown {{ request()->routeIs('admin.profile-settings.*') || request()->routeIs('admin.managers.*') || request()->routeIs('admin.social-settings.*') || request()->routeIs('admin.paypal-settings.*') || request()->routeIs('admin.stripe-settings.*') || request()->routeIs('admin.admin-settings.*') || request()->routeIs('admin.system-settings.*') || request()->routeIs('admin.mail-settings.*') ? 'show' : '' }}"
                         id="sidebarSettings">

                         <ul class="nav nav-sm flex-column">
                             {{-- Profile Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.profile-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.profile-settings.*') ? 'active' : '' }}">
                                     <i class="ri-user-settings-line"></i> <span>Profile Settings</span>
                                 </a>
                             </li>

                             {{-- Manage Managers --}}
                             @if (auth()->user()->role == 'admin')
                                 <li class="nav-item">
                                     <a href="{{ route('admin.managers.index') }}" class="nav-link {{ request()->routeIs('admin.managers.*') ? 'active' : '' }}">
                                         <i class="ri-group-line"></i> <span>Manage Managers</span>
                                     </a>
                                 </li>
                             @endif

                             {{-- Social Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.social-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.social-settings.*') ? 'active' : '' }}">
                                     <i class="ri-share-line"></i> <span>Social Settings</span>
                                 </a>
                             </li>

                             {{-- PayPal Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.paypal-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.paypal-settings.*') ? 'active' : '' }}">
                                     <i class="ri-paypal-line"></i> <span>PayPal Settings</span>
                                 </a>
                             </li>

                             {{-- System Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.system-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.system-settings.*') ? 'active' : '' }}">
                                     <i class="ri-settings-3-line"></i> <span>System Settings</span>
                                 </a>
                             </li>

                             {{-- Admin Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.admin-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.admin-settings.*') ? 'active' : '' }}">
                                     <i class="ri-settings-3-line"></i> <span>Admin Settings</span>
                                 </a>
                             </li>

                             {{-- Mail Settings --}}
                             <li class="nav-item">
                                 <a href="{{ route('admin.mail-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.mail-settings.*') ? 'active' : '' }}">
                                     <i class="ri-mail-settings-line"></i> <span>Mail Settings</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>

             </ul>
         </div>
         <!-- Sidebar -->
     </div>

     <div class="sidebar-background"></div>
 </div>
