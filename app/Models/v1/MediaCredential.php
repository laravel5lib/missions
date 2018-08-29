<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;

class MediaCredential extends Credential
{
    use HasParentModel;

    public function getMorphClass()
    {
        return 'media_credentials';
    }
}