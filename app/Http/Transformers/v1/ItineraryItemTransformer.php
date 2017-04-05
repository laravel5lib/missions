<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\Transport;
use App\Models\v1\ItineraryItem;
use League\Fractal\TransformerAbstract;

class ItineraryItemTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'attachment'
    ];

    public function transform(ItineraryItem $item)
    {
        return [
            'id'           => $item->id,
            'title'        => $item->title,
            'description'  => $item->description,
            'type'         => $item->type,
            'occurs_at'    => $item->occurs_at->toDateTimeString(),
            'updated_at'   => $item->updated_at->toDateTimeString(),
            'created_at'   => $item->created_at->toDateTimeString(),
            'deleted_at'   => $item->deleted_at ? $item->deleted_at->toDateTimeString() : null
        ];
    }

    public function includeAttachment(ItineraryItem $item)
    {
        $attachment = $item->attachment;

        if ($attachment instanceOf Transport) {
            return $this->item($attachment, new TransportTransformer);
        }
    }
}