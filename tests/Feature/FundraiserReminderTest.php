<?php

use App\Models\v1\Reminder;
use App\Models\v1\Fundraiser;
use App\Mail\FundraiserReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FundraiserReminderTest extends TestCase
{
    /** @test */
    public function creates_a_reminder()
    {
        $fundraiser = factory(Fundraiser::class)->create();
        
        $response = $this->post('/api/fundraisers/' . $fundraiser->uuid . '/remind', [
           'email' => 'email@example.com'
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('reminders', [
            'email' => 'email@example.com',
            'remindable_id' => $fundraiser->uuid,
            'remindable_type' => 'fundraisers'
        ]);
    }

    /** @test */
    public function sends_a_reminder_email()
    {
        Mail::fake();

        $reminder = factory(Reminder::class)->create();

        $reminder->send();

        Mail::assertSent(FundraiserReminder::class, function ($mail) use ($reminder) {
            return $mail->fundraiser->id === $reminder->remindable_id &&
                   $mail->hasTo($reminder->email);
        });
    }
}
