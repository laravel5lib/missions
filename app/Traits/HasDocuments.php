<?php

namespace App\Traits;

use App\Models\v1\Passport;

trait HasDocuments
{
    public function addDocument(array $data)
    {
        // TODO: check for missing array indexes
        // TODO: check for a non-existent method to match given document type
        
        return $this->{$data['document_type']}()->attach($data['document_id']);
    }

    public function passports()
    {
        return $this->morphedByMany(Passport::class, 'documentable', class_basename($this).'_documents');
    }

    // TODO: add all possible document types
}