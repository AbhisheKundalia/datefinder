<?php

    // configuration
    require("../includes/config.php"); 
    
        //query to get all the values of history for the ID
        $rows = CS50::query("SELECT * FROM `history` WHERE `user_id` = ?", $_SESSION["id"]);
                
        $positions = [];
        foreach ($rows as $row)
        {
            $date = date_create($row["date"]);
            $positions[] = [
                "transaction" => $row["transaction"],
                "date" => date_format($date, 'd/m/Y g:i A'),
                "symbol" => $row["symbol"],
                "shares" => $row["shares"],
                "price" => number_format($row["price"], 2, '.', ',')
            ];
            
        }
    //print_r(get_defined_vars());
    // render history.php
    render("history.php",["title" => "History", "positions" => $positions]);
?>
