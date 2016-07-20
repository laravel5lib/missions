<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
      'region_id', 'call_sign'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'published_at'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get all of the team's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function isPublished()
    {
        if ( ! is_null($this->published_at) and $this->published_at <= Carbon::now())
        {
            return true;
        }

        return false;
    }

    /**
     * Syncronize all the team's members.
     *
     * @param $members
     * @internal param $requirements
     */
    public function syncMembers($members)
    {
        if ( ! $members) return;

        $ids = $this->members()->lists('id', 'id');

        foreach($members as $member)
        {
            if( ! isset($member['id'])) $member['id'] = null;

            array_forget($ids, $member['id']);

            $this->members()->updateOrCreate(['id' => $member['id']], $member);
        }

        if( ! $ids->isEmpty()) TeamMember::destroy($ids);
    }
}
