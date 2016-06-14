<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'passports';

    protected $fillable = [
        'given_names', 'surname', 'number', 'issued_at',
        'expires_at', 'birth_country', 'citizenship',
        'scan_src', 'user_id'
    ];

    protected $dates = [
        'issued_at', 'expires_at', 'created_at', 'updated_at'
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

    /**
     * Get all the passport's reservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
