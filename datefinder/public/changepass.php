<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $user_id = CS50:: query("SELECT username FROM users WHERE id = ?", $_SESSION["id"]);
        $user_id =$user_id[0]["username"];
        // else render form
        render("changepass_form.php", ["title" => "Change Password", "user_id" => $user_id]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //print_r(get_defined_vars());
        if(empty($_POST["old_password"]))
        {
            apologize("You must provide Old Password!!");
        }
        else if(empty($_POST["new_password"]))
        {
            apologize("New Password field must not be left Blank!!");
        }
       // Validate password and confirm password
        else if ($_POST["new_password"] != $_POST["confirmation"])
        {
            apologize("Those passwords did not match.");
        }
        
        $rows = CS50::query("SELECT * FROM users WHERE username = ?", $_POST["username"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (password_verify($_POST["old_password"], $row["hash"]))
            {
                $changepass = CS50:: query("UPDATE users SET hash = ? WHERE id = ?", password_hash($_POST["new_password"], PASSWORD_DEFAULT), $_SESSION["id"]);
                if($changepass != 0)
                {
                    //print_r(get_defined_vars());
                    print("<script type='text/javascript'>alert(\"Password Changed Successfully!\"); 
                            window.location.href='/';
                            </script>");
                     // redirect to portfolio
                    //redirect("/");
                }
                print("<script type='text/javascript'>alert(\"Error Updating Password!\"); 
                            window.location.href='/';
                            </script>");
               
            }
            else
            {
                apologize("Old Password Entered is Incorrect. Retry!!");
            }
        }
    }

?>