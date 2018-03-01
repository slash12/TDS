<?php
    require("includes/connect.php");

    @$username = mysqli_real_escape_string($dbc ,trim($_POST['txtusername']));

    $qry_chckusername = "SELECT * FROM tbl_user WHERE username='$username';";

    $qry_run = mysqli_query($dbc, $qry_chckusername);

    if($qry_run)
    {
        $rowCount = mysqli_num_rows($qry_run);
        if($rowCount > 0)
        {
           echo "false";
        }
        else
        {
            echo "true";
        }
    }
    else
    {
        echo "Query did not run";
    }
?>
