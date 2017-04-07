<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Booking;
use App\Http\Controllers\CustomerController;
use Carbon\Carbon;

class BookingsTest extends TestCase
{
	/**
     * Get the total duration of the booking
     *
     * @return void
     */
	public function testGetDurationOfBooking() {
		// Given booking exists
		// and duration is two hours
		$booking = new Booking([
			'start_time' => Carbon::now()->subHour(4),
			'end_time' => Carbon::now()->subHour(2),
		]);

		// Calculate duration of booking
		$duration = $booking->duration();

		// Return expected result in seconds
		$this->assertEquals(7200, $duration);
	}

	/**
     * Get the duration time of booking in a time format
     *
     * @return void
     */
	public function testGetDurationOfBookingInTimeFormatString() {
		// Given booking exists
		// and duration is two hours
		$booking = new Booking([
			'start_time' => Carbon::now()->subHour(4),
			'end_time' => Carbon::now()->subHour(2),
		]);

		// Calculate duration of booking
		$duration = $booking->duration(true);

		// Expect duration to be in a time string HH:MM
		$this->assertEquals('2:00', $duration);
	}
}
