<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ประวัติอะไหล่ - {{ (request()->input('type') == 'in') ? "ซื้อเข้า" : "ขายออก" }}</title>
</head>
<body>
    @if (request()->input('type') == 'in')
        @include('admin.report.elements.excel_history_in_table')
    @else
        @include('admin.report.elements.excel_history_out_table')
    @endif
</body>
</html>