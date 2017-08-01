<?php

namespace App\Services;

use Dingo\Api\Routing\Helpers;

class TravelActivity
{

    use Helpers;
    
    public function create(array $data)
    {
        $data = collect($data);

        try {
            $activity = $this->api
                ->json($data->get('activity'))
                ->post('activities');

            if ($data->get('transport')) {
                $transport = $this->api
                    ->json($data->get('transport'))
                    ->post('transports');

                $passenger = $this->api
                    ->json($data->get('passenger'))
                    ->post('transports/'.$transport->id.'/passengers');

                $transportActivity = $this->api
                    ->json(['activities' => [$activity->id]])
                    ->post('transports/' . $transport->id . '/activities');
            }

            if ($data->get('hub')) {
                $hub = $this->api
                    ->json($data->get('hub'))
                    ->post('hubs');

                $hubActivity = $this->api
                    ->json(['activities' => [$activity->id]])
                    ->post('hubs/' . $hub->id . '/activities');
            }
        } catch (\Exception $e) {
            isset($transport) ? $transport->delete() : null;
            isset($passenger) ? $passenger->delete() : null;
            isset($hub) ? $hub->delete() : null;
            isset($activity) ? $activity->delete() : null;

            throw $e;
        }

        return $activity;
    }

    public function update(array $data)
    {
        $data = collect($data);

        try {
            $activity = $this->api
                ->json(array_filter($data->get('activity')))
                ->put('activities/'.$data->get('activity')['id']);

            if ($data->get('transport')) {
                $transport = $this->api
                    ->json(array_filter($data->get('transport')))
                    ->put('transports/'.$data->get('transport')['id']);
            }

            if ($data->get('hub')) {
                $hub = $this->api
                    ->json(array_filter($data->get('hub')))
                    ->put('hubs/'.$data->get('hub')['id']);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
