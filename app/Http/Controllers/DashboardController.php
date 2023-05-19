<?php

namespace App\Http\Controllers;

use App\Models\BuySellResult;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $buy_sell_result = BuySellResult::where('user_id', $user_id)->with('getBuy')->whereHas('getBuy')->get();
        $sell_buy_result = BuySellResult::where('user_id', $user_id)->with('getSell')->whereHas('getSell')->get();

        $Gt_buy_value = 0;
        $Gt_sell_value = 0;
        $Gt_buy_profit_loss = 0;
        $Gt_sell_profit_loss = 0;
        if (count($buy_sell_result)) {
            foreach ($buy_sell_result as $key => $buysellresult) {
                $Gt_buy_value += $buysellresult->total_value;
                if ($buysellresult->profit_loss) {
                    $Gt_buy_profit_loss += $buysellresult->profit_loss;
                }
            }
        }

        if (count($sell_buy_result)) {
            foreach ($sell_buy_result as $key => $sellbuyresult) {
                $Gt_sell_value += $sellbuyresult->total_value;
                if ($sellbuyresult->profit_loss) {
                    $Gt_sell_profit_loss += $sellbuyresult->profit_loss;
                }
            }
        }

        $total_investment = $Gt_buy_value + $Gt_sell_value;
        $total_profit_and_loss_amount = $Gt_buy_profit_loss + $Gt_sell_profit_loss;
        $total_profit_loss_percentage = ($total_investment) ? ($total_profit_and_loss_amount / $total_investment) * 100 : 0;

        $data['total_investment'] = number_format($total_investment, 2);
        $data['total_profit_and_loss_amount'] = number_format($total_profit_and_loss_amount, 2);
        $data['total_profit_loss_percentage'] = $total_profit_loss_percentage  > 100 ? 100 : number_format($total_profit_loss_percentage, 2);

        $nextMonth = date('m', strtotime('first day of +1 month'));
        $currentMonth = date('m');
        $lastMonth = date('m', strtotime('first day of -1 month'));
        $currentYear = date('Y') - 1;

        $monthArr = [];
        $graphDataArr = [];
        for ($i = $currentMonth; $i <= 12; $i++) {
            $dateObj   = DateTime::createFromFormat('!m', $i);
            $monthName = $dateObj->format('M'); // March
            array_push($monthArr, $monthName);

            if ($i == 12) {
                $i = 0;
                $currentYear = $currentYear + 1;
            } else if ($i == $currentMonth - 1) {
                $total_profit_and_loss_amount_gr = $this->graphCalculation($i, $user_id, $currentYear);
                array_push($graphDataArr, $total_profit_and_loss_amount_gr);
                break;
            }

            $total_profit_and_loss_amount_gr = $this->graphCalculation($i, $user_id, $currentYear);
            array_push($graphDataArr, $total_profit_and_loss_amount_gr);
        }

        $data['months'] = $monthArr;
        $data['graphData'] = $graphDataArr;

        return view('dashboard', compact('data'));
    }

    private function graphCalculation($i, $user_id, $currentYear)
    {
        $Gt_buy_profit = 0;
        $Gt_sell_profit = 0;

        $buy_sell_result_month = BuySellResult::where('user_id', $user_id)->whereHas('getBuy', function ($query) use ($i, $currentYear) {
            $query->whereMonth('date', $i)->whereYear('date', $currentYear);
        })->get();
        if (count($buy_sell_result_month)) {
            foreach ($buy_sell_result_month as $buy_sell_month) {
                if ($buy_sell_month->profit_loss) {
                    $Gt_buy_profit += $buy_sell_month->profit_loss;
                }
            }
        }
        $sell_buy_result_month = BuySellResult::where('user_id', $user_id)->whereHas('getSell', function ($query) use ($i, $currentYear) {
            $query->whereMonth('date', $i)->whereYear('date', $currentYear);
        })->get();

        if (count($sell_buy_result_month)) {
            foreach ($sell_buy_result_month as $sell_buy_month) {
                if ($sell_buy_month->profit_loss) {
                    $Gt_sell_profit += $sell_buy_month->profit_loss;
                }
            }
        }

        $total_profit_and_loss_amount_gr = $Gt_buy_profit + $Gt_sell_profit;

        return $total_profit_and_loss_amount_gr;
    }
}
