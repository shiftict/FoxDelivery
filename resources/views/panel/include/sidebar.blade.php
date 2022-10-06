<ul class="menu-nav">
    @if(!Auth::user()->hasRole('employee'))
    <li class="menu-item menu-item-active" aria-haspopup="true">
        <a href="{{Route('dashboard.index')}}" class="menu-link">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
											<i class="fas fa-tachometer-alt text-yellow"></i>
                                            <!--end::Svg Icon-->
										</span>
            <span class="menu-text">@lang('sidebar.dashboard')</span>
        </a>
    </li>
    @endif

    @permission(['order_create', 'order_delete', 'order_read', 'order_update'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fab fa-shopify text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.order')</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                @permission(['order_delete', 'order_read', 'order_update'])
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                        <span class="menu-link">
                                                            <span class="menu-text">@lang('order.index')</span>
                                                        </span>
                </li>

                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('order.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.orderIndex')</span>
                    </a>
                </li>
                @endpermission
                @permission('order_create')
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('order.create')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.orderCreate')</span>
                    </a>
                </li>
                @endpermission
            </ul>
        </div>
    </li>
    @endpermission

    @permission(['vendor_create', 'vendor_delete', 'vendor_read', 'vendor_update'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-store-alt text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.vendor')</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                    <span class="menu-link">
                                                        <span class="menu-text">@lang('sidebar.index')</span>
                                                    </span>
                </li>
                @permission(['vendor_delete', 'vendor_read', 'vendor_update'])
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('vendor.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.vendorIndex')</span>
                    </a>
                </li>
                @endpermission
                @permission('vendor_create')
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('vendor.create')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.vendorCreate')</span>
                    </a>
                </li>
                @endpermission
            </ul>
        </div>
    </li>
    @endpermission

    @role(['employee'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="{{Route('read.order.employee')}}" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-store-alt text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.order')</span>
        </a>
    </li>
    @endrole

    @permission(['delivery_create', 'delivery_delete', 'delivery_read', 'delivery_update'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-car text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.delivery')</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                @permission(['delivery_delete', 'delivery_read', 'delivery_update'])
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                        <span class="menu-link">
                                                            <span class="menu-text">@lang('sidebar.index')</span>
                                                        </span>
                </li>
                @endpermission
                @permission(['delivery_delete', 'delivery_read', 'delivery_update'])
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('delivery.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.deliveryIndex')</span>
                    </a>
                </li>
                @endpermission
                @permission('delivery_create')
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('delivery.create')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.deliveryCreate')</span>
                    </a>
                </li>
                @endpermission
            </ul>
        </div>
    </li>
    @endpermission

    @permission('report_read')
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <!--<i class="fas fa-lock text-yellow"></i>-->
                                                <i class="fas fa-file-pdf text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.role')</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                    <span class="menu-link">
                                                        <span class="menu-text">@lang('role.role')</span>
                                                    </span>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('order.report')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.roleIndex')</span>
                    </a>
                </li>
                @role('superadministrator')
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('vendors.report')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.reportVendors')</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('vendors.custom.report')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.reportVendorsAdmin')</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('deliveries.report')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.reportDriver')</span>
                    </a>
                </li>
                @endrole
            </ul>
        </div>
    </li>
    @endpermission

    @permission(['users_create', 'users_delete', 'users_read', 'users_update'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-users text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.user')</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <i class="menu-arrow"></i>
            <ul class="menu-subnav">
                @permission(['users_permission', 'users_read', 'users_update'])
                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                        <span class="menu-link">
                                                            <span class="menu-text">@lang('customer.user')</span>
                                                        </span>
                </li>
                @endpermission
                @permission(['users_delete', 'users_read', 'users_update'])
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{Route('users.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                            <span></span>
                        </i>
                        <span class="menu-text">@lang('sidebar.userIndex')</span>
                    </a>
                </li>
                @endpermission
            </ul>
        </div>
    </li>
    @endpermission

    @permission(['city_create', 'area_create', 'city_delete', 'area_delete', 'city_read', 'area_read', 'city_update', 'area_update'])
        <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
            <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-map-marker text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
                <span class="menu-text">@lang('sidebar.area')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="menu-submenu">
                <i class="menu-arrow"></i>
                <ul class="menu-subnav">
                    <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                    <span class="menu-link">
                                                        <span class="menu-text">@lang('sidebar.area')</span>
                                                    </span>
                    </li>
                    @permission(['area_delete', 'area_read', 'area_update'])
                        <li class="menu-item" aria-haspopup="true">
                            <a href="{{Route('area.index')}}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">@lang('sidebar.areaIndex')</span>
                            </a>
                        </li>
                    @endpermission
                    @permission(['city_delete', 'city_read', 'city_update'])
                        <li class="menu-item" aria-haspopup="true">
                            <a href="{{Route('city.index')}}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">@lang('sidebar.cityIndex')</span>
                            </a>
                        </li>
                    @endpermission
                </ul>
            </div>
        </li>
    @endpermission
    {{-- @permission(['users_create', 'users_delete', 'users_read', 'users_update'])
        <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
            <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-users text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
                <span class="menu-text">@lang('sidebar.package')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="menu-submenu">
                <i class="menu-arrow"></i>
                <ul class="menu-subnav">
                    @permission(['users_permission', 'users_read', 'users_update'])
                        <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                        <span class="menu-link">
                                                            <span class="menu-text">@lang('customer.user')</span>
                                                        </span>
                        </li>
                    @endpermission
                    @permission(['users_delete', 'users_read', 'users_update'])
                        <li class="menu-item" aria-haspopup="true">
                            <a href="{{Route('packages.index')}}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">@lang('sidebar.packageIndex')</span>
                            </a>
                        </li>
                    @endpermission
                </ul>
            </div>
        </li>
    @endpermission --}}
    @permission(['method_shipping_create', 'method_shipping_delete', 'method_shipping_read', 'method_shipping_update'])
        <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
            <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-truck text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
                <span class="menu-text">@lang('sidebar.methodShipping')</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="menu-submenu">
                <i class="menu-arrow"></i>
                <ul class="menu-subnav">
                        <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                        <span class="menu-link">
                                                            <span class="menu-text">@lang('sidebar.methodShippingIndex')</span>
                                                        </span>
                        </li>
                    @permission(['method_shipping_delete', 'method_shipping_read', 'method_shipping_update'])
                        <li class="menu-item" aria-haspopup="true">
                            <a href="{{Route('method_shipping.index')}}" class="menu-link">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">@lang('sidebar.methodShippingIndex')</span>
                            </a>
                        </li>
                    @endpermission
                    @permission('method_shipping_create')
                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{Route('method_shipping.create')}}" class="menu-link">
                            <i class="menu-bullet menu-bullet-dot">
                                <span></span>
                            </i>
                            <span class="menu-text">@lang('sidebar.methodShippingCreate')</span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </div>
        </li>
    @endpermission
        @role(['vendor'])
        <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
            <a href="{{Route('vendor.settings')}}" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-user-cog text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
                <span class="menu-text">@lang('sidebar.setting')</span>
            </a>
        </li>
    @endrole
    @role(['vendor'])
    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="{{Route('users.index.vendor')}}" class="menu-link menu-toggle">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                                                <i class="fas fa-users text-yellow"></i>
                                                <!--end::Svg Icon-->
                                            </span>
            <span class="menu-text">@lang('sidebar.user')</span>
        </a>
    </li>
    @endrole
        @role('superadministrator')
        <li class="menu-item menu-item-active" aria-haspopup="true">
            <a href="{{Route('drivers.scheduling.drivers')}}" class="menu-link">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
											<i class="far fa-calendar-alt text-yellow"></i>
                                            <!--end::Svg Icon-->
										</span>
                <span class="menu-text">@lang('sidebar.scheduling_drivers')</span>
            </a>
        </li>
        @endrole
        @role('superadministrator')
        <li class="menu-item " aria-haspopup="true">
            <a href="{{Route('apk.attacheds')}}" class="menu-link">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
											<i class="far fa-solid fa-image text-yellow"></i>
                                            <!--end::Svg Icon-->
										</span>
                <span class="menu-text">@lang('sidebar.file_apk')</span>
            </a>
        </li>
        @endrole
</ul>
