<style>
    th {
        font-weight: bold;
    }

</style>
<table>
    <thead>
        <tr>
            <th colspan="27" style="font-size:16px; font-style: italic;text-align:center; color:red">
                <b>Danh sách sản phẩm đang chờ giao hàng</b>
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Mã</th>
            <th style="font-weight: bold">Họ và tên</th>
            <th style="font-weight: bold">Sale</th>
            <th style="font-weight: bold">Size</th>
            <th style="font-weight: bold">Giá</th>
            <th style="font-weight: bold">Trạng thái</th>
            <th style="font-weight: bold">Ngày mua</th>
            <th style="font-weight: bold">Người mua</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index=>$item)
        @if ($item['step'] === 1)
                <tr>
                    <td>{{$item['product_code']}}</td>
                    <td>{{$item['product_name']}}</td>
                    <td>{{$item['note']}}</td>
                    <td>{{$item['size'] }}</td>
                    <td>{{$item['product_price']}}</td>
                    <td>{{$item['step']}}</td>
                    <td>{{$item['created_at']}}</td>
                    <td>{{$item['staff']}}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
