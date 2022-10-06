<!--Begin::Row-->
{{--<div class="row">--}}
{{--    <h3>@lang('sidebar.order')</h3>--}}
{{--</div>--}}
<div class="row">

    @foreach ( App\Models\StatusSystem::where('status', '1')->get() as $st)
        <div class="col-xl-3">
            <!--begin::Stats Widget 13-->
            <a href="#" style="height: 46px;" class="card card-custom bg-{{ $st->cards_color }} bg-hover-state-danger card-stretch gutter-b">
                <!--begin::Body-->
                <div style="padding: 1rem 2.25rem;" class="card-body">


                    <div class="text-inverse-danger font-weight-bolder font-size-h5 mb-2">
                        {{ $st->name }} - {{App\Models\Order::where('order_status', $st->id)->count()}}
                        </div>
                    <!--<div class="font-weight-bold text-inverse-danger font-size-sm"></div>-->
                </div>
                <!--end::Body-->
            </a>
            <!--end::Stats Widget 13-->
        </div>
    @endforeach
</div>
<!--End::Row-->
