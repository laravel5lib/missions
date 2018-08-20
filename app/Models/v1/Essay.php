<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\User;
use App\Models\v1\Upload;
use App\Traits\Manageable;
use App\Models\v1\Reservation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Essay extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

    protected $fillable = ['author_name', 'subject', 'content', 'user_id'];

    /**
     * Attributes that should mutated to date instances.
     *
     * @var [type]
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['content' => 'array'];

    /**
     * Get the essay's parent user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the essay's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }
}
