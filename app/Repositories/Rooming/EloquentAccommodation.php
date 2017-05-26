<?php

namespace App\Repositories\Rooming;

use App\Traits\Roomable;
use App\Models\v1\Accommodation as Model;
use App\Repositories\EloquentRepository;
use App\Repositories\Rooming\Interfaces\Accommodation;

class EloquentAccommodation extends EloquentRepository implements Accommodation
{
    use Roomable;

    protected $model;

    protected $attributes = [
        'name', 'address_one', 'address_two', 'city','state', 
        'zip', 'phone', 'fax', 'country_code', 'email', 'url', 
        'region_id', 'short_desc'
    ];

    function __construct(Model $model)
    {
        $this->model = $model;
    }

}