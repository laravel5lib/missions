<?php

namespace App;

class ViewComponent
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function toHtml()
    {
        return $this->model->toArray();
    }

    /**
     * Dynamically get properties from the underlying resource.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->model->{$key};
    }
}