<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange;

use Lin\Exchange\Api\Account;
use Lin\Exchange\Api\Trader;
use Lin\Exchange\Api\Market;

class Exchanges
{
    protected $exchange='';
    protected $key='';
    protected $secret='';
    protected $extra='';
    protected $host='';
    
    protected $platform;
    
    protected $proxy=false;
    protected $timeout=60;
    
    protected $acount;
    protected $market;
    protected $trader;
    
    
    function __construct(string $exchange,string $key,string $secret,string $extra='',string $host=''){
        $this->exchange=$exchange;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
        
        if(stripos($extra,'http')===0) {
            $this->host=$extra;
            $this->extra='';
        }
    }
    
    function account(){
        $this->acount=new Account($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $this->acount->setProxy($this->proxy);
        $this->acount->setTimeOut($this->timeout);
        return $this->acount;
    }
    
    function market(){
        $this->market=new Market($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $this->market->setProxy($this->proxy);
        $this->market->setTimeOut($this->timeout);
        return $this->market;
    }
    
    function trader(){
        $this->trader=new Trader($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $this->trader->setProxy($this->proxy);
        $this->trader->setTimeOut($this->timeout);
        return $this->trader;
    }
    
    /**
     * Support native access
     * */
    public function getPlatform(string $type=''){
        if($this->trader!==null) return $this->trader->getPlatform($type);
        if($this->market!==null) return $this->market->getPlatform($type);
        if($this->acount!==null) return $this->acount->getPlatform($type);
        
        return $this->trader()->getPlatform($type);
    }
    
    /**
     * Local development sets the proxy
     * @param bool|array
     * $proxy=false Default
     * $proxy=true  Local proxy http://127.0.0.1:12333
     *
     * Manual proxy
     * $proxy=[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     * ]
     * */
    function setProxy($proxy=true){
        $this->proxy=$proxy;
    }
    
    /**
     * Set the request timeout to 60 seconds by default
     * */
    function setTimeOut($timeout=60){
        $this->timeout=$timeout;
    }
}