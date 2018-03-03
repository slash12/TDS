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
                $mail->addAddress('nadeemshb_12@hotmail.com', 'Nad User');
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
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antartica">Antarctica</option>
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Aruba">Aruba</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Belize">Belize</option>
                        <option value="Benin">Benin</option>
                        <option value="Bermuda">Bermuda</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Bouvet Island">Bouvet Island</option>
                        <option value="Brazil">Brazil</option>
                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Congo">Congo, the Democratic Republic of the</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                        <option value="Croatia">Croatia (Hrvatska)</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="East Timor">East Timor</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="France Metropolitan">France, Metropolitan</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="French Southern Territories">French Southern Territories</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Greece">Greece</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                        <option value="Holy See">Holy See (Vatican City State)</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hong Kong">Hong Kong</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran (Islamic Republic of)</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
                        <option value="Korea">Korea, Republic of</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Lao">Lao People's Democratic Republic</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Macau">Macau</option>
                        <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius" selected>Mauritius</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Micronesia">Micronesia, Federated States of</option>
                        <option value="Moldova">Moldova, Republic of</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Niue">Niue</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau">Palau</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Pitcairn">Pitcairn</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russian Federation</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                        <option value="Saint LUCIA">Saint LUCIA</option>
                        <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                        <option value="Samoa">Samoa</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia (Slovak Republic)</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                        <option value="Span">Spain</option>
                        <option value="SriLanka">Sri Lanka</option>
                        <option value="St. Helena">St. Helena</option>
                        <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syrian Arab Republic</option>
                        <option value="Taiwan">Taiwan, Province of China</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania, United Republic of</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Turks and Caicos">Turks and Caicos Islands</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Viet Nam</option>
                        <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                        <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                        <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                        <option value="Western Sahara">Western Sahara</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Yugoslavia">Yugoslavia</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
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
