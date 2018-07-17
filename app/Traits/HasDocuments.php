<?php

namespace App\Traits;

use App\Models\v1\Passport;

trait HasDocuments
{
    public function addDocument($type, array $data)
    {
        // TODO: check for missing array indexes
        // TODO: check for a non-existent method to match given document type
        
        return $this->{$type}()->attach($data['document_id']);
    }

    public function removeDocument($type, $id)
    {
        return $this->{$type}()->detach($id);
    }

    public function passports()
    {
        return $this->morphedByMany(Passport::class, 'documentable', $this->getTableName());
    }

    // TODO: add all possible document types
    
    private function getTableName()
    {
        return class_basename($this).'_documents';
    }
}