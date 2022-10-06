
<div class="col-lg-6 col-xxl-4">
    <!--begin::List Widget 9-->
    <div class="card card-custom card-stretch gutter-b">
        <!--begin::Header-->
        <div class="card-header align-items-center border-0 mt-4">
            <h3 class="card-title align-items-start flex-column">
                <span class="font-weight-bolder text-dark">@lang('order.orders')</span>
                <span class="text-muted mt-3 font-weight-bold font-size-sm">@lang('order.count') {{\App\Models\Order::orderBy('id', 'desc')->where('created_by', Auth::id())->count()}}</span>
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
            @foreach(\App\Models\Order::orderBy('id', 'desc')->where('created_by', Auth::id())->take(10)->get() as $order)
                <!--begin::Item-->
                    <div class="timeline-item align-items-start">
                        <!--begin::Label-->
                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                            {{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}
                        </div>
                        <!--end::Label-->
                        <!--begin::Badge-->
                        <div class="timeline-badge">
                            <i class='fa fa-genderless text-'.{{ $order->statusOrder->cards_color }}.' icon-xl'></i>
                            
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
