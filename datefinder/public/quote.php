<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("quote_form.php", ["title" => "Get Quote"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        $stock = lookup($_POST["symbol"]);
        if($stock == false)
        {
            apologize("Symbol not found.");
        }
        // extract values of stock array
        extract($stock);
        //print_r(get_defined_vars());
            
        // else render quote page with values of stock
        render("quote.php",["title" => "Quote", "name" => $name, "symbol" => $symbol, "price" => number_format($price, 2, '.', '')]);
    }

?>
