<?php
            include("src/php/Model.php");
            include("src/php/View.php");
            ini_set("allow_url_fopen", 1);  
            //Surpress warnings
            error_reporting(E_ERROR | E_PARSE);
            //get the overall market summary
            $bittrex_market_summary = getMarketSummary();
            //get the currencies
            $bittrex_currencies = getCurrencies();
            $Json_success = false;
            //arrays that will be the fundamentals of this website
            $btc_price;
            $currencies;
            $market;
            if($bittrex_market_summary['success']==true && $bittrex_currencies['success']==true){
            //alright! now let's parse the market summary and ONLY get the BTC market.
            $bittrex_btc_market = getBtcMarket($bittrex_market_summary);
            $btc_price = searchMarketLast("USDT-BTC",$bittrex_market_summary["result"]);
            $currencies = $bittrex_currencies["result"];
            $market = $bittrex_btc_market;
            $Json_success = true;
            }
?>