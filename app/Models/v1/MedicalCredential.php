<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;

class MedicalCredential extends Credential
{
    use HasParentModel;

    public function getMorphClass()
    {
        return 'medical_credentials';
    }
}