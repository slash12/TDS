
  <!-- <link type="text/css" rel="stylesheet" href="../css/style.css"/> -->
  <?php
    //require("connect.php");
  ?>
    <nav>
      <div id="nav-wrapper" class="container">
        <a href="#"></a>
        <a href="#home" id="logo"><img src="img/logo_poule.png">ShirtPrints</a>
        <a href="#home" class="link">Browse T-Shirt</a>
        <a href="#home" class="link">Create T-Shirt</a>
        <?php
        if(!isset($_SESSION))
        {
          session_start();
        }
        if(!isset($_SESSION['username']))
        {
          echo "<a href='Register.php' class='creden'>Register</a>";
          echo "<a href='#Login' class='creden' id='lgnmodtri'>Login</a>";
          echo "<script src='js/main.js'></script>";
        }
        else
        {
          echo "<a href='logout.php' class='creden'>Logout</a>";
          echo "<a href='#' class='creden'>".$_SESSION['username']."</a>";
        }
        ?>
      </div>
    </nav>

<?php
  if(isset($_POST['btnlgsubmit']))
  {
    //Username Validation
    $username_cc = trim($_POST['txtusername']);
    $username = mysqli_real_escape_string($dbc, $username_cc);

    //Password Validation
    $password_cc = trim($_POST['txtpassword']);
    $password = mysqli_real_escape_string($dbc, md5($password_cc));

      $login_qry = "SELECT username, password FROM tbl_user WHERE username = '$username' AND password = '$password'";
      $qry = mysqli_query($dbc, $login_qry);
      if(mysqli_num_rows($qry)>0)
      {
        session_start();
        $_SESSION['username'] = $username;
        //header('Location: ../index.php');
      }
      else
      {
        $error_details = "Please Enter Appropriate Credentials!";
        echo "<script>window.onload = function(){modal.style.display = 'block';}</script>";
      }
    }

?>

    <!-- The Modal -->
<div id="lgmodal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <table>
        <tr>
          <td colspan="3">
            <h3>
              Login
              <hr>
            </h3>
          </td>
        </tr>
        <tr>
        <td colspan="3"><span id="error-validation"><?php echo @$error_details; ?></span></td>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
        <tr>
          <td>
            Username
          </td>
          <td>
            <input type="text" name="txtusername" id="txtusername" required/>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
        <tr>
        <tr>
          <td>
            Password
          </td>
          <td>
            <input type="password" name="txtpassword" id="txtpassword" required/>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
          <td>
            <br>
            <input type="submit" name="btnlgsubmit" id="btnlgsubmit" value="Submit"/>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
