<tr>
    <td></td>
    <td>{{ $zoneMember->member->name }}</td>
    <td>{{ $zoneMember->member->username }}</td>
    <td>{{ $zoneMember->member->address }}</td>
    <td>
        <a href="" data-id="{{ $zoneMember->id }}" class="btn btn-warning btn-xs btn-remove-member">
            <i class="fa fa-remove"></i>
        </a>
    </td>
</tr>