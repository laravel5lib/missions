<?php

namespace App\Traits;

use App\Models\v1\Tag;
use Spatie\Tags\HasTags as SpatieHasTags;

trait HasTags {

    use SpatieHasTags;

    public static function getTagClassName(): string
    {
        return Tag::class;
    }
}