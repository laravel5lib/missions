<?php

namespace App\Http\Transformers\v1;

use App\models\v1\Essay;
use League\Fractal\TransformerAbstract;

class EssayTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'reservations', 'notes', 'uploads'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Essay $essay
     * @return array
     */
    public function transform(Essay $essay)
    {
        return [
            'id'          => $essay->id,
            'user_id'     => $essay->user_id,
            'author_name' => $essay->author_name,
            'subject'     => $essay->subject,
            'content'     => $essay->content,
            'created_at'  => $essay->created_at->toDateTimeString(),
            'updated_at'  => $essay->updated_at->toDateTimeString()
        ];
    }

    /**
     * Include Reservations
     *
     * @param Essay $essay
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReservations(Essay $essay)
    {
        $reservations = $essay->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include User
     *
     * @param Essay $essay
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Essay $essay)
    {
        $user = $essay->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Notes
     *
     * @param Essay $essay
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(Essay $essay)
    {
        $notes = $essay->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include the uploads associated with the essay.
     *
     * @param Essay $essay
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(Essay $essay)
    {
        $uploads = $essay->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }
}
