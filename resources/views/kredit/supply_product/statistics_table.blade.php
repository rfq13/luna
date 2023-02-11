@foreach ($supply_products as $sp)
    <tr>
        <td data-toggle="tooltip" data-placement="top"
            title="{{ date('d M, Y', strtotime($sp->created_at)) . ' pada ' . date('h:m', strtotime($sp->created_at)) }}">
            {{ date('d M, Y', strtotime($sp->created_at)) }}</td>
        <td>{{ $sp->supply->supplier->name }}</td>
        <td>
            <input type="text" name="harga_beli" hidden="" value="{{ $sp->harga_beli }}">
            <span class="ammount-box-2 bg-green"><i class="mdi mdi-coin"></i></span>Rp.
            {{ number_format($sp->harga_beli, 2, ',', '.') }}
        </td>
        <td class="percent-status"></td>
        <td>
            <a role="button" href="#" class="ammount-box-2 bg-secondary info-btn" data-container="body"
                data-toggle="popover" data-placement="left" data-content="">
                <i class="mdi mdi-information-outline"></i>
            </a>
        </td>
    </tr>
@endforeach
<tr hidden="">
    <td></td>
    <td></td>
    <td><input type="text" name="harga_beli" value="0"></td>
    <td class="percent-status"></td>
    <td></td>
</tr>
