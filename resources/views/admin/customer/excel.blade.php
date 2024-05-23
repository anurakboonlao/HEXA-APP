<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Export</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>รหัสลูกค้า</td>
            <td>ระดับการใช้งาน</td>
            <td>ชื่อเต็ม</td>
            <td>ที่อยู่</td>
            <td>อีเมล</td>
            <td>เบอร์โทรศัพท์</td>
            <td>ชื่อผู้ใช้</td>
            <td>วันที่ใช้งานระบบ</td>
        </tr>
            @foreach ($customers as $key => $customer)
            <tr>
                <td> {{ $customer->customer_verify_key }} </td>
                <td> {{ ($customer->type) ? type_customer($customer->type) : 'Guest' }} </td>
                <td> {{ $customer->name }} </td>
                <td> {{ $customer->address }} </td>
                <td> {{ $customer->email }} </td>
                <td> {{ $customer->phone }} </td>
                <td> {{ $customer->username }} </td>
                <td> {{ set_date_format($customer->created_at) }} </td>
            </tr>
            @endforeach
    </table>
</body>
</html>