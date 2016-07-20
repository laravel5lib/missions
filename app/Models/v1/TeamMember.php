<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
      'team_id', 'reservation_id', 'role_id', 'group', 'leader', 'forms'
    ];

    protected $dates = [
      'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'forms' => 'array'
    ];

    /**
     * Set the member's forms.
     *
     * @param $value
     */
    public function setFormsAttribute($value)
    {
        $this->attributes['forms'] = json_encode($value);
    }

    /**
     * Get the team the member belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the assigned parent model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function assignable()
    {
        return $this->morphTo();
    }

    /**
     * Get the member's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all of the member's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
