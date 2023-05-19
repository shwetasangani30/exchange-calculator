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
        <th colspan="10" style="background-color:#e1e1e1;font-weight:bold;text-align:left;">SALE &#38; BUY</th>
    </tr>
    <thead>
        <tr>
            <th style="text-align:left;font-weight:bold;">#</th>
            <th style="text-align:left;font-weight:bold;">Exchange</th>
            <th style="text-align:left;font-weight:bold;">Date</th>
            <th style="text-align:left;font-weight:bold;">Sell Total Quantity</th>
            <th style="text-align:left;font-weight:bold;">Sell Average</th>
            <th style="text-align:left;font-weight:bold;">Sell Total Value</th>
            <th style="text-align:left;font-weight:bold;">Close Value</th>
            <th style="text-align:left;font-weight:bold;">Difference</th>
            <th style="text-align:left;font-weight:bold;">Profit &#38; Loss</th>
            <th style="text-align:left;font-weight:bold;">Profit &#38; Loss Percentage</th>
        </tr>
    </thead>
    <?php
    $sell_i = 1;
    $sell_profit_loss = 0;
    $sell_total_invest = 0;
    $sell_total_value_all = 0;
    $sell_total_qty = 0;
    ?>
    @if (count($sell_buy_result))
    <tbody>
        @foreach ($sell_buy_result as $sell_buy)
        <?php
        $sell_total_value_all += str_replace(',', '', $sell_buy->total_value);
        $sell_total_qty += str_replace(',', '', $sell_buy->total_quantity);
        if ($sell_buy->profit_loss) {
            $sell_profit_loss += str_replace(',', '', $sell_buy->profit_loss);
            $sell_total_invest += str_replace(',', '', $sell_buy->total_value);
        }
        ?>
        <tr>
            <td style="text-align:left;">{{ $sell_i++ }}</td>
            <td style="text-align:left;">{{ $sell_buy->getBuySell->exchange }}</td>
            <td style="text-align:left;">@php echo strtoupper(date('d-M-Y', strtotime($sell_buy->getBuySell->date))); @endphp</td>
            <td style="text-align:left;">{{ number_format($sell_buy->total_quantity, 2) }}</td>
            <td style="text-align:left;">{{ number_format($sell_buy->total_average, 2) }}</td>
            <td style="text-align:left;">{{ number_format($sell_buy->total_value, 2) }}</td>
            <td style="text-align:left;">@if($sell_buy->close || $sell_buy->close == 0){{ number_format($sell_buy->close, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($sell_buy->difference || $sell_buy->difference == 0){{ number_format($sell_buy->difference, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($sell_buy->profit_loss || $sell_buy->profit_loss == 0){{ number_format($sell_buy->profit_loss, 2) }}@else-@endif</td>
            <td style="text-align:left;">@if($sell_buy->profit_loss_per || $sell_buy->profit_loss_per == 0){{ number_format($sell_buy->profit_loss_per, 2) }}@else-@endif</td>
        </tr>
        @endforeach
    </tbody>
    @endif
    <tfoot>
        <tr>
            <?php $sell_profit_loss_per = ($sell_total_invest == 0) ? 0 : (($sell_profit_loss / $sell_total_invest) * 100); ?>
            <th colspan="3" style="font-weight:bold; text-align:right;">Total</th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($sell_total_qty, 2) }}</th>
            <th></th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($sell_total_value_all, 2) }}</th>
            <th colspan="2"></th>
            <th style="font-weight:bold; text-align:left;">{{ number_format($sell_profit_loss, 2) }}</th>
            <th style="font-weight:bold; text-align:left;">{{ number_format(($sell_profit_loss_per), 2) }}</th>
        </tr>
    </tfoot>
</table>