<html>   
    
    
    <head>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
    <?php
            //ask for the JSON files
            ini_set("allow_url_fopen", 1);  
            //ask for the latest data from whattomine
            $alt_json = file_get_contents('http://whattomine.com/coins.json'); 
            $alt_coins = json_decode($alt_json,true);   
            //ask for the latest btc price from bittrex
            $btc_json = file_get_contents('https://api.independentreserve.com/Public/GetMarketSummary?primaryCurrencyCode=xbt&secondaryCurrencyCode=usd');
            $btc = json_decode($btc_json,true);  
            //get the overall market summary
            $bittrex_market_summary = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getmarketsummaries'),true); 
            //get the currencies
            $bittrex_currencies = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getcurrencies'),true);  
            //alright! now let's parse the market summary and ONLY get the BTC market.
            $bittrex_btc_market = array();
             foreach($bittrex_market_summary["result"] as $results){
                        
                        if(preg_match('/BTC-/',$results["MarketName"])){
                            $bittrex_btc_market[] = $results;
                        }
            } 
            $btc_price = $bittrex_market_summary["result"][252]["Last"]; 
    ?>  
    
    </head>
    
    
    
    <body>
        <div class="jumbotron text-center">
             <h1>Coins!</h1>
            <h3>A simple Crypto Currency Value Checker page</h3>
            <p>Data taken from Bittrex.</p>
            <p>BTC = <?php echo $btc_price; ?> USD</p>
        </div>
        
       
        <div class="container">    

            
            <div class ="row ">
            </div> 
            <div class="row">
            <div class="col-md-3">
                    
                    <div class="panel panel-default">
                   
                    <div class="panel-heading">Coins</div>
                    <div class="panel-body">
                         <form method="post" name ="sorter" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <label>Sort By</label>
                        <select name="sortType">
                            <option value="lastPrice" <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="lastPrice")){ echo "selected"; }  ?>>Last Price</option>
                            <option value="name"  <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="name")){ echo "selected"; }  ?>>Coin Name</option> 
                            <option value="volume" 
                                    
                                    <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="volume"))
                                          { echo "selected";  }
                                    else if(!isset($_POST['sortType'])){ echo 'selected'; }
                                            
                                    
                                    
                                    ?>>Volume</option> 
                             </select> 
                        <input type="submit" name="submit" class="btn btn-primary" role="button">
                        </form>
                        </div>
                  
                    </div>
                
                
            </div>
            <div class="col-md-9">  
            <?php 
                $currencies = $bittrex_currencies["result"];
                $market = $bittrex_btc_market;
                
                
                function sortByPrice($a, $b) {
                    return ($b["Last"]<$a["Last"])?-1:1;
                }
                function sortByName($a, $b) {
                    return ($a<$b)?-1:1;
                }
                function sortByVolume($a, $b){
                    return ($b["BaseVolume"]<$a["BaseVolume"])?-1:1;
                }
                
                //sort it by price by default
                usort($market, 'sortByVolume'); 
                if(isset($_POST['sortType'])){ 
                    if($_POST['sortType'] == 'name'){
                        usort($market, 'sortByName');
                    }
                    if($_POST['sortType'] == 'lastPrice'){
                        usort($market, 'sortByPrice');
                    }
                    if($_POST['sortType'] == 'volume'){
                        usort($market, 'sortByVolume');
                    }
                }
                
                           
                rowCreator($market,$currencies, $btc_price);
                
            ?> 
                </div>
            </div>
        </div>
    </body>
    <script>  
    </script>
    
<?php  
    

function rowCreator($coins, $names, $price){
    $numberOfRows = ceil(sizeof($coins)/3);
    $lastIndex = 0;
    for($i=0;$i<=$numberOfRows;$i++){
                echo '</br><div class="row">';
                for($j=$lastIndex;$j<3*$i;$j++){
                    if($j < sizeof($coins)){
                    panelCreator($coins[$j], $names, $price);
                    $lastIndex = $j+1;
                    }
                }
                echo "</div>";
    }
    
}
    
function panelCreator($coin, $names, $price){
      
    
    $coinTag = str_replace("BTC-","",$coin["MarketName"]);
    $coinName = getCurrencyLong($coinTag, $names);
    $coinPrice = $coin["Last"];
    
    if($coinTag != "ADX"){ //strangely, ADX is NOT compatible with this creator. I had to hard code it.
    echo
                '
                <div class = "col-sm-6 col-md-4">
                <div class ="panel-body text-center" id="'.$coinTag.'">
                <img align = "center" src="img/logos/'.strtolower($coinTag).'.png" id="'.$coinTag.'_img" width="100px" height="100px"><div>'
                .'</br><h2>'.
                $coinName
                .'<h3>'
                .number_format($coinPrice*$price,2,'.',' ').
                " USD</h3> </br>".$coinPrice.
                " BTC </br> Volume : ".
                number_format($coin["Volume"],2,'.',' ').'
                </div>
                </div>
                </div>
                '; 
        }
    else{ 
        echo  '
                <div class = "col-sm-6 col-md-4">
                <div class ="panel-body text-center">
                <img align = "center" src="img/logos/buggy coin.png" width="100px" height="100px"><div>'
                .'</br><h2>'.
                $coinName
                .'<h3>'
                .number_format($coinPrice*$price,2,'.',' ').
                " USD</h3> </br>".$coinPrice.
                " BTC </br> Volume : ".
                number_format($coin["Volume"],2,'.',' ').'
                </div>
                </div>
                </div>
                '; 
    }
} 

function getCurrencyLong($tag,$array){
                foreach($array as $key => $val){
                    if($val["Currency"] == $tag){
                        return $val["CurrencyLong"];
                    }
                }
}


    
?>



</html>
