<?php

namespace App\Models\v1\Medical;

use App\Models\v1\Reservation;
use App\Models\v1\User;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'medical_releases';

    protected $fillable = [
        'user_id', 'ins_provider', 'ins_policy_no', 'conditions',
        'allergies', 'is_risk', 'has_insurance', 'name'
    ];

    protected $hidden = [];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $touches = ['user'];

    protected $casts = [
        'conditions' => 'array',
        'allergies' => 'array'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = json_encode($value);
    }


}
