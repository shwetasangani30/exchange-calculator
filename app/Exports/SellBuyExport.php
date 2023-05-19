<?php

namespace App\Exports;

use App\Models\BuySellResult;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SellBuyExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.sell_buy', [
            'sell_buy_result' => BuySellResult::where('user_id', Auth::id())->with('getSell')->whereHas('getSell')->get()
        ]);
    }
}
