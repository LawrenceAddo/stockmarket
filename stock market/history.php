<?php
    
    //configuration
    require("../includes/config.php");
    
    $getting = query("SELECT * FROM history WHERE id = ?", $_SESSION["id"]);
    if( $getting == false )
    {
        render("notyet.php", ["title" => "history"]);
    }
    else
    {
        $hist = [];
        foreach($getting as $get)
        {
            $hist[] = [
                "purchase" => $get["purchase"],
                "symbol" => $get["symbol"],
                "dateandtime" => $get["dateandtime"],
                "shares" => $get["shares"],
                "price" => $get["price"],
            ];
        
        }
    }
    render("history.php", [ "hist" => $hist, "title" => "history"]);
?>
