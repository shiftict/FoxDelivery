<div class="row">
    <div class="col-lg-6 col-xxl-4">
        <!--begin::List Widget 9-->
        <div class="card card-custom card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header align-items-center border-0 mt-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">@lang('order.orders')</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">@lang('order.count') {{\App\Models\Order::count()}}</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{Route('order.index')}}" class="btn btn-clean btn-light-success btn-hover-light-primary">
                        @lang('order.allOrders')
                    </a>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-4">
                <!--begin::Timeline-->
                <div class="timeline timeline-6 mt-3">
                @foreach(\App\Models\Order::orderBy('id', 'desc')->take(10)->get() as $order)
                    <!--begin::Item-->
                        <div class="timeline-item align-items-start">
                            <!--begin::Label-->
                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                {{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}
                            </div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-{{ $order->statusOrder->cards_color }} icon-xl"></i>
                                {{--  @if ($order->order_status == '0')
                                    <i class="fa fa-genderless text-primary icon-xl"></i>
                                @elseif($order->order_status == '1')
                                    <i class="fa fa-genderless text-warning icon-xl"></i>
                                @elseif($order->order_status == '2')
                                    <i class="fa fa-genderless text-success icon-xl"></i>
                                @elseif($order->order_status == '3')
                                    <i class="fa fa-genderless text-warning icon-xl"></i>
                                @elseif($order->order_status == '4')
                                    <i class="fa fa-genderless text-warning icon-xl"></i>
                                @endif  --}}
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="font-weight-mormal font-size-lg timeline-content
    @if($order->status == '1' || $order->status == '2') text-muted @endif pl-3">{{$order->name}}</div>
                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                {{ $order->statusOrder->name }}
                            </div>
                            <a href="{{route("order.show", $order->id)}}" class="navi-link">
                                                        <span class="navi-icon"><i class="la la-eye"></i></span>
                                                        <span class="navi-text"></span>
                                                    </a>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                    @endforeach
                </div>
                <!--end::Timeline-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end: List Widget 9-->
    </div>
    <div class="col-lg-6 col-xxl-4">
        <!--begin::List Widget 9-->
										<!--begin::List Widget 1-->
										<div class="card card-custom card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label font-weight-bolder text-dark">@lang('sidebar.vendor')</span>
													<span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
												</h3>
												<div class="card-toolbar">
                    <a href="{{Route('vendor.index')}}" class="btn btn-clean btn-light-success btn-hover-light-primary">
                        @lang('order.allOrders')
                    </a>
                </div>
											</div>
											<!--end::Header-->
											
											<!--begin::Body-->
											<div class="card-body pt-8">
											    @foreach(\App\Models\Vendor::orderBy('id', 'desc')->take(10)->get() as $vendor)
    												<!--begin::Item-->
    												<div class="d-flex align-items-center mb-10">
    													<!--begin::Symbol-->
    													<div class="symbol symbol-40 symbol-light-primary mr-5">
    														<span class="symbol-label">
    															<i class="fas fa-user-tie text-success"></i>
    														</span>
    													</div>
    													<!--end::Symbol-->
    													<!--begin::Text-->
    													<div class="d-flex flex-column font-weight-bold">
    														<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{$vendor->name}}</a>
    														<span class="text-muted"><i class="flaticon-email"></i> {{$vendor->email}}</span>
    													</div>
    													<!--end::Text-->
    												</div>
    												<!--end::Item-->
    											@endforeach
											</div>
											<!--end::Body-->
										</div>
										<!--end::List Widget 1-->
        <!--end: List Widget 9-->
    </div>
</div>
