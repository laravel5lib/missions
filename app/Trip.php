<?php
namespace App;

use App\Deposit;
use App\Models\v1\Fund;
use App\Models\v1\Deadline;
use App\ReservationFactory;
use App\Models\v1\Requirement;
use Illuminate\Support\Facades\DB;
use App\Models\v1\Trip as TripModel;

class Trip extends TripModel
{
    public static function getAll()
    {
        return static::withCount('reservations')->filter(request()->all());
    }

    public static function getById($id)
    {
        return static::withCount('reservations')->findOrFail($id);
    }

    /**
     * Syncronize all the trip's deadlines.
     *
     * @param $deadlines
     */
    public function syncDeadlines($deadlines)
    {
        if (! $deadlines) {
            return;
        }

        $ids = $this->deadlines()->pluck('id', 'id');

        foreach ($deadlines as $deadline) {
            if (! isset($deadline['id'])) {
                $deadline['id'] = null;
            }

            array_forget($ids, $deadline['id']);

            $this->deadlines()->updateOrCreate(['id' => $deadline['id']], $deadline);
        }

        if (! $ids->isEmpty()) {
            Deadline::destroy($ids);
        }
    }

    /**
     * Syncronize all the trip's requirements.
     *
     * @param $requirements
     */
    public function syncRequirements($requirements)
    {
        if (! $requirements) {
            return;
        }

        $ids = $this->requirements()->pluck('id', 'id');

        foreach ($requirements as $requirement) {
            if (! isset($requirement['id'])) {
                $requirement['id'] = null;
            }

            array_forget($ids, $requirement['id']);

            $this->requirements()->updateOrCreate(['id' => $requirement['id']], $requirement);
        }

        if (! $ids->isEmpty()) {
            Requirement::destroy($ids);
        }
    }

    /**
     * Syncronize all the trip's facilitators.
     *
     * @param $user_ids
     */
    public function syncFacilitators($user_ids = null)
    {
        if (is_null($user_ids)) {
            return;
        }

        $this->facilitators()->sync($user_ids);
    }

    /**
     * Check if trip is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        if (is_null($this->published_at)) {
            return false;
        }

        return $this->published_at <= Carbon::now() ? true : false;
    }

    public function updateSpots($number = -1)
    {
        $this->spots = $this->spots + $number;

        $this->save();
    }

    public function register($request)
    {
        return DB::transaction(function () use($request) {
            
            $request['companion_limit'] = $this->companion_limit;

            $reservation = $this->reservations()->create(
                ReservationFactory::make($request)
            );

            $fund = Fund::make($reservation);

            $reservation->process($request);
            
            if ($request->get('amount') > 0) {
                Deposit::create($fund, $request);
            }

            return $reservation;
        });
    }   
}