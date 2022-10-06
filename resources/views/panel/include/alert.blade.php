<script>
function showAlertMessage(messageType, title, message, timeOut) {
{{--var positionClass = "toast-bottom-right";--}}
{{--@if(app()->getLocale() == 'ar')--}}

{{--        @endif--}}
var positionClass = "toast-bottom-right";
toastr.options = {
"closeButton": true,
"debug": true,
"newestOnTop": true,
"progressBar": true,
"positionClass": positionClass,
"preventDuplicates": false,
"onclick": null,
"showDuration": "300",
"hideDuration": "1000",
"timeOut": timeOut,
"extendedTimeOut": "1000",
"showEasing": "swing",
"hideEasing": "linear",
"showMethod": "fadeIn",
"hideMethod": "fadeOut"
};

if(messageType === 'success')
toastr.success(message, title);

if(messageType === 'info')
toastr.info(message, title);

if(messageType === 'warning')
toastr.warning(message, title);

if(messageType === 'danger')
toastr.error(message, title);
}
</script>
