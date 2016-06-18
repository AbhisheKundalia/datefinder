<?php

    // configuration
    require("../includes/config.php"); 
    
    $cash = CS50::query("SELECT cash FROM users WHERE id = ?",$_SESSION["id"]);
    //print_r(get_defined_vars());
    $cash = $cash[0];
    $cash = number_format($cash["cash"], 2, '.', ',');
    
        //query to get all the values of stocks for the ID
        $rows = CS50::query("SELECT * FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        //print_r(get_defined_vars());
                
        $positions = [];
        foreach ($rows as $row)
        {
            $stock = lookup($row["symbol"]);
            if ($stock !== false)
            {
                $positions[] = [
                    "name" => $stock["name"],
                    "price" => $stock["price"],
                    "shares" => $row["shares"],
                    "symbol" => $row["symbol"],
                    "total" => number_format($stock["price"] * $row["shares"], 2, '.', ',')
                ];
            }
        }
                
    // redirect to portfolio
    render("portfolio.php",["title" => "Portfolio", "positions" => $positions, "cash" => $cash]);
?>
