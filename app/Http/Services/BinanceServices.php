<?php

namespace App\Http\Services;

use App\Models\OrderBook;
use GuzzleHttp\Client;
class BinanceServices 
{

    private $base = "https://api.binance.com";
    private $title = "binance";
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->base,
        ]);
    }
    function GetTicker($currency = "USDT"){
        $symbol = "BTC".$currency;
        $response = $this->client->request("GET","/api/v3/ticker/price",[
            "query"=>[
                "symbol"=>$symbol,
            ],
        ]);
        $body = json_decode($response->getBody());
        return $body;
    }
    function Get24HTicker($currency = "USDT"){
        $symbol = "BTC".$currency;
        $response = $this->client->request("GET","/api/v3/ticker/24hr",[
            "query"=>[
                "symbol"=>$symbol,
            ],
        ]);
        $body = json_decode($response->getBody());
        return $body;
    }

    function GetTickerBook($currency = "USDT"){
        $symbol = "BTC".$currency;
        $response = $this->client->request("GET","/api/v3/ticker/bookTicker",[
            "query"=>[
                "symbol"=>$symbol,
            ],
        ]);
        $body = json_decode($response->getBody());
        return $body;
        
    }

    function GetBTC($currency = "USDT", $qty= 10){
        $symbol = "BTC".$currency;
        $response = $this->client->request("GET","/api/v3/depth",[
            "query"=>[
                "symbol"=>$symbol,
                "limit"=>$qty,
            ],
        ]);
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

