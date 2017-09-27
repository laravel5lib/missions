<?php

namespace App\Jobs;

use App\Models\v1\RoomType;
use App\Repositories\Rooming\Interfaces\Plan;

class ExportRoomingPlans extends Exporter
{
    public function data(array $request)
    {
        $plans = app()->make(Plan::class)
                      ->filter($request)
                      ->with('group', 'rooms.type')
                      ->getAll();

        return $plans;
    }

    public function columns($plan)
    {
        $columns = [
            'name' => $plan->name,
            'group' => $plan->group->name,
            'occupants' => $plan->occupantsCount()->total()
        ];

        foreach ($plan->roomsCount()->all() as $key => $value) {
            $columns[$key] = $value;
        }

        $columns['created_at']  = $plan->created_at->toDateTimeString();
        $columns['updated_at']  = $plan->updated_at->toDateTimeString();

        return $columns;
    }

    public function getFields()
    {
        if ($this->includeRoomCount()) {
            $roomTypes = $this->getRoomTypesInPlans();

            return $this->addRoomTypesToFields($roomTypes);
        }

        return $this->request->get('fields', []);
    }

    private function includeRoomCount()
    {
        return in_array('room_count', $this->request->get('fields'));
    }

    private function getRoomTypesInPlans()
    {
        return $this->getData()->pluck('rooms')->flatten()->pluck('type.name')->unique();
    }

    private function addRoomTypesToFields($roomTypes)
    {
        $fields = collect($this->request->get('fields'));

        return $fields->reject(function ($field) use ($roomTypes) {
                    return $field == 'room_count';
        })->merge($roomTypes)->all();
    }
}
