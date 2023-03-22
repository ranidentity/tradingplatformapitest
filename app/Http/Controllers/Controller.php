<?php

namespace App\Http\Controllers;

use App\Http\Services\BinanceServices;
use App\Http\Services\CexServices;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Decimal;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $binanceServices;
    protected $cexServices;
    public function __construct()
    {
        $this->binanceServices = new BinanceServices();
        $this->cexServices = new CexServices();
    }

    public function GetTicker(Request $request){
        switch($request->query("platform")){
            case "cex":
                return $this->cexServices->GetTicker();
            case "binance":
                return $this->binanceServices->GetTicker(); 
            default:
                return "no data";        
        } 
    }
    public function Get24HTicker(Request $request){
        switch($request->query("platform")){
            case "cex":
                return $this->cexServices->GetTicker();
            case "binance":
                return $this->binanceServices->Get24HTicker(); 
            default:
                return "no data";        
        } 
    }
    public function GetBestPrice(){
        $binance = $this->binanceServices->GetTickerBook(); 
        $cex = $this->cexServices->GetTicker(); 
        $result = null;
        $binance_decimal = round($binance->askPrice * 100000000);
        $cex_decimal = round($cex->ask * 100000000);
        if($binance_decimal <= $cex_decimal){ // assumption: similar then take binance
            $result = collect([
                "platform" => "binance",
                "asking_price"=> $binance->askPrice,
                "alternatives"=>[collect([
                    "platform"=>"cex",
                    "asking_price"=> number_format($cex->ask,8,'.',''),
                ])],
            ]);
        }else{
            $result = collect([
                "platform" => "cex",
                "asking_price"=> number_format($cex->ask,8,'.',''),
                "alternatives"=>[collect([
                    "platform"=>"binance",
                    "asking_price"=> $binance->askPrice,
                ])],
            ]);
        }
        return $result;
    }

    public function GetOrderBook (Request $request){
        switch($request->query("platform")){
            case "cex":
                return $this->cexServices->GetBTC();
            case "binance":
                return $this->binanceServices->GetBTC(); 
            default:
                return "no data";        
        }
    }
}

