<?php session_start();?>
<?php
include_once("includes/connectDb.php");
//session_start();
if($_SESSION['email'] == "" OR $_SESSION["role"] == "User"){
    header('location:index.php');
    echo '<script>window.location = "index.php";</script>';
}
if ($_SESSION['role'] == "Admin") {


    include_once("includes/header.php");
    include_once("includes/navbar.php");
    include_once("includes/sidebar_admin.php");
} else {
  include_once("includes/header.php");
  include_once("includes/navbar.php");
  include_once("includes/sidebar_user.php");
}

if(isset($_POST['update'])){
  $oldPassword = $_POST['oldPassword'];
  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];
      
  //echo "Old" .  $oldPassword . "New" . $newPassword . "Confirm" .  $confirmPassword;
  $email = $_SESSION['email'];
  $select = $pdo -> prepare("SELECT * FROM users where email = '$email'");
  $select -> execute();
  $row = $select -> fetch(PDO::FETCH_ASSOC);
  $username =  $row['username'];
  $email = $row['email'];
  $password =  $row['password'];
 
  if($oldPassword == $password){
      if($newPassword == $confirmPassword){
          $update = $pdo -> prepare("UPDATE users SET password = :pass where email = :email");
          $update -> bindParam(':pass', $confirmPassword);
          $update -> bindParam (':email', $email);
          if($update -> execute()){
              
              echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Good Job!",
          text: "Your password is successfully",
          icon: "success",
          button: "OK",
          });
      });
      </script>';

          }else {
              echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Error",
          text: "Password is not Updated",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
          }
      }else {
          echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Warning",
          text: "new and confrim password is not matched",
          icon: "warning",
          button: "OK",
          });
      });
      </script>';
      }
  }else{
      echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Warning",
          text: "Your password is wrong please fill right password",
          icon: "warning",
          button: "OK",
          });
      });
      </script>';
  }
}
  

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    <div class="box box-warning">
      <form action="" method="post" name="">
        <div class="box-header with-border">
          <h3 class="box-title">blank</h3>
        </div>
        <div class="box-body">
        <form role="form" action="" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Old Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="oldPassword" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="newPassword" required>
                  </div>
                    <div class="form-group">
                    <label for="exampleInputPassword1">Confrim Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="confirmPassword" required>
                  </div>
                    
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
              </form>
              
        </div>
      </form>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include_once("includes/footbar.php")?>
  <!-- Control Sidebar -->
  <?php include_once("includes/outbar.php")?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
<?php include_once("includes/footer.php")?>