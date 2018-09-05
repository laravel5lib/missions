<?php

namespace Tests\Feature\Dashboard;

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
        'minor-releases'          => MinorRelease::class
    ];

    /** @test */
    public function non_owner_cannot_view_documents()
    {
        $this->actingAs(factory(User::class)->create());

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create();

            $this->get("dashboard/records/{$key}/{$document->id}")->assertStatus(403);
        }
    }

    /** @test */
    public function non_owner_cannot_view_forms()
    {
        $this->actingAs(factory(User::class)->create());

        foreach($this->forms as $key => $value) {
            $form = factory($value)->create();

            $response = $this->get("dashboard/records/{$key}/{$form->id}");

            $response->assertStatus(403);
        }
    }

    /** @test */
    public function owner_can_view_documents()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create(['user_id' => $user->id]);

            $this->get("dashboard/records/{$key}/{$document->id}")
                 ->assertOk()
                 ->assertViewIs('dashboard.records.show');
        }
    }

    /** @test */
    public function owner_can_view_forms()
    {
        $user = factory(User::class)->create();
        $reservation = factory(Reservation::class)->create(['user_id' => $user->id]);
        $this->actingAs($user);

        foreach($this->forms as $key => $value) {
            $form = factory($value)->create(['reservation_id' => $reservation->id]);

            $this->get("dashboard/records/{$key}/{$form->id}")
                 ->assertOk()
                 ->assertViewIs('dashboard.records.show');
        }
    }

    /** @test */
    public function team_coordinator_can_view_documents()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $group = factory(Group::class)->create();
        $group->managers()->attach($user->id);
        $trip = factory(Trip::class)->create(['group_id' => $group]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        foreach($this->documents as $key => $value) {
            $document = factory($value)->create(['user_id' => $reservation->user_id]);

            $this->get("dashboard/records/{$key}/{$document->id}")
                 ->assertOk()
                 ->assertViewIs('dashboard.records.show');
        }
    }

    /** @test */
    public function team_coordinator_can_view_forms()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $group = factory(Group::class)->create();
        $group->managers()->attach($user->id);
        $trip = factory(Trip::class)->create(['group_id' => $group]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        foreach($this->forms as $key => $value) {
            $form = factory($value)->create(['reservation_id' => $reservation->id]);

            $this->get("dashboard/records/{$key}/{$form->id}")
                 ->assertOk()
                 ->assertViewIs('dashboard.records.show');
        }
    }
}
