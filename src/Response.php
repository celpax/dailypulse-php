<?php


namespace Celpax\Dailypulse;

use GuzzleHttp\Psr7\Response as Psr7Response;
use GuzzleHttp\Exception\TransferException;

class Response {

    private $exception;
    private $response;

    public function __construct(Psr7Response $r=NULL, TransferException $e=NULL){
        if(isset($e)){
            $this->exception=$e;
            $this->response=$e->getResponse();
        }
        else {
            $this->response=$r;
        }
    }

    public function isException(){
        return isset($this->exception);
    }

    private function hasResponse(){
        return isset($this->response);
    }

    public function getExceptionMessage(){
        $msg="Unknown";
        if(isset($this->exception)){
            if($this->hasResponse()){
                $msg=$this->json()->message;
            }
            else $msg=$this->exception->getMessage();
        }
        return $msg;
    }

    public function statusCode(){
        if($this->hasResponse()) return $this->response->getStatusCode();
        else return 0;
    }

    public function json(){
        if($this->hasResponse())
            return json_decode($this->response->getBody());
        else return NULL;
    }

}
