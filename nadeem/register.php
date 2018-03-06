<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

require('includes/connect.php');

    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <script src="js/jquery.min.js"></script>
</head>
<?php

    function save_state($a)
    {
        if ($_SERVER['REQUEST_METHOD']== "POST") {
            @$b = $_POST['$a'];
            echo $_POST[$a];
        }
    }

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        $error_arr = array();
        //Last Name
        $lname_cc = trim($_POST['txtlname']);
        //Empty Validation
        if (empty($lname_cc)) {
            $error[] = "Please Enter your Last Name";
        } else {
            $lname = mysqli_real_escape_string($dbc, $lname_cc);
        }
        /*-----------------------------------------------------------------*/
        //First Name
        $fname_cc = trim($_POST['txtfname']);
        //Empty Validation
        if (empty($fname_cc)) {
            $error[]= "Please Enter your First Name";
        } else {
            $fname = mysqli_real_escape_string($dbc, $fname_cc);
        }
        /*-----------------------------------------------------------------*/
        //Country
        $country_cc = $_POST['sltcountry'];
        $country = mysqli_real_escape_string($dbc, $country_cc);
        /*-----------------------------------------------------------------*/
        //Address
        $address_cc = trim($_POST['txtaddress']);
        //Empty Valdation
        if (empty($address_cc)) {
            $error[] = "Please Enter your Address";
        } else {
            $address = mysqli_real_escape_string($dbc, $address_cc);
        }
        /*-----------------------------------------------------------------*/
        //Postal Code
        $pcode_cc = trim($_POST['txtpostalcode']);
        if (empty($pcode_cc)) {
            $error[]="Please Enter your Postal Code";
        } else {
            $pcode = mysqli_real_escape_string($dbc, $pcode_cc);
        }
        /*-----------------------------------------------------------------*/
        //Email
        $email_cc = trim($_POST['txtemail']);
        if (empty($email_cc)) {
            $error[] = "Please Enter your E-mail Address";
        } else {
            $email = mysqli_real_escape_string($dbc, $email_cc);
        }
        /*-----------------------------------------------------------------*/
        //username
        $uname_cc = trim($_POST['txtusername']);
        //Empty Validation
        if (empty($uname_cc)) {
            $error[]="Please Enter your username";
        } else {
            $uname = mysqli_real_escape_string($dbc, $uname_cc);
        }
        /*-----------------------------------------------------------------*/
        //Check for Password
        $password_c = trim($_POST['txtpassword']);
        if (empty($password_c)) {
            $error[] = "Please fill out Password field";
        } elseif (strlen($password_c) < 8) {
            $error[] = "Password is too short (should be greater than 8 characters)";
        } elseif (strlen($password_c) > 20) {
            $error[] = "Password is too Long (should not be larger than 20 characters)";
        } elseif (!preg_match("#[a-z]+#", $password_c)) {
            $error[]= "Password must include at least one short letter!";
        } elseif (!preg_match("#[A-Z]+#", $password_c)) {
            $error[]= "Password must include at least one CAPS!";
        }
        /*-----------------------------------------------------------------*/
        //Confirm Password
        $pc_c = trim($_POST['txtcpassword']);
        if (empty($pc_c)) {
            $error[] = "Please fill out Confirm Password field";
        }

        if (@$password_c != $pc_c) {
            $error[] = "Passwords not the same, Retry!";
        } else {
            $pc_e = md5($pc_c);
            $password = mysqli_real_escape_string($dbc, $pc_e);
        }

        /*-----------------------------------------------------------------*/
        if (empty($error)) {
            //Generated token
            $token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789!$/()*";
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            $register_query = "INSERT INTO tbl_user(l_name, f_name, country, address, postal_code, e_mail, username, password, isEmailConfirmed, token) VALUES('$lname', '$fname', '$country', '$address', '$pcode', '$email', '$uname','$password', '0', '$token');";
            $register_query_run = mysqli_query($dbc, $register_query);

            if ($register_query_run) {
                include_once("phpMailer/PHPMailer.php");
                include_once("phpMailer/Exception.php");
                include_once("phpMailer/SMTP.php");
                $mail = new PHPMailer();
                //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com;';                    // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->SMTPSecure = false;
                $mail->Username = 'testappui357@gmail.com';                 // SMTP username
            $mail->Password = 'qwert1234';                           // SMTP password
            //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;
                $mail->setFrom('testappui357@gmail.com', 'Nadda');
                $mail->addAddress($email, 'User');
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Here is the subject';
                $mail->Body    = "
                Please Click on the link below: <br><br>
                <a href=\"http://localhost:8001/dissertation_scratch/tds/nadeem/emailVerified.php?email=$email&token=$token\">Click Here</a>
            ";


                if ($mail->send()) {
                    echo "You have been registered. Please check your E-mail to activate your account.";
                } else {
                    echo $mail->ErrorInfo;
                }
                //header('Location: register.php');
            } else {
                echo "qry not run";
            }
        } else {
            echo "<h1>&#9940; Error!</h1><p style='font-weight:bold;'>The following error(s) occurred:</p>";
            foreach ($error as $msg) { // Print each error.
                echo " - <span class='error'>$msg</span><br />\n";
            }
            echo "<br /><p class='error'>Please try again.</p><p><br />";
        }
    }

