<?php session_start();?>
<?php 
//session_start();
include_once 'includes/connectDb.php';

if($_SESSION['email'] == "" OR $_SESSION['role'] == "User"){
    header('location:index.php');
    echo '<script>window.location = "index.php";</script>';
}
if($_SESSION['role'] == "Admin"){    
include_once 'header_admin.php';
}else {
    include_once 'header_user.php';
}

$id = $_POST["idd"];
//$id = $_GET["id"];
$sql = "DELETE FROM `products` WHERE id = $id";
$query = $pdo -> prepare($sql);
if($query -> execute()){
   echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Success",
          text: "Product Update Succssfully ",
          icon: "success",
          button: "OK",
          });
      });
      </script>';
}else{
    $error = '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "Updated Faild",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
      echo $error;
}


