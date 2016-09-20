<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Upload;
use League\Fractal\TransformerAbstract;

class UploadTransformer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Upload $upload
     * @return array
     */
    public function transform(Upload $upload)
    {
        $upload->load('tagged');

        return [
            'id'         => $upload->id,
            'source'     => $upload->type == 'video' ? $upload->source : image($upload->source),
            'name'       => $upload->name,
            'type'       => $upload->type,
            'meta'       => $upload->meta,
            'created_at' => $upload->created_at->toDateTimeString(),
            'updated_at' => $upload->updated_at->toDateTimeString(),
            'tags'       => $upload->tagNames(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/uploads/' . $upload->id,
                ]
            ],
        ];
    }

}