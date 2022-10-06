<div wire:poll.5000ms>
@if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator'))
        @include('panel.bord.orderTableAdmin')
    @elseif(Auth::user()->hasRole('vendor'))
        @include('panel.bord.vendor')
    @endif
</div>
    <!--end::Quick Cart-->
