<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Passport extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

    protected $fillable = [
        'given_names', 'surname', 'number',
        'expires_at', 'birth_country', 'citizenship',
        'upload_id', 'user_id', 'created_at', 'updated_at'
    ];

    protected $dates = [
        'expires_at', 'created_at', 'updated_at'
    ];

    /**
     * Get the passport's user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the passport's upload.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
