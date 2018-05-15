<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Price;
use App\Models\v1\Deadline;
use App\Models\v1\Requirement;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
    /** @test */
    public function user_registers_for_a_trip()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $response = $this->register($trip->id, $this->formData());
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'given_names' => 'John Smith',
                'surname' => 'Doe'
            ]
        ]);

        $reservation = $trip->reservations->first();

        $this->assertNotNull($reservation->fund->id);
        $this->assertNotEmpty($reservation->todos);
    }

    /** @test */
    public function requires_given_names_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['given_names']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('given_names', $response);
    }

    /** @test */
    public function requires_surname_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['surname']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('surname', $response);
    }

    /** @test */
    public function requires_gender_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['gender']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('gender', $response);
    }

    /** @test */
    public function requires_status_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['status']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('status', $response);
    }

    /** @test */
    public function requires_shirt_size_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['shirt_size']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('shirt_size', $response);
    }

    /** @test */
    public function requires_birthday_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['birthday']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('birthday', $response);
    }

    /** @test */
    public function requires_valid_birthday_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = $this->formData();
        $data['birthday'] = 'invalid';

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('birthday', $response);
    }

    /** @test */
    public function requires_user_id_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['user_id']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('user_id', $response);
    }

    /** @test */
    public function requires_address_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['address']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('address', $response);
    }

    /** @test */
    public function requires_zip_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['zip']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('zip', $response);
    }

    /** @test */
    public function requires_city_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['city']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('city', $response);
    }

    /** @test */
    public function requires_country_code_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['country_code']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('country_code', $response);
    }

    /** @test */
    public function requires_email_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['email']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('email', $response);
    }

    /** @test */
    public function requires_valid_email_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = $this->formData();
        $data['email'] = 'invalid';

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('email', $response);
    }

    /** @test */
    public function requires_phone_one_without_phone_two_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['phone_one', 'phone_two']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('phone_one', $response);
    }

    /** @test */
    public function requires_phone_two_without_phone_oneto_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['phone_two', 'phone_one']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('phone_two', $response);
    }

    /** @test */
    public function requires_donor_without_donor_id_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor', $response);
    }

    /** @test */
    public function requires_donor_first_name_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor.first_name']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor.first_name', $response);
    }

    /** @test */
    public function requires_donor_last_name_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor.last_name']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor.last_name', $response);
    }

    /** @test */
    public function requires_donor_email_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor.email']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor.email', $response);
    }

    /** @test */
    public function requires_donor_zip_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor.zip']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor.zip', $response);
    }

    /** @test */
    public function requires_donor_country_code_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['donor.country_code']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('donor.country_code', $response);
    }

    /** @test */
    public function requires_amount_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['amount']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('amount', $response);
    }

    /** @test */
    public function does_not_require_amount_if_no_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = $this->formData();
        $data['amount'] = 0;

        $response = $this->register($trip->id, $data);

        $response->assertStatus(200);
    }

    /** @test */
    public function requires_token_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['token']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('token', $response);
    }

    /** @test */
    public function does_not_require_token_if_no_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = $this->formData();
        $data['amount'] = 0;

        $response = $this->register($trip->id, $data);

        $response->assertStatus(200);
    }

    /** @test */
    public function requires_description_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['description']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('description', $response);
    }

    /** @test */
    public function requires_currency_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['currency']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('currency', $response);
    }

    /** @test */
    public function requires_payment_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['payment']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('payment', $response);
    }

    /** @test */
    public function requires_payment_type_if_has_deposit_to_register()
    {
        Passport::actingAs($this->user);
        
        $trip = $this->makeTrip();

        $data = array_except($this->formData(), ['payment.type']);

        $response = $this->register($trip->id, $data);

        $this->assertValidationError('payment.type', $response);
    }

    private function makeTrip()
    {
        $trip = factory(Trip::class)->create();
        $this->setupTripPricing($trip);
        $trip->requirements()->saveMany([
            factory(Requirement::class, 'passport')->make(),
            factory(Requirement::class, 'medical')->make(),
            factory(Requirement::class, 'testimony')->make()
        ]);

        return $trip;
    }

    private function register($tripId, $formData)
    {
        return $this->json('POST', "/api/trips/{$tripId}/register", $formData);
    }

    private function assertValidationError($field, $response)
    {
        $response->assertStatus(422);
        $this->assertArrayHasKey($field, $response->decodeResponseJson()['errors']);
    }

    private function formData()
    {
        return [
            'given_names'        => 'John Smith',
            'surname'            => 'Doe',
            'gender'             => 'male',
            'status'             => 'single',
            'shirt_size'         => 'M',
            'birthday'           => '1987-07-28',
            'user_id'            => $this->user->id,
            'address'            => '123 Some Place Rd.',
            'zip'                => '12345',
            'city'               => 'Some City',
            'country_code'       => 'us',
            'email'              => 'jsdoe@example.com',
            'phone_one'          => '1234567890',
            'phone_two'          => '1234567890',
            'donor'              => [
                'first_name'   => 'John',
                'last_name'    => 'Doe',
                'email'        => 'jsdoe@example.com',
                'zip'          => '12345',
                'country_code' => 'us',
                'account_id'   => $this->user->id,
                'account_type' => 'users',
            ],
            'amount'             => 100,
            'token'              => 'tok_visa',
            'description'        => 'Deposit for reservation.',
            'currency'           => 'USD',
            'payment'            => [
                'type' => 'card'
            ]
        ];
    }

    private function setupTripPricing($trip)
    {
        $costs = $this->costs();

        $this->generatePrice($trip, $costs['generalReg'], 2400, today()->addMonths(6)->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonths(9)->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addYear()->toDateTimeString()],
        ]);

        $this->generatePrice($trip, $costs['earlyReg'], 2200, today()->startOfYear()->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonth()->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addMonths(6)->toDateTimeString()],
        ]);

        $this->generatePrice($trip, $costs['deposit'], 100);
        $this->generatePrice($trip, $costs['standardRoom'], 0);
        $this->generatePrice($trip, $costs['flight'], 800);
        $this->generatePrice($trip, $costs['lateFee'], 200, today()->addYear()->toDateTimeString());
    }

    private function generatePrice($trip, $cost, $amount, $date = null, $payments = [])
    {
        $price = factory(Price::class)->create([
            'cost_id' => $cost->id,
            'model_id' => $trip->id, 
            'model_type' => 'trips', 
            'amount' => $amount,
            'active_at' => $date
        ]);
        
        if ($payments <> []) {

            foreach($payments as $payment) 
            {
                $price->payments()->create([
                    'price_id' => $price, 
                    'percentage_due' => $payment['percentage_due'],
                    'due_at' => $payment['due_at']
                ]);
            }

        }

        $trip->attachPriceToModel($price->id);

        return $price;
    }

    private function costs()
    {
        return [
            'generalReg'   => factory(Cost::class)->create(['name' => 'General Reg', 'type' => 'incremental']),
            'earlyReg'     => factory(Cost::class)->create(['name' => 'Early Reg', 'type' => 'incremental']),
            'standardRoom' => factory(Cost::class)->create(['name' => 'Standard Room', 'type' => 'optional']),
            'doubleRoom'   => factory(Cost::class)->create(['name' => 'Double Room', 'type' => 'optional']),
            'deposit'      => factory(Cost::class)->create(['name' => 'Deposit', 'type' => 'upfront']),
            'flight'       => factory(Cost::class)->create(['name' => 'Flight', 'type' => 'static']),
            'lateFee'      => factory(Cost::class)->create(['name' => 'Late Fee', 'type' => 'fee'])
        ];
    }
}
