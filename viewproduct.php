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
                <!-- form start -->
                <div class="box-body">
            <?php
                $id = $_GET["id"];
            $select = $pdo -> prepare("SELECT * FROM `products` WHERE id = $id");
            $select -> execute();
            while($row = $select -> fetch(PDO::FETCH_OBJ)){
                echo '
                <div class="row">
               
                <div class="col-md-6"><ul class="list-group">
                 <center><p class="list-group-item list-group-item-success"><b>Product Detail</b></p></center>
              <li class="list-group-item d-flex justify-content-between align-items-center">
              <b>Id</b>
                <span class="badge badge-primary badge-pill">' .$row -> id .'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
              <b>Name</b>
                
                <span class="badge badge-primary badge-pill">'.$row -> name.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Cost</b>
                <span class="badge badge-primary badge-pill">'.$row -> cost.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Sale</b>
                <span class="badge badge-primary badge-pill">'.$row -> sale.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Profit</b>
                <span class="badge badge-primary badge-pill">'.($row -> sale - $row -> sale). '</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Description</b>
                <span class="badge badge-primary badge-pill">'.$row -> description.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Stock</b>
                <span class="badge badge-primary badge-pill">'.$row -> stock.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Category</b>
                <span class="badge badge-primary badge-pill">'.$row -> categoryId.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Description</b>
                <span class="badge badge-primary badge-pill">'.$row -> description.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Code</b>
                <span class="badge badge-primary badge-pill">'.$row -> code.'</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Date</b>
                <span class="badge badge-primary badge-pill">'.$row -> createdAt.'</span>
              </li>
            </ul></div>
                <div class="col-md-6"><ul class="list-group">
                 <center><p class="list-group-item list-group-item-success"><b>Product Image</b></p></center>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                  <img src="img/products/'. $row -> image .'" class="img-roumded" width="500px" height="600px" />
                   
                  </li>
                
                </ul></div>
            </div>
                ';
            }
            ?>
            
            
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