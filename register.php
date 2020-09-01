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
error_reporting(0);
if(isset($_POST["delete"])){
    $id = $_GET['id'];
$delete = $pdo -> prepare("DELETE FROM users WHERE userId = ".$id);

if($delete ->execute()){
    echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Delete",
            text: "Delete Successfully",
            icon: "success",
            button: "OK",
            });
        });
        </script>';
}else {
    echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Error",
            text: "Delete faild",
            icon: "warning",
            button: "OK",
            });
        });
        </script>';
}

}



if(isset($_POST['save'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['selectOption'];
    //echo $username . $email . $password . $role;
    if(isset($_POST['email'])){
        $select = $pdo -> prepare("SELECT * FROM users WHERE email = '$email'");
        $select -> execute();

        if($select -> rowCount() > 0){
            echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Warning",
            text: "Email is Alerady Exist",
            icon: "warning",
            button: "OK",
            });
        });
        </script>';
            
        }else{
            $insert = $pdo -> prepare("INSERT INTO `users`(`username`, `email`, `password`, `role`) VALUES (:name, :email, :pass, :role)");
    $insert -> bindParam(":name", $username);
    $insert -> bindParam(":email", $email);
    $insert -> bindParam(":pass", $password);
    $insert -> bindParam(":role", $role);
    if($insert -> execute()){
        echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Good Job!",
            text: "Registeration is Successfully",
            icon: "success",
            button: "OK",
            });
        });
        </script>';
    }else {
        
         echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Error!",
            text: "Registeration is Faild",
            icon: "warning",
            button: "OK",
            });
        });
        </script>';
    }
}

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
                    <div class="row">
                    <div class="col-md-4">
                      <!-- text input -->
                     <div class="form-group">
                    <label for="exampleInputPassword1">User Name</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Username" name="username" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" id="exampleInputPassword1" placeholder="Email" name="email" required>
                  </div>
                    <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
                  </div>
                    <!-- select -->
                      <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="selectOption" required> 
                            <option value="" disabled>Select Role</option>
                          <option>Admin</option>
                          <option>User</option>
                        </select>
                      </div>
                    <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="save">Save</button>
                </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                       <table class="table table-striped">
                           <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Option</th>
                            </tr>
                           </thead>
                           <tbody>
                            <?php
                               $select = $pdo -> prepare("SELECT * FROM users order by userId desc");
                               $select -> execute();
                               
                               while($row = $select -> fetch (PDO::FETCH_OBJ)){
                                   
                                   echo'
                                   <tr>
                                   <td>' . $row -> userId . '</td>
                                   <td>' . $row -> username . '</td>
                                   <td>' . $row -> email . '</td>
                                   <td>' . $row -> password . '</td>
                                   <td>' . $row -> role . '</td>
                                   <td> 
                                   <a href="registeration.php?id=' . $row -> userId.'" class="btn btn-danger" role="button" name="delete"><span class="glyphicon glyphicon-trash"  title="delete"><i class="fas fa-trash"></i></span></a>
                                   <a href="registeration.php?id=' . $row -> userId.'" class="btn btn-primary" role="button" name="edit"><span class="glyphicon glyphicon-trash"  title="edit"><i class="fas fa-edit"></i></span></a>
                                   
                                   </td>
                                   </tr>
                                   ';
                               }
                               ?>
                           </tbody>
                        </table>
                          
                      </div>
                    </div>
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