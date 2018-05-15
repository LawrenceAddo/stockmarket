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
            apologize("kindly provide with a symbol of stock you want to buy");
        }
        else if(empty($_POST["shares"]))
        {
            apologize("kindly provide the number of shares you would like to buy");
        }
        else if($_POST["shares"] <= 0)
        {
            apologize("Please provide us with a whole number of stocks");
        }
        else 
        {
            $stocks = lookup($_POST["symbol"]);
            if($stocks === false)
            {
                apologize("Please provide a valid stock symbol");
            }
            else
            {
                $debit = ($stocks["price"] * $_POST["shares"]);
                $checking = query("SELECT cash, username FROM users WHERE id = ? ", $_SESSION["id"]);
                foreach($checking as $check)
                {   
                    $cash_debited = ($check["cash"] - $debit);
                    if($cash_debited < 0)
                    {
                        apologize("You don't have enough money in your wallet");
                    } 
                    else
                    {
                        query("UPDATE users SET cash = ? WHERE id = ?" ,$cash_debited,  $_SESSION["id"]);
                    }
                }
                $symbol = strtoupper($_POST["symbol"]);
                $inserting = query("SELECT * FROM information WHERE id = ? AND symbol = ?" , $_SESSION["id"], $symbol);
                if( $inserting === false )
                {
                    query("INSERT INTO information (id, symbol, share) VALUES(?, ?, ?)", $_SESSION["id"],$symbol,$_POST["shares"]);
                }
                else 
                {
                    query("INSERT INTO information (id, symbol, share) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE share = share + VALUES(share)", $_SESSION["id"], $symbol,$_POST["shares"]);
                }
                
                //adding the data into history table
                query("INSERT INTO history (purchase, symbol, shares, price, id) VALUES(?, ?, ?, ?, ?)", "BUY", $symbol, $_POST["shares"], $stocks["price"], $_SESSION["id"]);
                
                // redirect to index.php
                redirect("index.php");
            }
        }
    }

?>
