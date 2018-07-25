<?php

namespace App\Traits;

use App\Models\v1\Visa;
use App\Models\v1\Essay;
use App\Models\v1\Passport;

trait HasDocuments
{   
    /**
     * Add a document to the model.
     * 
     * @param  string $type
     * @param  array  $data
     * @return boolean
     */
    public function addDocument($type, array $data)
    {
        // TODO: check for missing array indexes
        // TODO: check for a non-existent method to match given document type
        
        $this->{$type}()->attach($data['document_id']);

        return $this->changeRequirementStatus('reviewing', $type, $data);
    }

    /**
     * Remove a document from the model.
     * 
     * @param  string $type
     * @param  string $id
     * @return boolean 
     */
    public function removeDocument($type, $id)
    {
        $this->{$type}()->detach($id);

        return $this->changeRequirementStatus('incomplete', $type);
    }

    /**
     * Get passports related to the model.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function passports()
    {
        return $this->morphedByMany(Passport::class, 'documentable', $this->getTableName());
    }

    /**
     * Get visas related to the model.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function visas()
    {
        return $this->morphedByMany(Visa::class, 'documentable', $this->getTableName());
    }

    /**
     * Get essays related to the model.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function essays()
    {
        return $this->morphedByMany(Essay::class, 'documentable', $this->getTableName());
    }

    // TODO: add all possible document types

    /**
     * Change the status of the requirement this document satisfies.
     * 
     * @param  string $status 
     * @param  string $type   
     * @param  array  $data 
     * @return boolean
     */
    private function changeRequirementStatus($status, $type, $data = [])
    {
        $requirementId = isset($data['requirement_id']) ? $data['requirement_id'] : null;
        
        $requirement = $this->requireables()
            ->where('document_type', $type)
            ->when($requirementId, function ($query) use ($requirementId) {
                return $query->where('id', $requirementId);
            })
            ->firstOrFail();

        return $this->requireables()->updateExistingPivot($requirement->id, ['status' => $status]);
    }

    /**
     * Get the database table name.
     * 
     * @return string
     */
    private function getTableName()
    {
        return class_basename($this).'_documents';
    }
}