<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback Export</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>#</td>
            <td>วันที่</td>
            <td>โค้ด</td>
            <td>
                รายละเอียด
                <br>
                คนไข้/แพทย์/ลูกค้า
            </td>
            <td>คะแนน</td>
            <td>ความคิดเห็น</td>
            <td>ลูกค้า</td>
        </tr>
        @foreach ($comments as $key => $comment)
            @php
                $detail = json_decode($comment->content);
            @endphp
            <tr>
                <td>{{ (($key + 1) * (request()->input('page') + 1)) }}</td>
                <td>{{ set_date_format($comment->created_at) }}</td>
                <td>{{ $detail->e_code }}</td>
                <td>
                    {{ $detail->pat_name }} / {{ $detail->doc_name }} / {{ $detail->cus_name }}
                </td>
                <td>
                    {{ $comment->rate }}
                </td>
                <td>{{ $comment->message }}</td>
                <td>{{ $comment->member->name ?? '' }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>