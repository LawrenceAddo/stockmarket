<?php
   
    //configuration
    require("../includes/config.php");
    
    if(empty($_POST["symbol"]))
    {
        apologize("provide us with the stock you want to sell");
    }
    else
    {   
    
        $shares = query("SELECT * FROM information WHERE id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        foreach($shares as $share)
        {
            $stocks = lookup($_POST["symbol"]);
            $cash_added = ($stocks["price"]*$share["share"]);
            query("UPDATE users SET cash = cash + ? WHERE id = ?" , $cash_added, $_SESSION["id"]);
            
            //adding the data into the history table
            query("INSERT INTO history (purchase, symbol, shares, price, id) VALUES(?, ?, ?, ?, ?)", "SELL", $_POST["symbol"], $share["share"], $stokes["price"], $_SESSION["id"]);
        
        }
        //deleting the row from the information table
        query("DELETE FROM information WHERE id = ? AND symbol = ?", $_SESSION["id"],$_POST["symbol"]);
        
        
        //redirect to index.php
        redirect("index.php");
    }
?>
