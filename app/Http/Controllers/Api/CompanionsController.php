<?php

namespace App\Http\Controllers\Api;

use Ramsey\Uuid\Uuid;
use App\Http\Requests;
use App\Models\v1\Companion;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CompanionRequest;
use App\Http\Transformers\v1\ReservationTransformer;

class CompanionsController extends Controller
{
    private $reservation;
    private $companion;

    public function __construct(Reservation $reservation, Companion $companion)
    {
        $this->reservation = $reservation;
        $this->companion = $companion;
    }

    /**
     * Get all companions
     * 
     * @param  String $reservation_id
     * @return response
     */
    public function index($reservation_id)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        return $this->response->collection($reservation->companionReservations, new ReservationTransformer);
    }

    /**
     * Add a companion
     * 
     * @param  String $reservation_id
     * @return response
     */
    public function store($reservation_id, CompanionRequest $request)
    {
        $companion_id = $request->get('companion_reservation_id');

        $this->set_companions($reservation_id, $companion_id, $request->get('relationship'));

        return $this->response->created();
    }

    /**
     * Remove a companion from the group
     * 
     * @param  String $reservation_id
     * @param  String $companion_reservation_id
     * @return response
     */
    public function destroy($reservation_id)
    {
        $reservation = $this->reservation->findOrFail($reservation_id);

        $this->leaveGroup($reservation);

        return $this->response->noContent();
    }

    private function set_companions($reservation_id, $companion_id, $relationship)
    {
        $reservation = $this->reservation->with('companions')->find($reservation_id);
        $companion   = $this->reservation->with('companions')->find($companion_id);

        if ($reservation->companions->count() && $companion->companions->count())
        {
            $this->leaveGroup($reservation); // leave it's current group
            $this->joinGroup($reservation, $companion, $relationship); // join it's companion's group

        } elseif ( ! $reservation->companions->count() && ! $companion->companions->count())
        {
            $this->createGroup($reservation, $companion, $relationship); // create a new group

        } else
        {
            $reservation->companions->count() ? 
                $this->joinGroup($companion, $reservation, $relationship) : // add companion to reservation's group
                $this->joinGroup($reservation, $companion, $relationship); // add reservation to companion's group
        }
    }

    /**
     * Join a companion group.
     * 
     * @param  Collection $new_member
     * @param  Collection $existing_member
     * @param  String     $relationship
     * @return void
     */
    private function joinGroup($new_member, $existing_member, $relationship)
    {
        $group_key = $existing_member->companions->pluck('group_key')->first();

        $companions = $this->companion->where('group_key', $group_key)->get();

        $companions->each(function($companion) use($new_member, $existing_member, $relationship) {
            $this->companion->create([
                'reservation_id' => $companion->companion_id,
                'companion_id'   => $new_member->id,
                'relationship'   => ($companion->companion_id == $existing_member->id) ? $relationship : 'other',
                'group_key'      => $companion->group_key
            ]);
            $this->companion->create([
                'reservation_id' => $new_member->id,
                'companion_id'   => $companion->companion_id,
                'relationship'   => ($companion->companion_id == $existing_member->id) ? $relationship : 'other',
                'group_key'      => $companion->group_key
            ]);
        });
    }

    /**
     * Create a new companion group.
     * 
     * @param  Collection $reservation
     * @param  Collection $companion
     * @param  String $relationship
     * @return void
     */
    private function createGroup($reservation, $companion, $relationship)
    {
        $group_key = Uuid::uuid4();

        $this->companion->create([
            'reservation_id' => $reservation->id, 
            'companion_id' => $companion->id, 
            'group_key' => $group_key,
            'relationship' => $relationship
        ]);

        $this->companion->create([
            'reservation_id' => $companion->id, 
            'companion_id' => $reservation->id, 
            'group_key' => $group_key,
            'relationship' => $relationship
        ]);
    }

    /**
     * Leave a companion group.
     * 
     * @param  Collection $existing_member
     * @return void
     */
    private function leaveGroup($existing_member)
    {
        $group_key = $existing_member->companions->pluck('group_key')->first();

        $companions = $this->companion
                           ->where('group_key', $group_key)
                           ->whereNested(function ($query) use($existing_member) {
                                $query->where('reservation_id', '=', $existing_member->id)
                                      ->orWhere('companion_id', '=', $existing_member->id);
                            })
                           ->get();

        $companions->each(function($companion) {
            $this->companion->destroy($companion->id);
        });
    }
}
