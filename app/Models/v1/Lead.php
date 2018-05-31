<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Events\LeadCreated;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['category_id', 'content', 'created_at'];

    protected $casts = ['content' => 'array'];

    protected $dates = ['created_at', 'updated_at'];

    protected $dispatchesEvents = [
        'created' => LeadCreated::class
    ];

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }
}
