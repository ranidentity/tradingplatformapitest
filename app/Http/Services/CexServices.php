<?php

namespace App\Http\Services;
use GuzzleHttp\Client;

class CexServices 
{
    private $base = "https://cex.io";
    private $title = "cex";

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->base,
        ]);
    }
    function GetTicker($currency = "USDT"){
        $response = $this->client->request("GET","/api/ticker/BTC/".$currency);
        $body = json_decode($response->getBody());
        return $body;
    }

    function GetBTC($currency = "USDT"){
        $response = $this->client->request("GET","/api/order_book/BTC/".$currency);
        $body = json_decode($response->getBody());
        $list = [];
        foreach($body->bids as $ea){
            $list []= collect([
                "price"=>$ea[0],
                "quantity"=>$ea[1],
            ]);
        }
        $buy = collect([
            "platform"=>$this->title,
            "pair"=>"BTC_".$currency,
            "type"=>"buy",
            "list"=>$list,
        ]);
        $list = [];
        foreach($body->asks as $ea){
            $list []= collect([
                "price"=>$ea[0],
                "quantity"=>$ea[1],
            ]);
        }
        $ask = collect([
            "platform"=>$this->title,
            "pair"=>"BTC_".$currency,
            "type"=>"ask",
            "list"=>$list,
        ]);
        $result = collect();
        $result []= $buy;
        $result []= $ask;
        return $result;
    }
    
}

