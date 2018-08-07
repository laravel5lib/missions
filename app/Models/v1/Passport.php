<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Passport extends Model
{
    use Filterable, UuidForKey, InteractsWithReservations;
    
    protected $table = 'passports';

    protected $fillable = [
        'given_names', 'surname', 'number',
        'expires_at', 'birth_country', 'citizenship',
        'upload_id', 'user_id', 'created_at', 'updated_at'
    ];

    protected $dates = [
        'expires_at', 'created_at', 'updated_at'
    ];

    public $timestamps = true;

    /**
     * Get the passport's user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
