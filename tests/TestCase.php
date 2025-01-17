<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createGDPRAckedUser(): User {

        // Creates user
        $user = User::factory()->create();
        $this->actingAs($user)
             ->post('/gdpr-ack');

        return $user;
    }

    /**
     * @var string Hafas is weird and it's trip ids are shorter the first 9 days of the month.
     */
    private static $HAFAS_ID_DATE = 'jmY';

    /**
     * Check if the given Hafas Trip was correct. Can be used from several test functions.
     * Currently checking if the hafas tripId contains four pipe characters and if it contains the
     * date of the request. If the test runs between 23:45 and midnight, the stationboard response
     * may contain trains starting the next day. If the test runs after midnight it might contain
     * some trains that started the day before.
     *
     * Trips where the first station is a day before the requestDate can be even one day more earlier.
     * e.g. Train starts at 01.01. but out request is on the same train which departs on 02.01 at 00:01
     * at the second station -> in the trip is is still the 01.01.
     *
     * @return Boolean If all checks were resolved positively. Assertions to be made on the caller
     * side to provide a coherent amount of assertions.
     * @throws \Exception
     */
    public static function isCorrectHafasTrip($hafastrip, $requestDate): bool {
        $requestDateMinusMinusOneDay = (clone $requestDate)->sub(new \DateInterval('P2D'));
        $requestDateMinusOneDay      = (clone $requestDate)->sub(new \DateInterval('P1D'));
        $requestDatePlusOneDay       = (clone $requestDate)->add(new \DateInterval('P1D'));

        // All Hafas Trips should have four pipe characters
        $fourPipes = 4 == substr_count($hafastrip->tripId, '|');

        $rightDate = in_array(1, [
            substr_count($hafastrip->tripId, $requestDateMinusMinusOneDay->format(self::$HAFAS_ID_DATE)),
            substr_count($hafastrip->tripId, $requestDateMinusOneDay->format(self::$HAFAS_ID_DATE)),
            substr_count($hafastrip->tripId, $requestDate->format(self::$HAFAS_ID_DATE)),
            substr_count($hafastrip->tripId, $requestDatePlusOneDay->format(self::$HAFAS_ID_DATE))
        ]);

        $ret = $fourPipes && $rightDate;
        if (!$ret) {
            echo "The following Hafas Trip did not match our expectations:";
            dd($hafastrip);
        }
        return $ret;
    }

    public function acceptGDPR(User $user): void {
        $response = $this->actingAs($user)
                         ->post('/gdpr-ack');
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

}
