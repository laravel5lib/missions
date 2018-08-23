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
                return new PassportResource($this);
                break;

            case 'visas':
                return new VisaResource($this);
                break;
            
            case 'essays':
                return new EssayResource($this);
                break;

            case 'influencer-applications':
                return new EssayResource($this);
                break;

            case 'referrals':
                return new ReferralResource($this);
                break;

            case 'medical-releases':
                return new MedicalReleaseResource($this);
                break;

            case 'medical-credentials':
                return new CredentialResource($this);
                break;

            case 'media-credentials':
                return new CredentialResource($this);
                break;

            case 'airport-preferences':
                return new QuestionnaireResource($this);
                break;

            default:
                return [
                    'message' => 'Undefined or unrecognized document type'
                ];
                break;
        }
    }
}
