<?php

namespace Tests\Feature\Api\Forms;

use Tests\TestCase;
use App\Models\v1\FormSubmission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewFormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_all_testimonies()
    {
        factory(FormSubmission::class, 'testimony', 2)->create();

        $response = $this->getJson('/api/testimonies');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id',
                            'user_id',
                            'type',
                            'content' => [
                                [
                                    'id',
                                    'label',
                                    'description',
                                    'input' => [
                                        'type',
                                        'placeholder',
                                        'options',
                                        'value'
                                    ],
                                    'helptext',
                                    'rules'
                                ]
                            ],
                            'created_at',
                            'updated_at',
                            'deleted_at'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        ['type' => 'testimonies']
                    ],
                    'meta' => ['total' => 2]
                ]);
    }
}
