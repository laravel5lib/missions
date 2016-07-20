<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'contacts';

    protected $fillable = [
        'user_id', 'suffix', 'first_name', 'last_name',
        'email', 'phone_one', 'phone_two', 'address_street',
        'address_city', 'address_state', 'address_zip',
        'country_code', 'emergency', 'relationship'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $touches = ['user'];

    public $timestamps = true;

    /**
     * Get the contact's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the contact's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
