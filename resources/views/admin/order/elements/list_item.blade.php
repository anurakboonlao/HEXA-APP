<tr>
    <td>
        {{ product_name($product) }}
        <input type="hidden" name="list_product_id[]" value="{{ $product->id }}">
        <input type="hidden" name="list_qty[]" value="{{ $params['qty'] }}">
        <input type="hidden" name="list_price_code[]" value="{{ $params['price_code'] }}">
        <input type="hidden" name="list_note[]" value="{{ $params['note'] }}">
    </td>
    <td align="right">
        {{ $params['price_code'] }}
    </td>
    <td align="right">
        {{ $params['qty'] }}
    </td>
    <td align="right">
        -
    </td>
    <td align="right">
        {{ $params['note'] }}
    </td>
    <td width="50">
        <a href="" class="btn btn-default btn-xs remove-item">
            <i class="fa fa-remove"></i>
        </a>
    </td>
</tr>