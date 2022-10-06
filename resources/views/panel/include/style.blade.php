@if(app()->getLocale() == 'ar')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{asset('panel/assets/plugins/custom/fullcalendar/fullcalendar.bundle.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('panel/assets/plugins/global/plugins.bundle.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/plugins/custom/prismjs/prismjs.bundle.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/style.bundle.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{asset('panel/assets/css/themes/layout/header/base/dark.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/header/menu/dark.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/brand/dark.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/themes/layout/aside/dark.rtl.css?v=7.2.9')}}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
    </style>
@else
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow&display=swap" rel="stylesheet">
<!--begin::Page Vendors Styles(used by this page)-->
<link href="{{asset('panel/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{asset('panel/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('panel/assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('panel/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--end::Global Theme Styles-->
<!--begin::Layout Themes(used by all pages)-->
<link href="{{asset('panel/assets/css/themes/layout/header/base/dark.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('panel/assets/css/themes/layout/header/menu/dark.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('panel/assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('panel/assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: 'PT Sans Narrow', sans-serif;
        }
    </style>
@endif
<!--end::Layout Themes-->
<meta name="token" content="{{csrf_token()}}" />
{{-- <link rel="shortcut icon" href="{{asset('panel/assets/media/logos/favicon.ico')}}" /> --}}
