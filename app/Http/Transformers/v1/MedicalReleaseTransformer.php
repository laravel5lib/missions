<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\MedicalRelease;
use League\Fractal\TransformerAbstract;

class MedicalReleaseTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'reservations', 'notes', 'conditions', 'allergies', 'uploads'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param MedicalRelease $release
     * @return array
     */
    public function transform(MedicalRelease $release)
    {
        $array = [
            'id'                => $release->id,
            'user_id'           => $release->user_id,
            'name'              => $release->name,
            'ins_provider'      => $release->ins_provider,
            'ins_policy_no'     => $release->ins_policy_no,
            'is_risk'           => (bool) $release->is_risk,
            'has_conditions'    => (bool) $release->conditions->count(),
            'has_allergies'     => (bool) $release->allergies->count(),
            'emergency_contact' => $release->emergency_contact,
            'created_at'        => $release->created_at->toDateTimeString(),
            'updated_at'        => $release->updated_at->toDateTimeString(),
            'links'             => [
                [
                    'rel' => 'self',
                    'uri' => '/medical/releases/' . $release->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Reservations
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReservations(MedicalRelease $release)
    {
        $reservations = $release->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include User
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(MedicalRelease $release)
    {
        $user = $release->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Notes
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(MedicalRelease $release)
    {
        $notes = $release->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include conditions
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Collection
     */
    public function includeConditions(MedicalRelease $release)
    {
        $conditions = $release->conditions;

        return $this->collection($conditions, new MedicalConditionTransformer);
    }

    /**
     * Include allergies
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAllergies(MedicalRelease $release)
    {
        $allergies = $release->allergies;

        return $this->collection($allergies, new MedicalAllergyTransformer);
    }

    /**
     * Include the uploads associated with the medical release.
     *
     * @param MedicalRelease $release
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(MedicalRelease $release)
    {
        $uploads = $release->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }
}