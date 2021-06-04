<table class="account_tb_ct">
    @if(count($order_dishes) > 0)
    @foreach($order_dishes as $order_dish)
        <tr>
            <td class="td1"><h6><span>{{ $order_dish->dish_name_en }}
                @foreach($order_dish->options as $option)
                    [{{ $option->option_name }}: {{ $option->item_name }}]
                @endforeach
            </span></h6></td>
            <td class="td2"><h6><span>{{ $order_dish->count }}</span></h6></td>
            <td class="td3"><h6><span>
                @if($order_dish->each_price)
                ¥{{ round($order_dish->each_price, 0) }}
                @endif</span></h6></td>
            <td class="td4"><h6><span>
                @if($order_dish->sub_total)
                ¥{{ round($order_dish->sub_total, 0) }}
                @endif
            </span></h6></td>
        </tr>
    @endforeach
    @else
        <tr>
            <td></td>
        </tr>
    @endif
</table>

<style>


</style>

<script>


</script>
