
  <link type="text/css" rel="stylesheet" href="../css/style.css"/>
    <nav>
      <div id="nav-wrapper" class="container">
        <a href="#"></a>
        <a href="#home" id="logo"><img src="img/logonav.png">ShirtPrints</a>
        <a href="#home" class="link">Browse T-Shirt</a>
        <a href="#home" class="link">Create T-Shirt</a>
        <a href="#Register" class="creden">Register</a>
        <a href="#Login" class="creden" id="lgnmodtri">Login</a>
      </div>
    </nav>

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
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><span id="error-validation"></span></td>
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
            <input type="text" name="txtusername" id="txtusername" />
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
            <input type="password" name="txtpassword" id="txtpassword" />
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
<script src="../js/main.js"></script>
