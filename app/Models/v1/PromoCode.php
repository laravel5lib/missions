<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode extends Model
{   
    use UuidForKey, SoftDeletes;

    /**
     * The attributes that should not be mass assigned.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     * 
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'expires_at', 'deleted_at'];

    /**
     * Get the promoter of the promocode.
     * 
     * @return Illuminate\Database\Eloquent\BelongsTo
     */
    public function rewardable()
    {
        return $this->morphTo();
    }

    public function promotional()
    {
        return $this->belongsTo(Promotional::class);
    }

    /**
     * Query builder to find promocode using code.
     *
     * @param $query
     * @param $code
     * @return mixed
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
    
    /**
     * Query builder to find all not expired promocodes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeFresh($query)
    {
        return $query->whereHas('promotional', function($promotional) {
            return $promotional->whereNull('expires_at')
                     ->orWhereDate(
                        'expires_at', '>=', Carbon::now()
                    );
        });
    }
}
