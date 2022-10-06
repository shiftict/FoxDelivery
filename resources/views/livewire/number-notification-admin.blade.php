<div wire:poll.5000ms>
    {{-- Stop trying to control. --}}
    <span style="height: 25px;
  width: 25px;
  background-color: #e1e11c;
  color: #1e1e2d;
  font-family: 'Cairo', sans-serif;
  border-radius: 50%;
  display: inline-block;
  text-align: center;">
        {{App\Models\NotificationOrder::where('read', '0')->count() + App\Models\NotificationVendorOrder::where('read', '0')->where('user_id', Auth::id())->orderBy('id', 'DESC')->count()}}
    </span>
</div>
