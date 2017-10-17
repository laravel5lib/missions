<?php

use App\Models\v1\Representative;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepresentativeTest extends TestCase
{
    /** @test */
    public function it_returns_reps()
    {
        factory(Representative::class, 2)->create();

        $this->get('/api/representatives')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'email', 'phone', 'ext', 'created_at', 'updated_at'],
                ]
            ])
            ->assertJson([
                'meta' => [
                    'pagination' => [
                        'total' => 2
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_a_rep_by_id()
    {
        $rep = factory(Representative::class)->create([
            'name' => 'john doe'
        ]);

        $this->get('/api/representatives/' . $rep->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $rep->id,
                    'name' => 'John Doe'
                ]
            ]);
    }

    /** @test */
    public function it_creates_a_rep()
    {
        $rep = ['name' => 'John Doe', 'phone' => '1234567890', 'email' => 'john@example.com'];

        $this->post('/api/representatives', $rep)
            ->assertStatus(200)
            ->assertJson([
                'data' => $rep
            ]);

        $this->assertDatabaseHas('representatives', [
            'name' => 'john doe',
            'phone' => '1234567890',
            'email' => 'john@example.com'
        ]);
    }

    /** @test */
    public function it_updates_a_rep()
    {
        $representative = factory(Representative::class)->create([
            'name' => 'john doe', 'email' => 'john@example.com'
        ]);

        $this->put('/api/representatives/' . $representative->id, ['name' => 'john doe', 'email' => 'john@gmail.com'])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'John Doe',
                    'email' => 'john@gmail.com'
                ]
            ]);

        $this->assertDatabaseHas('representatives', [
            'name' => 'john doe',
            'email' => 'john@gmail.com'
        ]);
    }

    /** @test */
    public function it_deletes_a_rep()
    {
        $representative = factory(Representative::class)->create();

        $this->delete('/api/representatives/' . $representative->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('representatives', ['id' => $representative->id]);
    }
}
