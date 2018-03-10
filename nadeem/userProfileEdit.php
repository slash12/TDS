<?php
  require('includes/connect.php');

  //Check if session has started
  if(!isset($_SESSION))
  {
    session_start();
  }

  @$username = $_SESSION['username'];

if(isset($username))
{
  //Fetching info about user in db
    $user_qry = "SELECT * FROM tbl_user WHERE username= '$username'";
    $user_qry_exe = mysqli_query($dbc, $user_qry);
    if($user_qry_exe)
    {
      $user_data = mysqli_fetch_assoc($user_qry_exe);
    }
    else
    {
      echo "<script> alert('Done user search failed')</script>";
    }
}


?>
<!DOCTYPE html>
<html>
    <meta charset="utf-8">
    <title>User Profile Edit</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
  </head>
  <?php
    if(isset($_POST['btnuploadimg']))
    {
      $uploadfile=$_FILES["upload_file"]["tmp_name"];
      $folder="img/upload/";
      $target_file = $folder . basename($_FILES["upload_file"]["name"][0]);
      $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
      // $imgcheck = true;

/*---------------------Image Validation-----------------------------*/
               $b = "Sorry, only JPG, JPEG & PNG files are allowed.";
               $a = "Sorry, your image is too large.";

               echo $_FILES["upload_file"]["size"][0];
               //Image > 500KB result in error
               if ($_FILES["upload_file"]["size"][0] > 500000)
               {
                 echo $a;
                $imgcheck = false;
                echo $imgcheck;
               }

               // Allow certain file formats
               if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg")
               {
                 $imgcheck = "true";
               }
               else
               {
                $imgcheck = false;
               }

               //echo $imgcheck;

               if($imgcheck)
               {

                 if(file_exists($user_data['img_path']))
                 {
                   //Delete old img
                   unlink($user_data['img_path']);
                   echo "<script> alert('Old img delete success')</script>";
                 }
                 else
                 {
                   echo "<script> alert('img does not exist')</script>";
                   //echo $target_file;
                 }

                 move_uploaded_file($_FILES["upload_file"]["tmp_name"][0], "$folder".$username."_".$_FILES["upload_file"]["name"][0]);

                 $target_file_final = $folder.$username."_".$_FILES["upload_file"]["name"][0];

                 //Adding the new image to the db
                 $update_img = "UPDATE tbl_user SET img_path = '$target_file_final' WHERE username='".$user_data['username']."';";
                 $update_img_exe = mysqli_query($dbc, $update_img);

                 if($update_img_exe)
                 {
                   echo "<script> alert('img_path updated') </script>";
                   // header('Location: userProfileEdit.php');
                   echo $imgcheck."<br>";
                   //echo $_FILES["upload_file"]["size"][0];
                 }
                 else
                 {
                   echo "<script> alert('img_path not updated') </script>";
                 }

                 echo "<script> alert('Upload img success'".$target_file.")</script>";

               }
               else
               {
                 echo "<script>alert('error uploading img')</script>";
               }
    }

    if(isset($_POST['btnsubmit']))
    {
      $user_id = $user_data['user_id'];
      $uname = $_POST['txtupdateusername'];
      $email = $_POST['txtemail'];
      $country_code = $_POST['sltcountry'];
      $address = $_POST['address'];

      $user_update = "UPDATE `tbl_user` SET `country_code`='$country_code',`address`='$address',`e_mail`='$email',`username`='$uname' WHERE user_id = '$user_id';";
      $user_update_exe = mysqli_query($dbc, $user_update);

      if($user_update_exe)
      {
        echo "<script> alert('User Details are updated')</script>";
      }
    }
  ?>
  <body>
    <?php require('includes/navbar.php'); ?>
    <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <td rowspan="6">
            <input type="file" id="upload_file" name="upload_file[]" onchange="loadFile(event)" required>
            <br>
            <img id="output" src="<?php echo @$user_data['img_path'] ?>" width="300" height="150">
            <!-- Script used to preview Image -->
            <script>
            var loadFile = function(event)
            {
              var output = document.getElementById('output');
              output.width = 300;
              output.height= 150;
              output.src = URL.createObjectURL(event.target.files[0]);
            }
            </script><br>
            <center><input type="submit" class="button" name="btnuploadimg" id="btnuploadimg" value="Upload Image"></center>
          </td>
        </tr>
        <tr>
          <td>Username</td>
          <td><input type="text" name="txtupdateusername" id="txtupdateusername" value="<?php echo @$user_data['username'] ?>"></td>
        </tr>
        <tr>
          <td>E-mail</td>
          <td><input type="email" name="txtemail" id="txtemail" value="<?php echo @$user_data['e_mail'] ?>"></td>
        </tr>
        <tr>
            <td>
                Country
            </td>
            <td>
            <select name="sltcountry" id="sltcountry">
                    <!-- <option value="" disabled selected>Choose your Country</option> -->
                    <?php
                      $all_country = "SELECT country_code, country_name FROM country;";
                      $country_qry = mysqli_query($dbc, $all_country);

                      while($row = mysqli_fetch_array($country_qry, MYSQLI_ASSOC))
                      {
                        echo "<option value=".$row['country_code'].">".$row['country_name']."</option>";
                      }

                      if($country_qry)
                      {
                        $search_country = "SELECT country_code, country_name FROM country WHERE country_code='".$user_data['country_code']."';";
                        $search_country_exe = mysqli_query($dbc, $search_country);
                        if($search_country_exe)
                        {
                          $data_country = mysqli_fetch_assoc($search_country_exe);
                          echo "<option value=".$data_country['country_code']." selected>".$data_country['country_name']."</option>";
                        }
                      }
                    ?>
                </select>
            </td>
        </tr>
          <td>Address</td>
          <td><input type="text" name="txtaddress" id="txtaddress" value="<?php echo @$user_data['address'] ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="btnsubmit" id="btnsubmit" value="Update Personal Info"></td>
        </tr>
      </table>
    </form>
  </body>
</html>
