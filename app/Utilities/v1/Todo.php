<?php

namespace App\Utilities\v1;

class Todo {

    /**
     * Available todos
     *
     * @var array
     */
    protected static $todos = [
        '01' => 'Send Shirt',
        '02' => 'Send Launch Guide',
        '03' => 'Send Luggage Tag',
        '04' => 'In LGL',
    ];

    public static function all()
    {
        return static::$todos;
    }
}