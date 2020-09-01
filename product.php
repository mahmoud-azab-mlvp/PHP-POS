<?php session_start();?>
<?php 
//session_start();
include_once 'includes/connectDb.php';

if($_SESSION['email'] == "" OR $_SESSION['role'] == "User"){
    header('location:index.php');
    echo '<script>window.location = "index.php";</script>';
}
if($_SESSION['role'] == "Admin"){    
  include_once ("includes/header.php");
  include_once ("includes/navbar.php");
  include_once ("includes/sidebar_admin.php");
}else {
  include_once ("includes/header.php");
  include_once ("includes/navbar.php");
  include_once ("includes/sidebar_user.php");
}

if(isset($_POST["save"])){
    $productCode = $_POST["code"];
    $productName = $_POST["name"];
    $productCost = $_POST["cost"];
    $productSale = $_POST["sale"];
    $productStock = $_POST["stock"];
    $productDescription = $_POST["description"];
    $productCategory = $_POST["category"];
    
    $f_name = $_FILES['myFile']['name'];
    $f_tmp = $_FILES['myFile']['tmp_name'];
    //$store = "upload/".$f_name;
    
    $f_size = $_FILES['myFile']['size'];
    
     $f_extension = explode('.',$f_name);
    //print_r($f_extension);
    $f_extension = strtolower(end($f_extension));
    $f_newFile = uniqid().'.'.$f_extension;
    $store = "img/products/".$f_newFile;
    //echo $f_tmp;
   // echo "Store" . $store;
    if($f_extension == "jpg" || $f_extension == "png" || $f_extension == "gif") {
        if($f_size >= 1000000) {
            //echo "max file should be 1MB";
            $error = '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "max file shoue be 1 mb",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
      echo $error;
        }else {
            if(move_uploaded_file($f_tmp, $store)){
                $productImage = $f_newFile;
                echo "uploaded";
                if(!isset($error)){
        $insert = $pdo -> prepare("INSERT INTO products (code, name, cost, sale, stock, description, image, categoryId ) VALUES(:code, :name, :cost, :sale, :stock, :description, :image, :categoryId)");
        $insert -> bindParam(":code", $productCode);
        $insert -> bindParam(":name", $productName);
        $insert -> bindParam(":cost", $productCost);
        $insert -> bindParam(":sale", $productSale);
        $insert -> bindParam(":stock", $productStock);
        $insert -> bindParam(":description", $productDescription);
        $insert -> bindParam(":image", $productImage);
        $insert -> bindParam(":categoryId", $productCategory);
       
        if($insert -> execute()){
             
            echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Success",
          text: "Product add Succssfully ",
          icon: "success",
          button: "OK",
          });
      });
      </script>';
        }else{
            $error = '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "error 3",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
      echo $error;
        }
        
    }
            }
        }
    }else{
        //echo "only jpg png gif can be upload";
        $error = '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Errror",
          text: "only jpg png gif can be upload",
          icon: "error",
          button: "OK",
          });
      });
      </script>';
      echo $error;
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
        <div class="box-header with-border">
                    <h3 class="box-title">blank</h3>
                </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="box-body">
          
           <form action="" method="post" enctype="multipart/form-data">
              <div class="row">
                   <div class="col-md-6">
                   <div class="form-group">
                    <label for="exampleInputPassword1">BarCode</label>
                    <input type="text" class="form-control" id="" placeholder="Barcode" name="code" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Product</label>
                    <input type="text" class="form-control" id="" placeholder="Name" name="name" required>
                  </div>
                    <div class="form-group">
                    <label for="exampleInputPassword1">Cost</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="" placeholder="Cost" name="cost" required>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Sale</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="" placeholder="Sale" name="sale" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Stock</label>
                    <input type="number" value="0" min="1" step="1" class="form-control" id="" placeholder="stock" name="stock" required>
                  </div>
                 
               </div>
                   <div class="col-md-6">
                   <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category" required> 
                            <option value="" readonly>Select Role</option>
                          <?php 
                            $select = $pdo -> prepare("SELECT * FROM categories ");
                            $select -> execute();
                            while($row = $select -> fetch(PDO::FETCH_ASSOC)){
                                extract($row);
                            
                            ?>
                            <option value="<?php echo $row["categoryId"];?>"><?php echo $row["categoryname"]?></option>
                            <?php }?>
                          
                        </select>
                </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <textarea type="text" class="form-control" id="" placeholder="description" name="description" required></textarea>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Photo</label>
                    <input type="file" class="input-group" name="myFile" required>
                    <p>Upload Image</p>
                    
                  </div>
               </div>
               </div>
               <div class="card-footer">
                 <a href="products.php" class="btn btn-danger" role="button" name="back"><span>back</span></a>
                  <button type="submit" class="btn btn-primary" name="save">Save</button>
                </div>
           </form>            
           
        </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
 <?php include_once ("includes/rightbar.php");?>

<!-- Main Footer -->
 <?php include_once ("includes/footer.php");?>