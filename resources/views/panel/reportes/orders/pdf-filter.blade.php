<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>تقرير - الطلبات</title>
    <link href="{{public_path('panel/assets/css/reports.css')}}" rel="stylesheet">

</head>
<body dir="rtl">

<htmlpageheader name="page-header">
    <img src="{{public_path('image/ff.png')}}" style="width:29cm;height: 3cm">
</htmlpageheader>
<br />

<table class="striped">
    <thead>
    <tr class="table header_table">
        <th width="50%" class="text-right" scope="col">@lang('pdf.date') : {{ \Carbon\Carbon::now()->format('Y/m/d') }}</th>
        <th width="50%" class="text-left" scope="col">@lang('pdf.time') : {{ \Carbon\Carbon::now()->format('g:i A') }}</th>
    </tr>

    </thead>
    <tbody>
    </tbody>
</table>

<br />

<table class="striped">
    <thead>
    <th width="100%" class="center" scope="col"></th>
    <th width="100%" class="center" scope="col"></th>
    </thead>
    <tbody>
    <tr>
        <th width="100%" class="center" scope="col"><h1> @lang('navbar.order')</h1></th>
    </tr>
    </tbody>
</table>
<br />
<table class="striped">
    <thead>
    <th width="50%" class="center" scope="col"></th>
    </thead>
    <tbody>
    <tr>
        <th width="50%" class="center" scope="col"><h3>@lang('pdf.description')</h3></th>
    </tr>
    </tbody>
</table>

<br />
<table style="">
    <thead>
    <tr class="table header_table">
        <th style="background-color:#ccc" scope="col">#</th>
        <th style="background-color:#ccc"  scope="col">@lang('orderTable.name')</th>
        <th style="background-color:#ccc" scope="col">@lang('orderTable.from')</th>
        <th style="background-color:#ccc" scope="col">@lang('orderTable.to')</th>
        <th style="background-color:#ccc" scope="col">@lang('orderTable.phone')</th>
        <th style="background-color:#ccc" scope="col">@lang('orderTable.vendor')</th>
    </tr>

    </thead>

    <tbody>
        @foreach($data as $d)
            <tr>
                <td width="5%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d['id']}}</td>
                <td width="15%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d['name']}}</td>
                <td width="15%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d['from_address']}}</td>
                <td width="15%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d['to_address']}}</td>
                <td width="25%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d['phone']}}</td>
                <td width="25%" style="border-bottom: 1px solid #ddd;" scope="col">{{$d->vendor['name']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<htmlpagefooter name="page-footer">
     <img src="{{public_path('image/hh.jpg')}}" style="width: 29cm; height: 5cm"> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>{PAGENO} </small>
    <table>
        <tr>

        </tr>
    </table>
</htmlpagefooter>

</body>

</html>
