<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\v1\Reservation;

class RedirectIfHasUpfrontRequirement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('dashboard/reservations/*')) {
            $reservation = Reservation::findOrFail($request->segment(3));

            $requirement = $reservation->requireables->filter( function ($requirement) {
                return $requirement->upfront && ($requirement->pivot->status == 'incomplete' || is_null($requirement->pivot->status));
            })->first();

            if ($requirement) {
                $request->session()->flash('alert', true);

                return redirect("dashboard/reservations/{$reservation->id}/requirements/{$requirement->id}");
            }

            return $next($request);
        }

        return $next($request);
    }
}
