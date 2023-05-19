<?php

namespace App\Http\Controllers;

use App\Exports\BuySellExport;
use App\Models\BuySell;
use App\Models\BuySellResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class BuySellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buy_sell_result = BuySellResult::where('user_id', Auth::id())->with('getBuy')->whereHas('getBuy')->orderBy('created_at', 'desc')->get();
        return view('buysell.buy.index', compact('buy_sell_result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buysell.buy.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'exchange' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'numeric'],
            'average' => ['required', 'numeric'],
        ]);

        $is_buy = $request->input('is_buy');

        $quantity = str_replace(',', '', $request->input('quantity'));
        $average = str_replace(',', '', $request->input('average'));

        $val = $quantity * $average;
        $buy_sell = new BuySell();
        $buy_sell->user_id = Auth::id();
        $buy_sell->exchange = $request->input('exchange');
        $buy_sell->date = $request->input('date');
        $buy_sell->quantity = str_replace(',', '', number_format($quantity, 2));
        $buy_sell->average = str_replace(',', '', number_format($average, 2));
        $buy_sell->value = str_replace(',', '', number_format($val, 2));
        $buy_sell->is_buy = $is_buy;
        $buy_sell->save();

        return Redirect::route('buysell.index')->with('success', 'Buy value has been added.');
    }

    /**
     * Display the specified resource.
     * All information will display about merge entries
     */
    public function show(string $id) // Info
    {
        $buy_sell_result = BuySellResult::with('getBuySell')->where('buy_sell_id', $id)->first();

        if ($buy_sell_result) {
            $buy_sell = BuySell::whereIn('id', explode(',', $buy_sell_result->merged_ids))->get();

            $buy_sell_result->close = ($buy_sell_result->close || $buy_sell_result->close == 0) ? number_format($buy_sell_result->close, 2) : '-';
            $buy_sell_result->difference = ($buy_sell_result->difference || $buy_sell_result->difference == 0) ? number_format($buy_sell_result->difference, 2) : '-';
            $buy_sell_result->profit_loss = ($buy_sell_result->profit_loss || $buy_sell_result->profit_loss == 0) ? number_format($buy_sell_result->profit_loss, 2) : '-';
            $buy_sell_result->profit_loss_per = ($buy_sell_result->profit_loss_per || $buy_sell_result->profit_loss_per == 0) ? number_format($buy_sell_result->profit_loss_per, 2) : '-';

            return view('buysell.buy.info', compact('buy_sell_result', 'buy_sell'));
        } else {
            return Redirect::route('buysell.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Update close value of the row
     */
    public function edit(string $id) // Close
    {
        $buy_sell_result = BuySellResult::with('getBuy')->whereRaw("FIND_IN_SET(" . $id . ", merged_ids)")->first();
        return view('buysell.buy.edit', compact('buy_sell_result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => ['required', 'numeric'],
            'average' => ['required', 'numeric'],
        ]);

        $qty = str_replace(',', '', $request->input('quantity'));
        $avg = str_replace(',', '', $request->input('average'));
        $val = $qty * $avg;

        $buy_sell_exist = BuySell::where('id', $id)->where('status', 1)->first();
        $buy_sell = BuySell::where('id', $id)->where('status', 1)->first();

        if ($buy_sell) {
            $buy_sell->quantity = str_replace(',', '', number_format($qty, 2));
            $buy_sell->average = str_replace(',', '', number_format($avg, 2));
            $buy_sell->value = str_replace(',', '', number_format($val, 2));
            $buy_sell->save();

            $buy_sell_result = BuySellResult::whereRaw("FIND_IN_SET(" . $buy_sell->id . ", merged_ids)")->first();

            $total_quantity = ($buy_sell_result->total_quantity + $buy_sell->quantity) - $buy_sell_exist->quantity;
            $total_value = ($buy_sell_result->total_value + $buy_sell->value) - $buy_sell_exist->value;
            $average = ($buy_sell_result->average + $buy_sell->average) - $buy_sell_exist->average;
            $total_average = str_replace(',', '', number_format($total_value / $total_quantity, 2));

            $buy_sell_result->average = str_replace(',', '', number_format($average, 2));
            $buy_sell_result->total_quantity = str_replace(',', '', number_format($total_quantity, 2));
            $buy_sell_result->total_value = str_replace(',', '', number_format($total_value, 2));
            $buy_sell_result->total_average = $total_average;
            if ($buy_sell_result->is_close_added == 1) {
                $difference = $buy_sell_result->close - $total_average;
                $total_close_value = $buy_sell_result->close * $buy_sell_result->total_quantity;
                $buy_sell_result->difference = str_replace(',', '', (number_format(($difference), 2)));
                $buy_sell_result->profit_loss = str_replace(',', '', (number_format(($total_close_value - $buy_sell_result->total_value), 2)));
                $buy_sell_result->profit_loss_per = str_replace(',', '', (number_format((($buy_sell_result->profit_loss / $buy_sell_result->total_value) * 100), 2)));
            }
            $buy_sell_result->save();
            return Redirect::route('buysell.show', $buy_sell_result->buy_sell_id)->with('success', 'Data updated successfully');
        } else {
            return Redirect::route('buysell.index')->with('error', 'There is some problem in saving data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buy_sell = BuySell::where('id', $id)
            ->where('status', 1)
            ->first();
        if ($buy_sell) {
            $buy_sell->deleted_at = date('Y-m-d H:i:s');
            $buy_sell->save();

            $buy_sell_result = BuySellResult::whereRaw("FIND_IN_SET(" . $id . ", merged_ids)")->first();
            if ($buy_sell_result) {
                $explode_ids = explode(',', $buy_sell_result->merged_ids);
                if (count($explode_ids) > 1) {
                    $buy_sell_id = "";
                    $merged_ids = "";
                    $unset_key = "";
                    if (($key = array_search($id, $explode_ids)) !== false) {
                        $unset_key = $explode_ids[$key];
                        unset($explode_ids[$key]);
                    }

                    if ($unset_key && $unset_key == $buy_sell_result->buy_sell_id) {
                        $buy_sell_id = max($explode_ids);
                    }
                    $merged_ids = implode(',', $explode_ids);

                    $average = $buy_sell_result->average - $buy_sell->average;
                    $total_quantity  = $buy_sell_result->total_quantity - $buy_sell->quantity;
                    $total_value = $buy_sell_result->total_value - $buy_sell->value;
                    $total_average = str_replace(',', '', number_format($total_value / $total_quantity, 2));

                    $buy_sell_result->buy_sell_id = ($buy_sell_id) ? $buy_sell_id : $buy_sell_result->buy_sell_id;
                    $buy_sell_result->merged_ids = ($merged_ids) ? $merged_ids : $buy_sell_result->merged_ids;
                    $buy_sell_result->average = str_replace(',', '', number_format($average, 2));
                    $buy_sell_result->total_quantity = str_replace(',', '', number_format($total_quantity, 2));
                    $buy_sell_result->total_value = str_replace(',', '', number_format($total_value, 2));
                    $buy_sell_result->total_average = $total_average;
                    if ($buy_sell_result->is_close_added == 1) {
                        $difference = $buy_sell_result->close - $total_average;
                        $total_close_value = $buy_sell_result->close * $buy_sell_result->total_quantity;
                        $buy_sell_result->difference = str_replace(',', '', (number_format(($difference), 2)));
                        $buy_sell_result->profit_loss = str_replace(',', '', (number_format(($total_close_value - $buy_sell_result->total_value), 2)));
                        $buy_sell_result->profit_loss_per = str_replace(',', '', (number_format((($buy_sell_result->profit_loss / $buy_sell_result->total_value) * 100), 2)));
                    }
                } else {
                    $buy_sell_result->deleted_at = date('Y-m-d H:i:s');
                }
                $buy_sell_result->save();
                return Redirect::route('buysell.show', $buy_sell_result->buy_sell_id)->with('success', 'Deleted successfully');
            } else {
                return Redirect::route('buysell.index')->with('error', 'There is some problem in delete');
            }
        } else {
            return Redirect::route('buysell.index')->with('error', 'There is some problem in delete');
        }
    }

    /**
     * Sell Exchange
     */
    public function sell(Request $request)
    {
        $request->validate([
            'close_val' => ['required', 'numeric'],
        ]);

        $buy_sell_id = $request->input('buy_sell_id');
        $buy_sell_result = BuySellResult::whereRaw("FIND_IN_SET(" . $buy_sell_id . ", merged_ids)")->first();

        if ($buy_sell_result) {
            $explode_ids = explode(',', $buy_sell_result->merged_ids);
            $buy_sell_all = BuySell::whereIn('id', $explode_ids)
                ->where('is_buy', 0)
                ->get();

            if (count($buy_sell_all)) {
                foreach ($buy_sell_all as $value) {
                    $value->status = 0;
                    $value->save();
                }
                if ($buy_sell_result) {
                    /* is_buy = 0 then use below formula */
                    $close_value = str_replace(',', '', $request->input('close_val'));
                    $difference = $close_value - $buy_sell_result->total_average;
                    $total_close_value = $close_value * $buy_sell_result->total_quantity;
                    $profit_loss = $total_close_value - $buy_sell_result->total_value;
                    $profit_loss_per = number_format((($profit_loss / $buy_sell_result->total_value) * 100), 2);

                    $buy_sell_result->close = $close_value;
                    $buy_sell_result->difference = str_replace(',', '', (number_format($difference, 2)));
                    $buy_sell_result->profit_loss = str_replace(',', '', (number_format($profit_loss, 2)));
                    $buy_sell_result->profit_loss_per = str_replace(',', '', $profit_loss_per);
                    $buy_sell_result->save();

                    return Redirect::route('buysell.index')->with('success', 'Sell success');;
                } else {
                    return Redirect::route('buysell.index')->with('error', 'Something went wrong!');
                }
            } else {
                return Redirect::route('buysell.index')->with('error', 'No any sell or buy value found!');
            }
        } else {
            return Redirect::route('buysell.index')->with('error', 'No any sell or buy value found!');
        }

        return Redirect::route('buysell.index')->with('error', 'validation failed');
    }

    /**
     * add close value
     */
    public function sellClose(Request $request)
    {
        $request->validate([
            'close_val' => ['required', 'numeric'],
        ]);
        $buy_sell_id = $request->input('buy_sell_id');
        $user_id = Auth::id();
        $buy_sell = BuySell::find($buy_sell_id);

        if ($buy_sell) {
            $buy_sell_all = BuySell::where('user_id', $user_id)->where('exchange', $buy_sell->exchange)
                ->where('is_buy', 0)
                ->get();

            if (count($buy_sell_all)) {
                $buy_sell_result = BuySellResult::where('buy_sell_id', $buy_sell_id)->first();
                if ($buy_sell_result) {
                    $close_value = str_replace(',', '', $request->input('close_val'));
                    $difference = $close_value - $buy_sell_result->total_average;
                    $total_close_value = $close_value * $buy_sell_result->total_quantity;
                    $profit_loss = $total_close_value - $buy_sell_result->total_value;
                    $profit_loss_per = ($profit_loss / $buy_sell_result->total_value) * 100;

                    $buy_sell_result->close = str_replace(',', '', (number_format($close_value, 2)));
                    $buy_sell_result->difference = str_replace(',', '', (number_format($difference, 2)));
                    $buy_sell_result->profit_loss = str_replace(',', '', (number_format($profit_loss, 2)));
                    $buy_sell_result->profit_loss_per = str_replace(',', '', (number_format($profit_loss_per, 2)));
                    $buy_sell_result->is_close_added = 1;
                    $buy_sell_result->save();
                    return Redirect::route('buysell.index')->with('success', 'Close value added successfully.');
                } else {
                    return Redirect::route('buysell.index')->with('error', 'Something went wrong!');
                }
            } else {
                return Redirect::route('buysell.index')->with('error', 'No any sell or buy value found!');
            }
        } else {
            return Redirect::route('buysell.index')->with('error', 'No any sell or buy value found!');
        }

        return Redirect::route('buysell.index')->with('error', 'validation failed');
    }

    public function exportToExcel()
    {
        return Excel::download(new BuySellExport(), 'buyandsell.xlsx');
    }
}
