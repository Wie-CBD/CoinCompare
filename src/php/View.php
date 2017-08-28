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
function panelCreator($coin, $names, $btc_price){ 
    $coinTag = str_replace("BTC-","",$coin["MarketName"]);
    $coinName = getCurrencyLong($coinTag, $names);
    $coinPrice = $coin["Last"];
    $coinPriceBtc = $coinPrice*$btc_price; 
    $formatted_price;
    
    if($coinPriceBtc > 1){
        $formatted_price = number_format($coinPriceBtc,2,'.',' ');
    }
    else{
         $formatted_price = number_format($coinPriceBtc,5,'.',' ');
    }

    
    if($coinTag != "ADX"){ //strangely, ADX is NOT compatible with this creator. I had to hard code it.
    echo
                '
                <div class = "col-sm-6 col-md-4">
                <div class ="panel-body text-center coin-panel" id="'.$coinTag.'">
                <img align = "center" src="img/logos/'.strtolower($coinTag).'.png" id="'.$coinTag.'_img" width="100px" height="100px"><div>'
                .'</br><h2>'.
                $coinName
                .'<h3>'
                .$formatted_price.
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
                <div class ="panel-body text-center coin-panel">
                <img align = "center" src="img/logos/buggy coin.png" width="100px" height="100px"><div>'
                .'</br><h2>'.
                $coinName
                .'<h3>'
                .$formatted_price.
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