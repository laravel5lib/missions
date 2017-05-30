<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use UuidForKey, SoftDeletes, Filterable;
    
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function plans()
    {
        return $this->morphedByMany(RoomingPlan::class, 'roomable');
    }

    public function accommodations()
    {
        return $this->morphedByMany(Accommodation::class, 'roomable');
    }

    public function occupants()
    {
        return $this->belongsToMany(Reservation::class, 'occupants')
                    ->withPivot('room_leader')
                    ->withTimestamps();
    }

    public function validateOccupants($occupants)
    {
        $occupants = is_array($occupants) ? $occupants : [$occupants];

        $occupants = Reservation::whereIn('id', $occupants)->get();

        $this->assertUniqueToRoom($occupants);
        $this->assertWithinOccupancyLimit($occupants);
        $this->assertSameGender($occupants);
        $this->assertMarriedOnly($occupants);
    }

    private function assertUniqueToRoom($occupants)
    {
        $currentOccupants = $this->occupants()->pluck('id')->all();

        collect($occupants)->each(function ($occupant) use ($currentOccupants) {
            if (in_array($occupant->id, $currentOccupants))
                throw new \Exception("$occupant->given_names $occupant->surname is already in this room.");
        });
    }

    private function assertWithinOccupancyLimit($occupants)
    {
        $limit = $this->type->rules()->occupancy_limit;

        if ($limit <= $this->occupants()->count())
            throw new \Exception('This room is full.');

        if ($limit < count($occupants))
            throw new \Exception("Can not add more than $limit occupants.");
    }

    private function assertSameGender($occupants)
    {
        if ($occupants->pluck('gender')->unique()->count() > 1)
            throw new \Exception('Occupants must be of the same gender.');

        $currentOccupant = $this->occupants()->first();

        if ( ! $this->type->rules()->same_gender || ! $currentOccupant)
            return true;

        $notMatching = collect($occupants)->reject(function ($occupant) use ($currentOccupant) {
            return $occupant->gender == $currentOccupant->gender;
        })->count();

        if ($notMatching > 0)
            throw new \Exception('Occupants must be of the same gender.');
    
    }

    private function assertMarriedOnly($occupants)
    {
        if ( ! $this->type->rules()->married_only)
            return true;

        $notMatching = collect($occupants)->reject(function ($occupant) {
            return $occupant->status == 'married';
        })->count();

        if ($notMatching > 0)
            throw new \Exception('Occupants must be married.');
    }
}
