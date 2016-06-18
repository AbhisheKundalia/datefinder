<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("buy_form.php", ["title" => "Buy"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(empty($_POST["symbol"]))
        {
            apologize("You must specify a stock to buy.");
        }
        else if(empty($_POST["shares"]))
        {
            apologize("You must specify a number of shares.");
        }
        else if(preg_match("/^\d+$/", $_POST["shares"]) == false)
        {
            apologize("Invalid number of shares.");
        }
        // validate submission
        $stock = lookup($_POST["symbol"]);
        if($stock == false)
        {
            apologize("Symbol not found.");
        }
        // extract values of stock array
        extract($stock);
        //print_r(get_defined_vars());
        
        // to find the value of cash available
        $cash = CS50::query("SELECT cash FROM users WHERE id = ?",$_SESSION["id"]);
        
        $cash = $cash[0];
        $cash = number_format($cash["cash"], 2, '.', '');
        
        $amount = $stock["price"] * $_POST["shares"];
        $amount = number_format($amount, 2, '.', '');
        //print("amount - $".$amount."cash-$".$cash);
        if ($amount > $cash)
        {
            apologize("You can't afford that.");
        }
        else
        {
            $buy = CS50::query("INSERT INTO `portfolios` (`user_id`, `symbol`, `shares`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + ? ",$_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"], $_POST["shares"]);
            if($buy == 0)
            {
                print("Error Buying!!");
            }
            
            $buyed = CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?",$amount, $_SESSION["id"]);
            if($buyed == 0)
            {
                apologize("Unable to update Cash!!");
            }
            
            $historylog = CS50::query("INSERT INTO `history` (`user_id`, `symbol`, `shares`, `transaction`, `price`) VALUES (?, ?, ?, 'BUY', ?)", $_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"], $stock["price"]);
            // redirect to portfolio
            redirect("/");
            
        }
    }

?>