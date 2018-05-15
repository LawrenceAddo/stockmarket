<?php
    
    //configuration
    require("../includes/config.php");
   
    $sellings = query("SELECT symbol, share FROM information WHERE id = ?" , $_SESSION["id"]);
    $sale = [];
    if($sellings != null)
    {
        
        foreach($sellings as $selling)
        {
            $stocks = lookup($selling["symbol"]);
            if($stocks !== false)
            {
                $sale[] = [
                   "name" => $stocks["name"],
                   "price" => $stocks["price"],
                   "sym" => $selling["symbol"],
                   "shares" => $selling["share"]
                ];
            }
        
         }
         render("sold.php" , ["sale" => $sale, "title" => "selling"]);
     }
     else if($sellings == null ) 
     {
         render("not_sold.php" , ["title" => "not_selling"]);
     }
     
?>
