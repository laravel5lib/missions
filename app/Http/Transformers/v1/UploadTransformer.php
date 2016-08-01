<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Upload;
use Illuminate\Support\Facades\Storage;
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
            'source'     => Storage::disk('s3')->url($upload->source),
            'name'       => $upload->name,
            'type'       => $upload->type,
            'size'       => Storage::disk('s3')->size($upload->source),
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