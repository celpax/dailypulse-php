<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 20/10/14
 * Time: 16:21
 */

namespace Celpax\Dailypulse\Test;

use Celpax\Dailypulse\Client;


class APITest extends \PHPUnit_Framework_TestCase {


    const access_key_id="your-access-key-here";
    const secret_access_key="your-secret-access-key-here";


    private $dailyPulseClient;

    private $number_of_days;

    public function __construct(){
        $this->dailyPulseClient=new Client(self::access_key_id,self::secret_access_key);
        $this->number_of_days = 28;
    }

    public function testEchoService(){
        $response=$this->dailyPulseClient->echoMsg('hello');
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testEchoService ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;
        $this->assertEquals('hello', $obj->msg);
    }

    public function testCompanySites(){
        $response=$this->dailyPulseClient->getSites();
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testCompanySites ' . json_encode($obj, JSON_PRETTY_PRINT) .  PHP_EOL;
        $this->assertGreaterThan(0,count($obj));
        $site_id=$obj[0]->id;
        return $site_id;
    }

    /**
     * @depends testCompanySites
     */
    public function testSiteMoodKPI($site_id){
        $response=$this->dailyPulseClient->getMoodKPI($site_id);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testSiteMoodKPI ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;
        // NOTE: the site might not have a mood KPI yet. in that
        // Case red/green are NOT retunned.
        $red=$obj->red;
        $green=$obj->green;
        $dateStr=$obj->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertEquals(100-$red,$green);
    }

    /**
     *
     */
    public function testGlobalMoodKPI(){
        $response=$this->dailyPulseClient->getGlobalMoodKPI();
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testGlobalMoodKPI ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;
        // NOTE: the global company might not have a mood KPI yet. in that
        // Case red/green are NOT retunned.
        $red=$obj->red;
        $green=$obj->green;
        $dateStr=$obj->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertEquals(100-$red,$green);
    }

    /**
     * @depends testCompanySites
     */
    public function testPulsesPerTypicalDay($site_id){
        $response=$this->dailyPulseClient->getPulsesPerTypicalDay($site_id);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testPulsesPerTypicalDay ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: The site might not yet have a pulses per typical day KPI.
        // in that case it is NOT returned.

        $pulses=$obj->pulses;
        $dateStr=$obj->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertGreaterThan(0,$pulses);
    }

    /**
     *
     */
    public function testGlobalPulsesPerTypicalDay(){
        $response=$this->dailyPulseClient->getGlobalPulsesPerTypicalDay();
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testGlobalPulsesPerTypicalDay ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: The global company might not yet have a pulses per typical day KPI.
        // in that case it is NOT returned.

        $pulses=$obj->pulses;
        $dateStr=$obj->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertGreaterThan(0,$pulses);
    }

    /**
     * @depends testCompanySites
     */
    public function testHistoricalSiteMoodKPI($site_id){
        $response=$this->dailyPulseClient->getHistoricalMoodKPI($site_id, $this-> number_of_days);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testHistoricalSiteMoodKPI ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: the site might not have a mood KPI yet. in that
        // Case red/green are NOT retunned.
        $red=$obj[0]->red;
        $green=$obj[0]->green;
        $dateStr=$obj[0]->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertEquals(100-$red,$green);
    }

    /**
     *
     */
    public function testHistoricalGlobalMoodKPI(){
        $response=$this->dailyPulseClient->getHistoricalGlobalMoodKPI($this-> number_of_days);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testHistoricalGlobalMoodKPI ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: the site might not have a mood KPI yet. in that
        // Case red/green are NOT retunned.
        $red=$obj[0]->red;
        $green=$obj[0]->green;
        $dateStr=$obj[0]->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertEquals(100-$red,$green);
    }


    /**
     * @depends testCompanySites
     */
    public function testHistoricalPulsesPerTypicalDay($site_id){
        $response=$this->dailyPulseClient->getHistoricalPulsesPerTypicalDay($site_id, $this-> number_of_days);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testHistoricalPulsesPerTypicalDay ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: The site might not yet have a pulses per typical day KPI.
        // in that case it is NOT returned.

        $pulses=$obj[0]->pulses;
        $dateStr=$obj[0]->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertGreaterThan(0,$pulses);
    }

    /**
     *
     */
    public function testHistoricalGlobalPulsesPerTypicalDay(){
        $response=$this->dailyPulseClient->getHistoricalGlobalPulsesPerTypicalDay($this-> number_of_days);
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        echo 'testHistoricalGlobalPulsesPerTypicalDay ' . json_encode($obj, JSON_PRETTY_PRINT)  .  PHP_EOL;

        // NOTE: The site might not yet have a pulses per typical day KPI.
        // in that case it is NOT returned.

        $pulses=$obj[0]->pulses;
        $dateStr=$obj[0]->date;

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertGreaterThan(0,$pulses);
    }

}
