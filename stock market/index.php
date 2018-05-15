    <?php

    // configuration
    require("../includes/config.php"); 

    
    //displaying the current portfolio of the user
    $rows = query("SELECT symbol, share FROM information WHERE id = ?" , $_SESSION["id"]);
    $money = query("SELECT cash, username FROM users WHERE id = ? ", $_SESSION["id"]);
    $mon = [];
    foreach($money as $mone)
    {
        
            $mon[] = [
                "cashh" => $mone["cash"],
                "nam" => $mone["username"]
            ];
             
    }
    $positions = [];
    foreach($rows as $row)
    {
        $stocks = lookup($row["symbol"]);
        if($stocks !== false)
        {
            $positions[] = [
                "name" => $stocks["name"],
                "price" => $stocks["price"],
                "share" => $row["share"],
                "symbol" => $row["symbol"],
                "current_value" => ($stocks["price"]*$row["share"])
            ];
        }
     }
     
     render("portfolio.php", [ "positions" => $positions, "mon" => $mon, "title" => "Portfolio"]);
     

?>
