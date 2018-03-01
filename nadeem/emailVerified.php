<?php
function redirect()
{
    header('Location: register.php');
}

    if (!isset($_GET['email']) && (!isset($_GET['token']))) {
        //redirect();
        echo "pan rentrE";
    } else {
        require('includes/connect.php');
        $email = mysqli_real_escape_string($dbc, trim($_GET['email']));
        $token = mysqli_real_escape_string($dbc, trim($_GET['token']));

        $sql_search = "SELECT user_id FROM tbl_user WHERE e_mail = '$email' AND token='$token' AND isEmailConfirmed = 0;";
        $search_qry = mysqli_query($dbc, $sql_search);


        if (mysqli_num_rows($search_qry) > 0) {
            $sql_update = "UPDATE tbl_user SET isEmailConfirmed = 1, token =' ' WHERE e_mail = '$email';";
            $update_query = mysqli_query($dbc, $sql_update);
            //redirect();
            if ($update_query) {
                echo "good";
            } else {
                echo mysqli_error($dbc);
            }
        } else {
            //redirect();
            echo "zafr la pan update";
        }
    }
