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

    function searchMarketLast($marketName, $market){ 
        foreach($market as $key => $val){
                    if($val["MarketName"] == $marketName){
                        return $val["Last"];
                    }
        }
}

?>