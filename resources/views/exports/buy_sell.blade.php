<table></table>
<table>
    <thead>
        <tr>
            <th></th>
            <th style="text-align:left;font-weight:bold;">DATE-{{ date('d-m-Y') }}</th>
        </tr>
    </thead>
</table>
<table>
    <tr>
        <th></th>
        <th colspan="10" style="background-color:#e1e1e1;font-weight:bold;text-align:left;">BUY &#38; SALE</th>
    </tr>
    <thead>
        <tr>
            <th style="text-align:left;font-weight:bold;">#</th>
            <th style="text-align:left;font-weight:bold;">Exchange</th>
            <th style="text-align:left;font-weight:bold;">Date</th>
            <th style="text-align:left;font-weight:bold;">Buy Total Quantity</th>
            <th style="text-align:left;font-weight:bold;">Buy Average</th>
            <th style="text-align:left;font-weight:bold;">Buy Total Value</th>
            <th style="text-align:left;font-weight:bold;">Close Value</th>
            <th style="text-align:left;font-weight:bold;">Difference</th>
            <th style="text-align:left;font-weight:bold;">Profit &#38; Loss</th>
            <th style="text-align:left;font-weight:bold;">Profit &#38; Loss Percentage</th>
        </tr>
    </thead>
    <?php
    $buy_i = 1;
    $buy_profit_loss = 0;
    $buy_total_value = 0;
    $buy_total_value_all = 0;
    $buy_total_qty = 0;
    ?>
    @if (count($buy_sell_result))
    <tbody>
        @foreach ($buy_sell_result as $buy_sell)
        <?php
        $buy_total_value_all += str_replace(',', '', $buy_sell->total_value);
        $buy_total_qty += str_replace(',', '', $buy_sell->total_quantity);
        if ($buy_sell->profit_loss) {
            $buy_profit_loss += str_replace(',', '', $buy_sell->profit_loss);
            $buy_total_value += str_replace(',', '', $buy_sell->total_value);
        }
        ?>
        <tr>
            <td style="text-align:left;">{{ $buy_i++ }}</td>
            <td style="text-align:left;">{{ $buy_sell->getBuySell->exchange }}</td>
            <td style="text-align:left;">@php echo strtoupper(date('d-M-Y', strtotime($buy_sell->getBuySell->date))); @endphp</td>
            <td style="text-align:left;">{{ number_format($buy_sell->total_quantity, 2) }}</td>
            <td style="text-align:left;">{{ number_format($buy_sell->total_average, 2) }}</td>
            <td style="text-align:left;">{{ number_format($buy_sell->total_value, 2) }}</td>
            <td style="text-align:left;">@if($buy_sell->close || $buy_sell->close == 0){{ number_format($buy_sell->close, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($buy_sell->difference || $buy_sell->difference == 0){{ number_format($buy_sell->difference, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($buy_sell->profit_loss || $buy_sell->profit_loss == 0){{ number_format($buy_sell->profit_loss, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($buy_sell->profit_loss_per || $buy_sell->profit_loss_per == 0){{ number_format($buy_sell->profit_loss_per, 2) }}@else-@endif</td>
        </tr>
        @endforeach
    </tbody>
    @endif
    <tfoot>
        <tr>
            <?php $buy_profit_loss_per = ($buy_total_value == 0) ? 0 : (($buy_profit_loss / $buy_total_value) * 100); ?>
            <th colspan="3" style="font-weight:bold; text-align:right;">Total</th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($buy_total_qty, 2) }}</th>
            <th></th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($buy_total_value_all, 2) }}</th>
            <th colspan="2"></th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($buy_profit_loss, 2) }}</th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($buy_profit_loss_per,2) }}</th>
        </tr>
    </tfoot>
</table>