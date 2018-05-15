<?php
    // configuration
    require("../includes/config.php"); 
    
    //if user reached page via GET (as by clicking a link or via redirect)
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //else render form
        render("change_form.php", ["title" => "settings"]);
    }

    //else if user reached page via POST (as by submitting a form via POST)
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
         if (empty($_POST["current_password"]))
         {
             apologize("You must provide your current password.");
         }
         else if (empty($_POST["new_password"]))
         {
             apologize("You must provide your new password.");
         }
         else if (empty($_POST["confirmation"]))
         {
             apologize("Kindly confirm your new password.");
         }
         else 
         {
             $change = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]); 
             foreach($change as $check)
             {
                 if((password_verify($_POST["current_password"],$check["hash"])) === false)
                 {
                     apologize("Your current password is incorrect, try again!");
                 }
             }
             if ($_POST["new_password"] != $_POST["confirmation"])
             {
                 apologize("Your passwords donot match.");
             }
             else
             {
                 query("UPDATE users SET hash = ? WHERE id = ?" , crypt($_POST["new_password"]) , $_SESSION["id"]);
             }
         }
    }
?>

