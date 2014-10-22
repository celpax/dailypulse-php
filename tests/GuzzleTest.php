<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 20/10/14
 * Time: 15:17
 */

namespace Celpax\Dailypulse\Test;

use GuzzleHttp\Client;


class GuzzleTest extends \PHPUnit_Framework_TestCase {

    public function testHttpGet(){
        $client = new Client();
        $response = $client->get('https://www.celpax.com');
        $this->assertEquals(200,$response->getStatusCode());
    }

} 