<?php

namespace App\Traits;

use App\Models\v1\Requirement;
use Illuminate\Support\Facades\DB;

trait HasRequirements 
{
    /**
     * Get all the model's requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function requirements()
    {
        return $this->morphMany(Requirement::class, 'requester');
    }
    
    /**
     * Get all requirements assigned to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function requireables()
    {
        return $this->morphToMany(Requirement::class, 'requireable')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Add a new requirement to the model.
     *
     * @param array $requirement
     * @return void
     */
    public function addRequirement(array $requirement)
    {
        if (isset($requirement['requirement_id'])) {

            return $this->attachRequirementToModel($requirement['requirement_id']);
        }

        return $this->createNewRequirementAndAttachToModel($requirement);
    }

    /**
     * Create new requirement and attach it to the model
     *
     * @param array $requirement
     * @return void
     */
    public function createNewRequirementAndAttachToModel(array $data)
    {
        return DB::transaction(function() use($data) {

            $array = [
                'name' => isset($data['name']) ? $data['name'] : null,
                'short_desc' => isset($data['short_desc']) ? $data['short_desc'] : null,
                'document_type' => isset($data['document_type']) ? $data['document_type'] : null,
                'due_at' => isset($data['due_at']) ? $data['due_at'] : null,
                'rules' => isset($data['rules']) ? $data['rules'] : null,
                'upfront' => isset($data['upfront']) ? $data['upfront'] : null
            ];

            if (isset($array['upfront']) && $array['upfront']) {
                $array['due_at'] = null;
            }

            $requirement = $this->requirements()->create($array);
            
            $this->attachRequirementToModel($requirement->id);

            return $requirement;
        });
    }

    /**
     * Attach requirement to the model.
     *
     * @param string $requirementId
     * @return void
     */
    public function attachRequirementToModel($requirementId)
    {
        return $this->requireables()->syncWithoutDetaching($requirementId);
    }

    /**
     * Update a requirement for the model.
     * 
     * @param  string $id
     * @param  array  $data
     * @return Requirement
     */
    public function updateRequirement($id, array $data)
    {
        $requirement = $this->requireables()->findOrFail($id);

        if (isset($data['upfront']) && $data['upfront']) {
            $data['due_at'] = null;
        }

        $requirement->update($data);

        // update the status attribute on the pivot table
        if (array_key_exists('status', $data)) {
            $this->requireables()
                 ->updateExistingPivot(
                    $id, ['status' => $data['status']]
                 );
        }

        // need to reload the model to get updated changes to pivot
        return $this->requireables()->findOrFail($id);
    }
}