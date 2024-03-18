<style>
    th {
        font-weight: bold;
    }

</style>
<table>
    <thead>
        <tr>
            <th colspan="27" style="font-size:16px; font-style: italic;text-align:center; color:red">
                <b>Danh sách sản phẩm giao hàng thành công</b>
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="font-weight: bold;">STT</th>
            <th rowspan="2" style="font-weight: bold;">Mã</th>
            <th rowspan="2" style="font-weight: bold">Họ và tên</th>
            <th rowspan="2" style="font-weight: bold">Sale</th>
            <th rowspan="2" style="font-weight: bold">Size</th>
            <th rowspan="2" style="font-weight: bold">Giá</th>
            <th rowspan="2" style="font-weight: bold">Trạng thái</th>
            <th rowspan="2" style="font-weight: bold">Ngày mua</th>
            <th rowspan="2" style="font-weight: bold">Người mua</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index=>$item)
        @if ($item['step'] == 2)
                <tr>
                    <td>{{$index}}</td>
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
