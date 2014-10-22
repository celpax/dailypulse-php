<?php

namespace Celpax\Dailypulse\Test;

use Celpax\Dailypulse\URLSign;

class TestDigest extends \PHPUnit_Framework_TestCase{

    public function testDummy(){
        $this->assertEquals(1+1,2);
    }

    public function testBase64(){
        $text = "hello world";
        $b64text= base64_encode($text);
        $this->assertEquals($b64text,"aGVsbG8gd29ybGQ=");
    }

    public function testSHA512(){
        $text="hello world";
        $digest=hash("sha512",$text);
        $this->assertEquals($digest,"309ecc489c12d6eb4cc40f50c902f2b4d0ed77ee511a7c7a9bcd3ca86d4cd86f989dd35bc5ff499670da34255b45b0cfd830e81f605dcf7dc5542e93ae9cd76f");
    }

    public function testSignature(){
        $urlSign=new URLSign();
        $signature=$urlSign->sign("https://www.celpax.com",base64_encode("my super-secret"));
        $this->assertNotEmpty($signature);
    }
}

