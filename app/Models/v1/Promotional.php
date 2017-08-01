<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotional extends Model
{
    use UuidForKey, Filterable, SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'expires_at', 'deleted_at'];

    public function setRewardAttribute($value)
    {
        $this->attributes['reward'] = $value*100;
    }

    public function getRewardInDollars()
    {
        return number_format($this->reward/100, 2);
    }

    public function promoteable()
    {
        return $this->morphTo();
    }

    public function promocodes()
    {
        return $this->hasMany(Promocode::class)->withTrashed();
    }

    public function scopeActive($query)
    {
        return $query->whereDate('expires_at', '>=', Carbon::now())
                     ->orWhereNull('expires_at');
    }

    public function scopeHasAffiliates($query, $type)
    {
        return $query->where('affiliates', $type);
    }
}
