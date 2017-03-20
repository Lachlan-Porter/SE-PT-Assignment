<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Booking;
use App\Employee;
use App\Availability;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->username,
        'password' => $password ?: $password = bcrypt('secret'),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
    ];
});

$factory->define(App\Booking::class, function (Faker\Generator $faker) {
    return [
        'customer_id' => $faker->numberBetween(1,10),
        'booking_start_time' => \Carbon\Carbon::now(),
        'booking_end_time' => \Carbon\Carbon::now(),
    ];
});

$factory->define(App\Employee::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Availability::class, function (Faker\Generator $faker) {
    return [
		'employee_id' => 1,
		'day' => 'Monday',
		'start_time' => '9:00',
		'end_time' => '18:30'
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'password' => $password ?: $password = bcrypt('secret'),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
    ];
});

$factory->define(App\BusinessOwner::class, function (Faker\Generator $faker) {
    return [
		'business_name' => $faker->sentence,
		'owner_name' => $faker->name,
		'username' => $faker->userName,
		'password' => 'password',
		'address' => $faker->address,
		'phone' => '0400 000 000'
    ];
});