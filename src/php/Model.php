<?php  
    

    function getMarketSummary(){ 
        $marketSummary = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getmarketsummaries'),true);
        return $marketSummary;
        
    }

    function getCurrencies(){ 
        $currencies  =  json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getcurrencies'),true);  
        return $currencies;
    }

    

    function getBtcMarket($market){ 
        $btcMarket = array(); 
        foreach($market["result"] as $results){ 
                        if(preg_match('/BTC-/',$results["MarketName"])){ 
                            $btcMarket[] = $results;
                        }
            } 
        return $btcMarket;
    }

     function getEthMarket($market){ 
        $ethMarket = array(); 
        foreach($market["result"] as $results){ 
                        if(preg_match('/ETC-/',$results["MarketName"])){ 
                            $btcMarket[] = $results;
                        }
            } 
        return $ethMarket;
    }

    function getMarket($market, $marketType){
        $market;
        if($marketType == "btc"){
            foreach($market["result"] as $results){ 
                        if(preg_match('/BTC-/',$results["MarketName"])){ 
                            $market[] = $results;
                        }
            } 
        }
        else if ($marketType == "eth"){
             foreach($market["result"] as $results){ 
                        if(preg_match('/ETC-/',$results["MarketName"])){ 
                            $market[] = $results;
                        }
            } 
        }
        return $market;
    }
    

    function searchMarketLast($marketName, $market){ 
        foreach($market as $key => $val){
                    if($val["MarketName"] == $marketName){
                        return $val["Last"];
                    }
        }
}

?>