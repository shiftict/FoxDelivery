<div wire:poll.5000ms>


        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content">
            <!--begin::Wrapper-->
            <div class="offcanvas-wrapper mb-5 scroll-pull">
                @role('superadministrator')
                    @foreach (App\Models\NotificationOrder::where('read', '0')->orderBy('id', 'DESC')->get() as $order)
                        <!--begin::Item-->
                            <div class="d-flex align-items-center justify-content-between py-8">
                                <div class="d-flex flex-column mr-2">
                                    <a href="{{ Route('read.order', $order->id) }}" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">@lang('notificationOrder.newOrder') # {{ $order->order_id }}</a>
                                    <span class="text-muted">{{ $order->created_at->diffForHumans();
                                    }}</span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Separator-->
                            <div class="separator separator-solid"></div>
                        @endforeach
                        @foreach (App\Models\NotificationVendorOrder::where('read', '0')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get() as $order)
                        <!--begin::Item-->
                            <div class="d-flex align-items-center justify-content-between py-8">
                                <div class="d-flex flex-column mr-2">
                                    <a href="{{ Route('read.order.vendor', $order->id) }}" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary"> {{ $order->statusOrder->name }} # {{ $order->order_id }}</a>
                                    <span class="text-muted">{{ $order->created_at->diffForHumans();
                                }}</span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Separator-->
                            <div class="separator separator-solid"></div>
                        @endforeach
                @endrole
                @role('vendor')
                @foreach (App\Models\NotificationVendorOrder::where('read', '0')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get() as $order)
                <!--begin::Item-->
                    {{--  <div style="background: {{ $order->statusOrder->color }}" class="d-flex align-items-center justify-content-between py-8">  --}}
                        <div class="d-flex align-items-center justify-content-between py-8">
                        <div class="d-flex flex-column mr-2">
                            <a href="{{ Route('read.order.vendor', $order->id) }}" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary"> {{ $order->statusOrder->name }} # {{ $order->order_id }} </a>
                            <span class="text-muted">{{ $order->created_at->diffForHumans();
                        }}</span>
                        </div>
                    </div>
                    <!--end::Item-->
                    <!--begin::Separator-->
                    <div class="separator separator-solid"></div>
                @endforeach
                @endrole('superadministrator')
            </div>
            <!--end::Wrapper-->
            <!--begin::Purchase-->
        {{-- <div class="offcanvas-footer">
            <div class="text-right">
                <button type="button" class="btn btn-primary text-weight-bold">Show all</button>
            </div>
        </div> --}}
        <!--end::Purchase-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Quick Cart-->
