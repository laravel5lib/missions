<?php

/**
 * Generic Credit Card Donation Transaction
 */
$factory->define(App\Models\v1\Transaction::class, function (Faker\Generator $faker) {
    return [
        'fund_id' => $faker->uuid,
        'donor_id' => $faker->uuid,
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'anonymous' => $faker->boolean(25),
        'details' => [
            'type' => 'card',
            'last_four' => substr($faker->creditCardNumber, -4),
            'cardholder' => $faker->name,
            'brand' => $faker->creditCardType,
            'comment' => $faker->text($maxNbChars = 120, $indexSize = 2),
            'charge_id' => 'ch_' . str_random(30)
        ],
        'created_at' => $faker->dateTimeThisMonth,
        'updated_at' => $faker->dateTimeThisMonth
    ];
});

/**
 * Anonymous Credit Card Donation Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'check', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'anonymous' => true
    ]);
});

/**
 * Check Donation Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'check', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'details' => [
            'type' => 'check',
            'number' => $faker->randomDigitNotNull,
            'comment' => $faker->text($maxNbChars = 120, $indexSize = 2)
        ]
    ]);
});

/**
 * Cash Donation Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'cash', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'details' => [
            'type' => 'cash',
            'comment' => $faker->text($maxNbChars = 120, $indexSize = 2)
        ]
    ]);
});

/**
 * Transfer From Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_from', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'anonymous' => false,
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => -$faker->randomNumber(2),
        'details' => null
    ]);
});

/**
 * Transfer to Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_to', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'anonymous' => false,
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => $faker->randomNumber(2),
        'details' => null,
    ]);
});

/**
 * Credit Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'credit', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'anonymous' => false,
        'donor_id' => null,
        'type' => 'credit',
        'details' => [
            'reason' => $faker->text($maxNbChars = 120, $indexSize = 2)
        ]
    ]);
});

/**
 * Refund Transaction
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'refund', function (Faker\Generator $faker) use ($factory) {
    $transaction = $factory->raw(App\Models\v1\Transaction::class);

    return array_merge($transaction, [
        'anonymous' => false,
        'donor_id' => null,
        'type' => 'refund',
        'details' => [
            'refund_id' => 'rf_' . str_random(33),
            // 'transaction_id' => function (array $transaction) {
            //     return factory(App\Models\v1\Transaction::class)->create([
            //         'fund_id' => $transaction['fund_id']
            //     ])->id;
            // },
            'reason' => $faker->text($maxNbChars = 120, $indexSize = 2)
        ]
    ]);
});
