<html>   
    
    
<head>
<title>BitCompare</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256 DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="  crossorigin="anonymous"></script>
    <script type="application/javascript" src="src/js/chosen.jquery.js"></script>
<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
<link rel="stylesheet" type="text/css" href="src/css/chosen.css" media="screen" />   
    <link rel="stylesheet" type="text/css" href="src/style.css" media="screen" />  
<?php
            include("src/php/controller.php");
    ?>  
    
    </head>
    
    
    
    <body>
        <div class="jumbotron text-center" id="header">
            <h1><img onclick="window.location.href=window.location.href" class="img-responsive center-block" id="header_img" src="img/logo.png">
            </h1>
            <h3>A simple Crypto Currency Value Checker page</h3>
            <p>Data taken from Bittrex.</p> 
            
            
        </div> 
        <div class="container">     
            <div class ="row ">
            </div> 
            <div class="row">
            <?php    
                 if($Json_success == true){
            ?>
            <div class="col-md-3"> 
                    
                    <div class="panel panel-default">
                    <div class="panel-heading">Control</div>
                    <div class="panel-body">
                        
                        
                        
                         <form method="post" name ="sorter" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
                             <label>Sort By</label><br>
                        <select name="sortType" class="chosen-select">
                            <option value="lastPrice" <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="lastPrice")){ echo "selected"; }  ?>>Last Price</option>
                            <option value="name"  <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="name")){ echo "selected"; }  ?>>Coin Name</option> 
                            <option value="volume" 
                                    
                                    <?php if(isset($_POST['sortType'])&&($_POST['sortType']=="volume"))
                                          { echo "selected";  }
                                    else if(!isset($_POST['sortType'])){ echo 'selected'; }
                                     ?>>Volume</option> 
                             </select> 
                        <br>
                        <label>Show Only...</label><br>
                        <select multiple name="coinFilters[]" class="chosen-select"> 
                            <?php
                            foreach($bittrex_market_summary['result'] as $coin){
                            $CoinTag =  str_replace("BTC-","",$coin["MarketName"]);
                            $CurrencyLong = getCurrencyLong($CoinTag,$currencies);
                            echo '<option value='.$CoinTag.'>'.$CurrencyLong.'</option>';
                            }
                            ?>
                        </select> 
                        
                        <input style="margin-top: 5px;" type="submit" name="submit" class="btn btn-primary" role="button">
                        
                        </form> 
                          
                        <button onClick="window.location.href=window.location.href" class="btn btn-danger">Reset</button>
                       
                        
                    </div>
                  
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <?php
                 
                        echo '<img src="img/logos/btc.png" align="center" width="100px" height="100px"><br>';
                        echo '<h2>Bitcoin</h2>';
                        echo "<h3>".$btc_price." USD</h3>";
                
            
                        ?>
                        
                        </div>
                    </div>
            </div>
            <div class="col-md-9">  
            <?php 
                
                function sortByPrice($a, $b) {
                    return ($b["Last"]<$a["Last"])?-1:1;
                }
                function sortByName($a, $b) {
                    return ($a<$b)?-1:1;
                }
                function sortByVolume($a, $b){
                    return ($b["BaseVolume"]<$a["BaseVolume"])?-1:1;
                }
                $filteredCoin = array();
               
                
                if(!empty($filteredCoin)){
                    echo 'Previous Filtered coin detected';
                    
                }
                
                else if(isset($_POST['coinFilters'])){
                    $filterCoin = array();
                    $filterCoin = $_POST['coinFilters']; 
                    foreach($market as $market_coin){
                        $coinTag = str_replace("BTC-","",$market_coin["MarketName"]);
                        foreach($filterCoin as $filter){
                            if($coinTag == $filter){  
                                $filteredCoin[] = $market_coin;
                            }
                        }
                    }
                    if(isset($_POST['sortType'])){ 
                        if($_POST['sortType'] == 'name'){
                            usort($filteredCoin, 'sortByName');
                        }
                        if($_POST['sortType'] == 'lastPrice'){
                            usort($filteredCoin, 'sortByPrice');
                        }
                        if($_POST['sortType'] == 'volume'){
                            usort($filteredCoin, 'sortByVolume');
                        }
                    }
                    else{
                        usort($filteredCoin, 'sortByVolume');
                    }
                    rowCreator($filteredCoin,$currencies, $btc_price);
                    
                }
                else{
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
                else{//sort it by price by default
                usort($market, 'sortByVolume'); 
                }
                    rowCreator($market,$currencies, $btc_price);
                }
                
            ?> 
                </div>
            
            <?php
                }
                else{?>
                <div class="text-center">
                <h2>Oops!</h2>
                <p>There seems to be a problem. Please come back later!</p>
                
                </div>
            <?php
                }
            ?>
            
            </div>
        </div>
        <footer class="footer">
            <div class="container text-center" >
                 
            </div>
            <div class="container text-center">
                <p> Copyright ©2017 BitCompare</p>
            </div>
        
        
        </footer>
    </body> 
    
    <script>
        //Chosen-select
        $(function(){
             $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"}); 
        })
    </script> 
</html>
