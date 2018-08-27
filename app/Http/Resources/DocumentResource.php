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
                $this->load(['upload']);
                return new PassportResource($this);
                break;

            case 'visas':
                $this->load(['upload']);
                return new VisaResource($this);
                break;
            
            case 'essays':
                $this->load(['uploads']);
                return new EssayResource($this);
                break;

            case 'influencer-applications':
                $this->load(['uploads']);
                return new EssayResource($this);
                break;

            case 'referrals':
                return new ReferralResource($this);
                break;

            case 'medical-releases':
                $this->load(['uploads', 'conditions', 'allergies']);
                return new MedicalReleaseResource($this);
                break;

            case 'medical-credentials':
                $this->load(['uploads']);
                return new CredentialResource($this);
                break;

            case 'media-credentials':
                $this->load(['uploads']);
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
