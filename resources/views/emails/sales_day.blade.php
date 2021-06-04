<html>
    <head>
        <meta charset="utf-8">
    </head>

    <body>
    <h3>({{ $receipt->shop_name }}) Daily Sales Report({{ Date('Y/m/d H:i:s') }})</h3>

    <h3> 1. Sales Data </h3>
    <table>
        <tr>
            <td align="left">Total Sales</td>
            <td align="right">¥{{ round($sales_data['total_sales'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Gross Total</td>
            <td align="right">¥{{ round($sales_data['gross_total'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Total GST({{ $sales_data['gst_pr'] }}%)</td>
            <td align="right">¥{{ round($sales_data['total_gst'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Guest</td>
            <td align="right">{{ $sales_data['guest'] }}</td>
        </tr>
        <tr>
            <td align="left">Cash Income</td>
            <td align="right">¥{{ round($sales_data['cash_income'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Cash Count</td>
            <td align="right">{{ $sales_data['cash_count'] }}</td>
        </tr>
        <tr>
            <td align="left">Card Total</td>
            <td align="right">¥{{ round($sales_data['card_total'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Card Count</td>
            <td align="right">{{ $sales_data['card_count'] }}</td>
        </tr>
        <tr>
            <td align="left">Refund</td>
            <td align="right">¥{{ round($sales_data['refund_total'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Discount</td>
            <td align="right">¥{{ round($sales_data['discount_total'], 0) }}</td>
        </tr>
        <tr>
            <td align="left">Tip Total</td>
            <td align="right">¥{{ round($sales_data['tip_total'], 0) }}</td>
        </tr>
    </table>

    <h3> 2. Card Sales Data </h3>
    @for($i=0;$i<count($card_type);$i++)
        <table>
            <tr>
                <td align="left">{{ $card_type[$i] }} Total</td>
                <td align="right">¥{{ round($card_sales_data[$card_type[$i] . '_Total'], 0) }}</td>
            </tr>
            <tr>
                <td align="left">{{ $card_type[$i] }} Tip</td>
                <td align="right">¥{{ round($card_sales_data[$card_type[$i] . '_Tip'], 2) }}</td>
            </tr>
            <tr>
                <td align="left">{{ $card_type[$i] }} Count</td>
                <td align="right">{{ $card_sales_data[$card_type[$i] . '_Count'] }}</td>
            </tr>
        </table>
    @endfor

    <h3> 3. Discounts </h3>
    <table>
        @foreach($order_pay as $ord_pay)
            @if($ord_pay->discount != 0)
            <tr>
                <td align="left">{{ substr($ord_pay->created_at, 11, 5) }}({{ $ord_pay->id }})</td>
                <td align="left">Reception</td>
                <td align="right">-¥{{ round($ord_pay->discount, 0) }}</td>
            </tr>
            @endif
        @endforeach
    </table>

    <h3> 4. Canceled Items </h3>
    <table>
        @for($i=0;$i<count($cancel_items);$i++)
            <tr>
                <td align="left">{{ substr($cancel_items[$i]['amend_time'], 11, 5) }}({{ $cancel_items[$i]['id'] }})</td>
                <td align="right">Reception</td>
                <td align="right">-¥{{ round($cancel_items[$i]['cancel_price'], 0) }}</td>
            </tr>
        @endfor
    </table>

    <h3> 5. Hour Sales Data </h3>
    <table>
        <tr>
            <td align="center"><b>Hour</b></td>
            <td align="center"><b>People</b></td>
            <td align="center"><b>Sales</b></td>
        </tr>
        @for($i=0;$i<24;$i++)
            <tr>
                <td align="right">{{ $i }}</td>
                <td align="right">{{ $hour_sales_data[$i]['people'] }}</td>
                <td align="right">¥{{ round($hour_sales_data[$i]['sales'], 0) }}</td>
            </tr>
        @endfor
    </table>

    <h3> 6. Category Sales Data </h3>
    <table>
        <tr>
            <td align="center"><b>Category</b></td>
            <td align="center"><b>Qty</b></td>
            <td align="center"><b>Sales</b></td>
        </tr>
        @for($i=0;$i<count($category_sales_data);$i++)
            <tr>
                <td align="left">{{ ($i+1).'.'.$category_sales_data[$i]['name'] }}</td>
                <td align="right">@if($category_sales_data[$i]['is_parent'] == 0) {{ $category_sales_data[$i]['qty'] }} @else {{ $category_sales_data[$i]['qty1'] }} @endif</td>
                <td align="right">@if($category_sales_data[$i]['is_parent'] == 0) ¥{{ round($category_sales_data[$i]['sales'], 0) }} @else ¥{{ round($category_sales_data[$i]['sales1'], 0) }} @endif</td>
            </tr>
        @endfor
    </table>

    <h3> 7. Item Sales Data </h3>
    <table>
        <tr>
            <td align="center"><b>Item Name</b></td>
            <td align="center"><b>Qty</b></td>
            <td align="center"><b>Sales</b></td>
        </tr>
        @for($i=0;$i<count($item_sales_data);$i++)
            <tr>
                <td align="left">{{ $item_sales_data[$i]['name'] }}</td>
                <td align="right">{{ $item_sales_data[$i]['qty'] }}</td>
                <td align="right">¥{{ round($item_sales_data[$i]['sales'], 0) }}</td>
            </tr>
        @endfor
    </table>

    <h3> 8. Hourly Item Ranking </h3>
    <table>
        <tr>
            <td align="center"><b>Item Name</b></td>
            <td align="center"><b>Item Total</b></td>
            <td align="center"><b>10</b></td>
            <td align="center"><b>11</b></td>
            <td align="center"><b>12</b></td>
            <td align="center"><b>13</b></td>
            <td align="center"><b>14</b></td>
            <td align="center"><b>15</b></td>
            <td align="center"><b>16</b></td>
            <td align="center"><b>17</b></td>
            <td align="center"><b>18</b></td>
            <td align="center"><b>19</b></td>
            <td align="center"><b>20</b></td>
            <td align="center"><b>21</b></td>
            <td align="center"><b>22</b></td>
            <td align="center"><b>23</b></td>
            <td align="center"><b>0</b></td>
        </tr>
        @for($i=0;$i<count($hourly_item_ranking);$i++)
            <tr>
                <td align="left">{{ $hourly_item_ranking[$i]['item_name'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['item_total'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['10'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['11'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['12'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['13'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['14'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['15'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['16'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['17'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['18'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['19'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['20'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['21'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['22'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['23'] }}</td>
                <td align="right">{{ $hourly_item_ranking[$i]['0'] }}</td>
            </tr>
        @endfor
    </table>

    <h3> 9. Hourly Cooktime Ranking </h3>
    <table>
        <tr>
            <td align="center"><b>Item Name</b></td>
            <td align="center"><b>Cooktime(AVG:min)</b></td>
            <td align="center"><b>10</b></td>
            <td align="center"><b>11</b></td>
            <td align="center"><b>12</b></td>
            <td align="center"><b>13</b></td>
            <td align="center"><b>14</b></td>
            <td align="center"><b>15</b></td>
            <td align="center"><b>16</b></td>
            <td align="center"><b>17</b></td>
            <td align="center"><b>18</b></td>
            <td align="center"><b>19</b></td>
            <td align="center"><b>20</b></td>
            <td align="center"><b>21</b></td>
            <td align="center"><b>22</b></td>
            <td align="center"><b>23</b></td>
            <td align="center"><b>0</b></td>
        </tr>
        @for($i=0;$i<count($hourly_cooktime_ranking);$i++)
            <tr>
                <td align="left">{{ $hourly_cooktime_ranking[$i]['item_name'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['cook_avg_time'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['10'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['11'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['12'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['13'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['14'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['15'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['16'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['17'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['18'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['19'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['20'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['21'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['22'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['23'] }}</td>
                <td align="right">{{ $hourly_cooktime_ranking[$i]['0'] }}</td>
            </tr>
        @endfor
    </table>

    <h3> 10. Feedbacks </h3>
    <table>
        <tr>
            <td align="center"><b>Time</b></td>
            <td align="center"><b>Table</b></td>
            <td align="center"><b>Name</b></td>
            <td align="center"><b>Rate</b></td>
            <td align="center"><b>Comment</b></td>
        </tr>
        @foreach($feedbacks as $feedback)
            <tr>
                <td align="left">{{ substr($feedback->time, 11, 5) }}</td>
                <td align="left">{{ $feedback->table_name }}</td>
                <td align="left">{{ $feedback->customer_name }}</td>
                <td align="left">{{ $feedback->review_type }}</td>
                <td align="left">{{ $feedback->review }}</td>
            </tr>
        @endforeach
    </table>

    </body>

    <style>
        table td {
            border: 1px solid #000000;
        }
    </style>

</html>

