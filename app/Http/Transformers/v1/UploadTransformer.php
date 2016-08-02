<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Upload;
use League\Fractal\TransformerAbstract;

class UploadTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Upload $upload
     * @return array
     */
    public function transform(Upload $upload)
    {
        return [
            'id'         => $upload->id,
            'source'     => image($upload->source),
            'name'       => $upload->name,
            'type'       => $upload->type,
            'size'       => $upload->size,
            'created_at' => $upload->created_at->toDateTimeString(),
            'updated_at' => $upload->updated_at->toDateTimeString(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/uploads/' . $upload->id,
                ]
            ],
        ];
    }

}