?>
<body>
  <?php
    require('includes/navbar.php');
  ?>
    <h1 class="heading1">Registration Page</h1>
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="post" id="frmregister">
        <table>
            <tr>
                <td>
                    Last Name
                </td>
                <td>
                    <input type="text" name="txtlname" id="txtlname" value="<?php save_state('txtlname'); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    First Name
                </td>
                <td>
                    <input type="text" name="txtfname" id="txtfname" value="<?php save_state('txtfname'); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Country
                </td>
                <td>
                <select name="sltcountry" id="sltcountry">
                        <option value="" disabled selected>Choose your Country</option>
                        <?php
                          $all_country = "SELECT country_code, country_name FROM country;";
                          $country_qry = mysqli_query($dbc, $all_country);

                          while($row = mysqli_fetch_array($country_qry, MYSQLI_ASSOC))
                          {
                            echo "<option value=".$row['country_code'].">".$row['country_name']."</option>";
                          }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Address
                </td>
                <td>
                    <input type="text" name="txtaddress" id="txtaddress" value="<?php save_state('txtaddress'); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    Postal Code
                </td>
                <td>
                    <input type="text" name="txtpostalcode" id="txtpostalcode" value="<?php save_state('txtpostalcode'); ?>"/>
                </td>
            </tr>
             <!-- E-mail -->
             <script>
                //Check if email already exist
                function chckEmail(value)
                {
                $.ajax({
                type:"POST",
                url:"checkEmail.php",
                data:"txtemail="+value,
                success:function(data)
                {
                if(data == "false")
                {
                document.getElementById('msg1').innerHTML = "<span class='error'>&#9888; Email Already Existed</span>";
                document.getElementById("btnsubmit").disabled = true;
                document.getElementById('txtemail').style.border="1px solid #FF0000";

                }
                if(data == "true")
                {
                document.getElementById('msg1').innerHTML = "<span style='color:green;'> &#x2611; Valid E-mail Address</span>";
                document.getElementById('txtemail').style.border="1px solid green";
                document.getElementById("btnsubmit").disabled = false;
                }
                }
                });
                }
                    </script>
            <tr>
                <td>
                    Email
                </td>
                <td>
                    <input type="email" name="txtemail" id="txtemail" value="<?php save_state('txtemail'); ?>" onkeyup="chckEmail(this.value)"/>
                    <span id="msg1"></span>
                </td>
            </tr>
            <!-- Username -->
            <script>
               //Check if username already exist
               function chckUsername(value)
               {
               $.ajax({
               type:"POST",
               url:"checkUsername.php",
               data:"txtusername="+value,
               success:function(data)
               {
               if(data == "false")
               {
               document.getElementById('msg2').innerHTML = "<span class='error'>&#9888; Username Already Existed</span>";
               document.getElementById('txtusername').style.border="1px solid #FF0000";
               $("#btnsubmit").prop('disabled', true); // disable button

               }
               if(data == "true")
               {
               document.getElementById('msg2').innerHTML = "<span style='color:green;'>&#x2611; Valid Username</span>";
               document.getElementById('txtusername').style.border="1px solid green";
               document.getElementById("btnsubmit").removeAttr('disabled');
               $("#btnsubmit").prop('disabled', false); // disable button
               }
               }
               });
               }
                   </script>
            <tr>
                <td>
                    Username
                </td>
                <td>
                    <input type="text" name="txtusername" id="txtusername" value="<?php save_state('txtusername'); ?>" onkeyup="chckUsername(this.value)"/>
                    <span id="msg2"></span>
                </td>
            </tr>
            <tr>
                <td>
                    Password
                </td>
                <td>
                    <input type="password" name="txtpassword" id="txtpassword" />
                </td>
            </tr>
            <tr>
                <td>
                    Confirm Password
                </td>
                <td>
                    <input type="password" name="txtcpassword" id="txtcpassword" />
                </td>
            </tr>
            <?php
            if ($_SERVER['REQUEST_METHOD']=="post") {
                $cpass = trim($_POST['txtcpassword']);
                echo "<script>alert('".$cpass."');</script>";
                if ($cpass != $password_cc) {
                    echo "<span class='error'>Password and Confirm password not similar, Try again!</span>";
                    $error = "true";
                }
            }
            ?>
            <tr>
                <td>
                    &nbsp;
                </td>
                <td>
                    <input type="Submit" name="btnsubmit" id="btnsubmit" value="Submit"/>
                    <input type="reset" name="btnreset" id="btnreset" value="Reset"/>
                </td>
            </tr>
        </table>
    </form>
    <br />
</body>
<?php
 require('includes/footer.html');
?>
</html>
