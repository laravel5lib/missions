<?php

namespace App\Models\v1;

use App\UuidForKey;
use Ramsey\Uuid\Uuid;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormSubmission extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable, SoftDeletes;

    protected $fillable = ['user_id', 'content', 'type'];

    /**
     * Attributes that should be mutated to date instances.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['content' => 'array'];

    /**
     * Get the form's owner/
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
