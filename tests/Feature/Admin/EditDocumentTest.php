<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Visa;
use App\Models\v1\Essay;
use App\Models\v1\Group;
use App\Models\v1\Passport;
use App\Models\v1\Reservation;
use App\Models\v1\MedicalRelease;
use App\Models\v1\MediaCredential;
use App\Models\v1\MedicalCredential;
use App\Models\v1\InfluencerApplication;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected $documents = [
        'passports'               => Passport::class,
        'visas'                   => Visa::class,
        'medical-releases'        => MedicalRelease::class,
        'essays'                  => Essay::class,
        'influencer-applications' => InfluencerApplication::class,
        'media-credentials'       => MediaCredential::class,
        'medical-credentials'     => MedicalCredential::class
    ];

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        foreach($this->documents as $key => $value) {
            $this->user->givePermissionTo(Permission::create(['name' => 'edit_'.str_replace('-', '_', $key)]));
        }
    }

    /** @test */
    public function admin_with_permission_can_edit_documents()
    {
        $this->actingAs($this->user);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create();

            $this->get("admin/records/{$key}/{$document->id}/edit")
                 ->assertOk()
                 ->assertViewIs('admin.records.edit');
        }
    }

    /** @test */
    public function admin_without_permission_cannot_edit_documents()
    {
        foreach($this->documents as $key => $value) {
            $this->user->revokePermissionTo('edit_'.str_replace('-', '_', $key));
        }

        $this->actingAs($this->user);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create();

            $this->get("admin/records/{$key}/{$document->id}/edit")
                 ->assertForbidden();
        }
    }
}
