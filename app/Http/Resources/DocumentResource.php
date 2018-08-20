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
                    'created_at' => $this->created_at->toIso8601String(),
                    'updated_at' => $this->updated_at->toIso8601String(),
                    'expired' => $this->expires_at->isPast() ? true : false,
                ];
                break;

            case 'visas':
                return [
                    'id' => $this->id,
                    'given_names' => $this->given_names,
                    'surname' => $this->surname,
                    'number' => $this->number,
                    'country' => $this->country_code,
                    'country_name' => country($this->country_code),
                    'upload_id' => $this->upload_id,
                    'issued_at' => $this->issued_at->format('Y-m-d'),
                    'expires_at' => $this->expires_at->format('Y-m-d'),
                    'created_at' => $this->created_at->toIso8601String(),
                    'updated_at' => $this->updated_at->toIso8601String(),
                    'expired' => $this->expires_at->isPast() ? true : false,
                ];
                break;
            
            case 'essays':
                return [
                    'id' => $this->id,
                    'author_name' => $this->author_name,
                    'subject' => $this->subject,
                    'content' => $this->content,
                    'created_at' => $this->created_at->toIso8601String(),
                    'updated_at' => $this->updated_at->toIso8601String()
                ];
                break;

            case 'influencer-applications':
                return [
                    'id' => $this->id,
                    'author_name' => $this->author_name,
                    'subject' => $this->subject,
                    'content' => $this->content,
                    'created_at' => $this->created_at->toIso8601String(),
                    'updated_at' => $this->updated_at->toIso8601String()
                ];
                break;

            case 'referrals':
                return [
                    'id' => $this->id,
                    'applicant_name' => $this->applicant_name,
                    'type' => $this->type,
                    'attention_to' => $this->attention_to,
                    'recipient_email' => $this->recipient_email,
                    'referrer' => $this->referrer,
                    'response' => $this->response,
                    'status' => $this->status,
                    'sent_at' => $this->sent_at ? $this->sent_at->toIso8601String() : null,
                    'responded_at' => $this->responded_at ? $this->responded_at->toIso8601String() : null,
                    'created_at' => $this->created_at->toIso8601String(),
                    'updated_at' => $this->updated_at->toIso8601String(),
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
