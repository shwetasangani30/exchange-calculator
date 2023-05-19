<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuySellResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'buy_sell_result';

    public function getBuySell(): HasOne
    {
        return $this->hasOne(BuySell::class, 'id', 'buy_sell_id');
    }

    public function getBuy(): HasOne
    {
        return $this->hasOne(BuySell::class, 'id', 'buy_sell_id')->where('is_buy', 0)->where('status', 1);
    }

    public function getSell(): HasOne
    {
        return $this->hasOne(BuySell::class, 'id', 'buy_sell_id')->where('is_buy', 1)->where('status', 1);
    }
}
