<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use UuidForKey;

    protected $fillable = ['type', 'content', 'reservation_id'];

    protected $casts = ['content' => 'array'];
}
