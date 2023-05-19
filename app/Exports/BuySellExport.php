<?php

namespace App\Exports;

use App\Models\BuySellResult;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BuySellExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.buy_sell', [
            'buy_sell_result' => BuySellResult::where('user_id', Auth::id())->with('getBuy')->whereHas('getBuy')->get()
        ]);
    }
}
