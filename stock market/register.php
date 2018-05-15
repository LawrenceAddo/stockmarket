<?php

    //configuration
    require("../includes/config.php");

    //if user reached page via GET (as by clicking a link or via redirect)
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //else render form
        render("register_form.php", ["title" => "Register"]);
    }

    //else if user reached page via POST (as by submitting a form via POST)
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your passwords donot match.");
        }
        $result = query("INSERT INTO users (username, hash, cash) VALUES(?, ?, 10000.0000)", $_POST["username"], crypt($_POST["password"]));
        if($result === false)
        { 
            //return false if username already exists
            apologize("This username already exists");
        }
        else
        {
            // if insertion is successful
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            
            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $id;

            // redirect to index.php
            redirect("index.php");
        }
        
        
        
    }

?>

