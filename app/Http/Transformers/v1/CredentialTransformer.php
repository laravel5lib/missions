<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\User;
use App\Models\v1\Credential;
use League\Fractal\TransformerAbstract;
use App\Http\Transformers\v1\UserTransformer;

class CredentialTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'notes', 'uploads', 'holder'
    ];

    public function transform(Credential $credential)
    {
        return [
            'id'             => $credential->id,
            'type'           => $credential->type,
            'holder_id'      => $credential->holder_id,
            'holder_type'    => $credential->holder_type,
            'applicant_name' => $credential->applicant_name,
            'content'        => $credential->content,
            'expired_at'     => $credential->expired_at ? $credential->expired_at->toDateTimeString() : null,
            'created_at'     => $credential->created_at->toDateTimeString(),
            'updated_at'     => $credential->updated_at->toDateTimeString(),
            'deleted_at'     => $credential->deleted_at ? $credential->deleted_at->toDateTimeString() : null
        ];
    }

    /**
     * Include Notes
     *
     * @param Credential $credential
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(Credential $credential)
    {
        $notes = $credential->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include the uploads associated with the medical release.
     *
     * @param Credential $credential
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(Credential $credential)
    {
        $uploads = $credential->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }

    public function includeHolder(Credential $credential)
    {
        $holder = $credential->holder;

        if ($holder instanceOf User) {
            return $this->item($holder, new UserTransformer);
        }
    }
}