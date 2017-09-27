<?php

use App\Models\v1\User;
use App\Models\v1\MedicalRelease;

class MedicalReleaseTest extends BrowserKitTestCase
{
    /** @test */
    public function syncs_conditions()
    {
        $release = factory(MedicalRelease::class)->create([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ]);

        $release->syncConditions([
            [
                'name' => 'fake condition',
                'medication' => true,
                'diagnosed' => true
            ]
        ]);

        $this->assertEquals('fake condition', $release->conditions()->first()->name);
    }

     /** @test */
    public function syncs_allergies()
    {
        $release = factory(MedicalRelease::class)->create([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ]);

        $release->syncAllergies([
            [
                'name' => 'fake allergy',
                'medication' => true,
                'diagnosed' => true
            ]
        ]);

        $this->assertEquals('fake allergy', $release->allergies()->first()->name);
    }
}
