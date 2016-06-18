<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // get all the availabe stocks for the user
        $stocks = CS50::query("SELECT symbol FROM `portfolios` WHERE user_id = ?",$_SESSION["id"]);
        if(count($stocks) == 0)
        {
            apologize("Nothing to Sell!!");
        }
        render("sell.php",["title" => "Sell", "stocks" => $stocks]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(empty($_POST["symbol"]))
        {
            apologize("You must select a stock to sell.");
        }
       
        $stock = lookup($_POST["symbol"]);
        if($stock == false)
        {
            apologize("Price Not found for the Symbol.");
        }
        $rows = CS50::query("SELECT * FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
    
        // else render quote page with values of stock
        $sell = CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ? ", $_SESSION["id"], $_POST["symbol"]);  
        if($sell == 1)
        {
            //print_r(get_defined_vars());
            $rows = $rows[0];
            $selled = CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?",$stock["price"] * $rows["shares"], $_SESSION["id"]);
            if($selled != 1)
            {
                apologize("Unable to update Cash!!");
            }
            
            $historylog = CS50::query("INSERT INTO `history` (`user_id`, `symbol`, `shares`, `transaction`, `price`) VALUES (?, ?, ?, 'SELL', ?)", $_SESSION["id"], strtoupper($_POST["symbol"]), $rows["shares"], $stock["price"]);
            
            // redirect to portfolio
            redirect("/");
        }
    }
    

?>