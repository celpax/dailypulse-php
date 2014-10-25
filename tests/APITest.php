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

    public function __construct(){
        $this->dailyPulseClient=new Client(self::access_key_id,self::secret_access_key);
    }

    public function testEchoService(){
        $response=$this->dailyPulseClient->echoMsg('hello');
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        $this->assertEquals('hello', $obj['msg']);
    }

    public function testCompanySites(){
        $response=$this->dailyPulseClient->getSites();
        $this->assertFalse($response->isException());
        $this->assertEquals(200, $response->statusCode());
        $obj=$response->json();
        $this->assertGreaterThan(0,count($obj));
        $site_id=$obj[0]['id'];
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

        // NOTE: the site might not have a mood KPI yet. in that
        // Case red/green are NOT retunned.
        $red=$obj['red'];
        $green=$obj['green'];
        $dateStr=$obj['date'];

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

        // NOTE: The site might not yet have a pulses per typical day KPI.
        // in that case it is NOT returned.

        $pulses=$obj['pulses'];
        $dateStr=$obj['date'];

        // here is how to parse the date
        $date=\DateTime::createFromFormat("Y-m-d",$dateStr);

        $this->assertGreaterThan(0,$pulses);
    }

} 