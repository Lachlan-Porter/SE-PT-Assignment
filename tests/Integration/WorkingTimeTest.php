<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Customer;
use App\Employee;
use App\BusinessOwner;
use App\Booking;
use App\WorkingTime;

use Carbon\Carbon;

class WorkingTimeTest extends TestCase
{
    // Rollback database actions once test is complete with this trait
    use DatabaseTransactions;

    /**
     * Add working time for employee
     *
     * @return void
     */
    public function testWorkingTimeBelongsToOneEmployee()
    {
        // Given there is a working time
        $workingTime = factory(WorkingTime::class)->create();

        // Working time must have only one employee
        $this->assertEquals(1, count($workingTime->employee));
    }

    /**
     * Add working time for employee
     *
     * @return void
     */
    public function testEmployeeHasManyWorkingTimes()
    {
        // Given there is aan employee
        $employee = factory(Employee::class)->create();

        // and there are 20 working times from the employee
        $workingTimes = factory(WorkingTime::class, 20)->create([
            'employee_id' => $employee->id,
        ]);

        // Working time must have only one employee
        $this->assertEquals(20, count($employee->workingTimes));
    }

    /**
     * Add working time for employee
     *
     * @return void
     */
    public function testAddWorkingTimeForEmployee()
    {
    	// Create employee
    	$employee = factory(Employee::class)->create();

    	// Create working time data
    	// and add a two hour shift next week
    	$workingTimeData = [
    		'employee_id' => $employee->id,
    		'start_time' => Carbon::now()
    			->startOfDay()
    			->addHours(13)
    			// Format time to 24 hour HH:MM
    			->format('H:i'),
    		'end_time' => Carbon::now()
    			->startOfDay()
    			->addHours(15)
    			// Format time to 24 hour HH:MM
    			->format('H:i'),
    		'date' => Carbon::now()
                ->addMonth()
    			->addWeek()
    			->startOfWeek()
    			// Format to date string yyyy-mm-dd
    			->toDateString(),
    	];

    	// Send a POST request to /admin/roster with working time data
    	$response = $this->json('POST', '/admin/roster', $workingTimeData);

    	// Check for a session message
        $response->assertSessionHas('message', 'New working time has been added.');

        // Check if working time is in database
        $this->assertDatabaseHas('working_times', [
        	// Choose ID 1 since there must be only one working time in the table
        	'id' => 1
        ]);
    }

    /**
     * Test all fields that are required
     *
     * @return void
     */
    public function testAllFieldsThatAreRequired()
    {
    	// Send a POST request to /admin/roster with nothing
    	$response = $this->json('POST', '/admin/roster');

    	// Check if errors occured
        $response->assertJson([
        	'employee_id' => ['The employee id field is required.'],
        	'start_time' => ['The start time field is required.'],
        	'end_time' => ['The end time field is required.'],
        	'date' => ['The date field is required.'],
        ]);
    }

    /**
     * Start and end time fields must be in a time format
     *
     * @return void
     */
    public function testStartTimeAndEndTimeFieldsMustBeATimeFormat()
    {
    	// When time fields are not in time format
    	$workingTimeData = [
    		'start_time' => 'johndoe',
    		'end_time' => 'johndoe',
    	];

    	// Send a POST request to /admin/roster with nothing
    	$response = $this->json('POST', '/admin/roster', $workingTimeData);

    	// Find in JSON response for error
        $response->assertJsonFragment(['The start time field must be in the correct time format.']);
        $response->assertJsonFragment(['The end time field must be in the correct time format.']);
    }

    /**
     * If start time is later than end time then respond with an error
     *
     * @return void
     */
    public function testErrorIfStartTimeIsLaterThanEndTime()
    {
    	// Create working time data
    	// and add a one hour shift
    	$workingTimeData = [
    		'start_time' => Carbon::now()
    			->startOfDay()
    			->addHours(16)
    			->format('H:i'),
    		'end_time' => Carbon::now()
    			->startOfDay()
    			->addHours(15)
    			->format('H:i'),
    	];

    	// Send a POST request to /admin/roster with working time data
    	$response = $this->json('POST', '/admin/roster', $workingTimeData);

    	// Find in JSON response for error
        $response->assertJsonFragment([
        	'The start time must be a date before end time.'
        ]);
        $response->assertJsonFragment([
        	'The end time must be a date after start time.'
        ]);
    }

    /**
     * If working time is today then respond with an error
     *
     * @return void
     */
    public function testErrorIfWorkingTimeIsToday() {
        // Create working time data
        // and set date field as today
        $workingTimeData = [
            'date' => Carbon::now()
                ->toDateString(),
        ];

        // Send a POST request to /admin/roster with working time data
        $response = $this->json('POST', '/admin/roster', $workingTimeData);

        // Find in JSON response for error
        $response->assertJsonFragment([
            'The date must be a date after within the weeks of next month.'
        ]);
    }

    /**
     * Error if working time is not in the next month of weeks
     *
     * @return void
     */
    public function testErrorIfWorkingTimeIsBeforeNextMonthOfWeeks() {
    	// Create working time data
        // Get the start of next month and week
        // One day before
        $workingTimeData = [
            'date' => Carbon::now()
                ->addMonth()
                ->startOfMonth()
                ->startOfWeek()
                ->subDay()
                ->toDateString(),
        ];

    	// Send a POST request to /admin/roster with working time data
    	$response = $this->json('POST', '/admin/roster', $workingTimeData);

    	// Find in JSON response for error
        $response->assertJsonFragment([
        	'The date must be a date after within the weeks of next month.'
        ]);
    }

    /**
     * Error if working time is not in the next month of weeks
     *
     * @return void
     */
    public function testErrorIfWorkingTimeIsAfterNextMonthOfWeeks() {
        // Create working time data
        // Get the end of next month and week
        // One day after
        $workingTimeData = [
            'date' => Carbon::now()
                ->addMonth()
                ->endOfMonth()
                ->endOfWeek()
                ->addDay()
                ->toDateString(),
        ];

        // Send a POST request to /admin/roster with working time data
        $response = $this->json('POST', '/admin/roster', $workingTimeData);

        // Find in JSON response for error
        $response->assertJsonFragment([
            'The date must be a date after within the weeks of next month.'
        ]);
    }

    /**
     * If employee does not exist then respond with an error
     *
     * @return void
     */
    public function testErrorIfEmployeeDoesNotExist() {
    	// Create working time data
    	// and set employee ID to 1 (non-existant)
    	$workingTimeData = [
    		'employee_id' => 1
    	];

    	// Send a POST request to /admin/roster with working time data
    	$response = $this->json('POST', '/admin/roster', $workingTimeData);

    	// Find in JSON response for error
        $response->assertJsonFragment([
        	'Employee does not exist.'
        ]);
    }

    /**
     * Get the roster of working times for next month
     *
     * @return void
     */
    public function testGetRosterOfNextMonth() {
        // Create a working time at the start of next month
        factory(WorkingTime::class)->create([
            'date' => Carbon::now()
                ->addMonth()
                ->startOfMonth()
                ->toDateString(),
        ]);

        // Create a working time at the end of the month
        factory(WorkingTime::class)->create([
            'date' => Carbon::now()
                ->addMonth()
                ->endOfMonth()
                ->toDateString(),
        ]);

        // Assert that all 20 working times is shown
        $this->assertEquals(2, count(WorkingTime::getRoster()));
    }
}