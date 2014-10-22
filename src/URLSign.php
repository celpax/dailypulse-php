<?php

namespace Celpax\Dailypulse;


class URLSign {

    /**
     * Returns UTC YYYYMMDD date string
     */
    function getDate(){
        date_default_timezone_set ("UTC");
        return date('Ymd');
    }

    /**
     * Returns HMAC-SHA-512 time stamped token for the url based on the provided secret
     * @param $url
     * @param $secret
     * @return bool|string
     */
    public function sign($url,$secret){

        // decode base64
        $secret=base64_decode($secret,true);

        $signKey=hash_hmac("sha512",$this->getDate(),$secret,true);

        $signature=hash_hmac("sha512",$url,$signKey,true);

        return base64_encode($signature);
    }

} 