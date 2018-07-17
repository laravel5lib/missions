<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getFields($request->route('documentType'));
    }

    private function getFields($type) {
        switch ($type) {
            case 'passports':
                return [
                    'id' => $this->id,
                    'given_names' => $this->given_names,
                    'surname' => $this->surname,
                    'number' => $this->number,
                    'birth_country' => $this->birth_country,
                    'birth_country_name' => country($this->birth_country),
                    'citizenship' => $this->citizenship,
                    'citizenship_name' => country($this->citizenship),
                    'upload_id' => $this->upload_id,
                    'expires_at' => $this->expires_at->format('Y-m-d'),
                    'created_at' => $this->created_at->toDateTimeString(),
                    'updated_at' => $this->updated_at->toDateTimeString(),
                    'expired' => $this->expires_at->isPast() ? true : false,
                ];
                break;
            
            default:
                return [
                    'message' => 'Undefined or unrecognized document type'
                ];
                break;
        }
    }
}
