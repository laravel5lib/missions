<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{   
    use UuidForKey;

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
    protected $dates = ['created_at', 'updated_at', 'expires_at'];

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
     * Query builder to find all promocodes by type.
     * 
     * @param  $query
     * @param  string $type
     * @return mixed
     */
    public function scopeByType($query, $type)
    {
        return $query->where('promoteable_type', $type);
    }
    
    /**
     * Query builder to find all not expired promocodes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeFresh($query)
    {
        return $query->whereNull('expires_at')
                     ->orWhereDate(
                        'expires_at', '>=', Carbon::now()
                    );
    }
}
