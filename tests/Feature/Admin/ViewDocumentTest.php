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
use App\Models\v1\MinorRelease;
use App\Models\v1\MedicalRelease;
use App\Models\v1\MediaCredential;
use App\Models\v1\AirportPreference;
use App\Models\v1\MedicalCredential;
use App\Models\v1\ArrivalDesignation;
use App\Models\v1\InfluencerApplication;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewDocumentTest extends TestCase
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

    protected $forms = [
        'airport-preferences'     => AirportPreference::class,
        'arrival-designations'    => ArrivalDesignation::class,
        // 'minor-releases'          => MinorRelease::class
    ];

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        foreach($this->documents as $key => $value) {
            $this->user->givePermissionTo(Permission::create(['name' => 'view_'.str_replace('-', '_', $key)]));
        }

        foreach($this->forms as $key => $value) {
            $this->user->givePermissionTo(Permission::create(['name' => 'view_'.str_replace('-', '_', $key)]));
        }
    }

    /** @test */
    public function admin_with_permission_can_view_documents()
    {
        $this->actingAs($this->user);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create();

            $this->get("admin/records/{$key}/{$document->id}")
                 ->assertOk()
                 ->assertViewIs('admin.records.show');
        }
    }

    /** @test */
    public function admin_with_permission_can_view_forms()
    {
        $this->actingAs($this->user);

        foreach($this->forms as $key => $value) {
            $form = factory($value)->create();

            $this->get("admin/records/{$key}/{$form->id}")
                 ->assertOk()
                 ->assertViewIs('admin.records.show');
        }
    }

    /** @test */
    public function admin_without_permission_cannot_view_documents()
    {
        foreach($this->documents as $key => $value) {
            $this->user->revokePermissionTo('view_'.str_replace('-', '_', $key));
        }

        $this->actingAs($this->user);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create();

            $this->get("admin/records/{$key}/{$document->id}")
                 ->assertStatus(403);
        }
    }

    /** @test */
    public function admin_without_permission_cannot_view_forms()
    {
        foreach($this->forms as $key => $value) {
            $this->user->revokePermissionTo('view_'.str_replace('-', '_', $key));
        }

        $this->actingAs($this->user);

        foreach($this->forms as $key => $value) {
            $document = factory($value)->create();

            $this->get("admin/records/{$key}/{$document->id}")
                 ->assertStatus(403);
        }
    }
}
