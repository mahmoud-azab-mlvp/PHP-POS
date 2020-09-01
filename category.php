<?php session_start();?>
<?php
include_once 'includes/connectDb.php';
//session_start();
if($_SESSION['email'] == "" OR $_SESSION["role"] == "User"){
    header('location:index.php');
    echo '<script>window.location = "index.php";</script>';
}
if ($_SESSION['role'] == "Admin") {
  include_once ("includes/header.php");
  include_once ("includes/navbar.php");
  include_once ("includes/sidebar_admin.php");
} else {
  include_once ("includes/header.php");
  include_once ("includes/navbar.php");
  include_once ("includes/sidebar_user.php");
}



if (isset($_POST['save'])) {
  $category = $_POST['categoryname'];
  //echo $category ;
  if (empty($category)) {
    $error =  '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Error!",
            text: "Please fill feild",
            icon: "error",
            button: "OK",
            });
        });
        </script>';
    echo $error;
  }
  if (!isset($error)) {

    if (isset($_POST['categoryname'])) {
      $select = $pdo->prepare("SELECT * FROM categories WHERE categoryname = '$category'");
      $select->execute();
      if ($select->rowCount() > 0) {
        echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Warning",
            text: "Category is Alerady Exist",
            icon: "warning",
            button: "OK",
            });
        });
        </script>';
      } else {
        $insert = $pdo->prepare("INSERT INTO categories (categoryname) VALUES (:name)");
        $insert->bindParam(":name", $category);
        if ($insert->execute()) {
          echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Good Job",
            text: "Category is Successfully",
            icon: "success",
            button: "OK",
            });
        });
        </script>';
        }
      }
    } else {
    }
  } else {
    echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Errror",
            text: "Error occured",
            icon: "error",
            button: "OK",
            });
        });
        </script>';
  }
}
if (isset($_POST['edit'])) {
  $select = $pdo->prepare("SELECT * FROM categories WHERE categoryId=" . $_POST['edit']);
  $select->execute();
  if ($select) {
  }
} else {
}
if(isset($_POST['update'])){
  $id = $_POST['categoryId'];
  $name = $_POST['categoryName'];
  if(empty($name)){
    $error = '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "Error occured",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
      echo $error;
  }
  if(!isset($error)){
    $update = $pdo->prepare("UPDATE categories SET categoryname='$name' WHERE categoryId='$id'");
    $update ->bindParam(":categoryName", $name);
    if($update -> execute()){
      echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Good Job",
            text: "Category is Successfully",
            icon: "success",
            button: "OK",
            });
        });
        </script>';
    }else{
      echo '<script type="text/javascript">jQuery(function validation(){
        swal({
            title: "Errror",
            text: "Error occured",
            icon: "error",
            button: "OK",
            });
        });
        </script>';
    }
  }
}
if (isset($_POST['delete'])) {
  $delete = $pdo->prepare("DELETE FROM categories WHERE categoryId=" . $_POST['delete']);
  if($delete -> execute()){
    echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Good Job",
          text: "Category is Successfully",
          icon: "success",
          button: "OK",
          });
      });
      </script>';
  }else {
    echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "Error occured",
          icon: "error",
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
  <!-- /.content-header -->
  <!--Your page content-->
  <section class="content container-fluid">
    <div class="box box-warning">
      <form action="" method="post" name="">
            <div class="box-header with-border">
          <h3 class="box-title">blank</h3>
        </div>
        <!-- form start -->
        <div class="box-body">
        <form role="form" action="" method="post">
          <?php if(isset($_POST['edit'])){
            $select = $pdo->prepare("SELECT * FROM categories WHERE categoryId = ".$_POST['edit']);
            $select -> execute();
            if($select){
              $row = $select -> fetch(PDO::FETCH_OBJ);
              echo '<div class="col-md-4">
            <!-- text input -->
            <div class="form-group">
              <input type="hidden" value='.$row -> categoryId .' class="form-control" id="exampleInputPassword1" placeholder="CategoryId" name="categoryId">
              <label for="exampleInputPassword1">Category Name</label>
              <input type="text" value='. $row -> categoryname .' class="form-control" id="exampleInputPassword1" placeholder="Categoryname" name="categoryName">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-info" name="update">Update</button>
            </div>
          </div>';
            }
          }else{
            echo '<div class="col-md-4">
            <!-- text input -->
            <div class="form-group">
              <label for="exampleInputPassword1">Category Name</label>
              <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Categoryname" name="categoryname">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary" name="save">Save</button>
            </div>
          </div>';
          }
          ?>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <table id="tblCateogry" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Option</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $select = $pdo->prepare("SELECT * FROM categories order by categoryId desc");
                    $select->execute();

                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                      echo '
                                   <tr>
                                   <td>' . $row->categoryId . '</td>
                                   <td>' . $row->categoryname . '</td>
                                   <td>
                                   <button type="submit" value="' . $row->categoryId . '" class="btn btn-danger" name="delete"><i class="fas fa-trash"></i>Delete</button>
                                    <button type="submit" value="' . $row->categoryId . '" class="btn btn-success" name="edit"><i class="fas fa-edit">Edit</i></button>
                                    
                                   
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
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
 <?php include_once ("includes/outbar.php");?>
<!-- 3 You get a fully interactive table  -->
<script>
  $(document).ready( function () {
    $('#tblCateogry').DataTable();
} );
</script>
<!-- Main Footer -->
 <?php include_once ("includes/footer.php");?>