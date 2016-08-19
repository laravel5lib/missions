<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes, Filterable, UuidForKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'donations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'description', 'message', 'anonymous', 'currency',
        'payment_type', 'donor_id', 'designation_id', 'designation_type'
    ];

    /**
     * type : card
     * last_four : xxxx
     * card_type: visa
     * stripe_id : kdsfjalsdjfkajkd
     */

    /**
     * type : check
     * check_number : 1234
     * scan_src : /img/scan.jpg
     * issue_date : 2016-01-02
     */

    /**
     * the attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the donation's designation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function designation()
    {
        return $this->morphTo(); // Fundraiser, Cause, Campaign
    }

    /**
     * Get the donation's donor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

}
