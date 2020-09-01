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
$id = $_GET["id"];
//echo"______________________" . $id;
$select = $pdo -> prepare("SELECT * FROM products WHERE id =  $id ");
$select -> execute();
$row = $select -> fetch(PDO::FETCH_ASSOC);
$productId = $row["id"];
$productCode = $row["code"];
$productName = $row["name"];
$productCost = $row["cost"];
$productSale = $row["sale"];
$productStock = $row["stock"];
$productCategory = $row["categoryId"];
$productDescription = $row["description"];
$productImage = $row["image"];
//print_r($row);
 print_r("------------------------------------------" . $productCategory);
//echo ("-----------------------------") . print_r($row);
if(isset($_POST["edit"])){
    $productCode = $_POST["code"];
    $productName = $_POST["name"];
    $productCost = $_POST["cost"];
    $productSale = $_POST["sale"];
    $productStock = $_POST["stock"];
    $productDescription = $_POST["description"];
    $productCategory = $_POST["category"];
    $f_name = $_FILES['myFile']['name'];
    if(!empty($f_name)){
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
                    $update = $pdo -> prepare("UPDATE `products` SET `code`=:code,`name`=:name,`cost`=:cost,`sale`=:sale,`stock`=:stock,`description`=:description,`image`=:image,`categoryId`=:categoryId WHERE id = $id");
        var_dump($update);
        $update -> bindParam(":code", $productCode);
        $update -> bindParam(":name", $productName);
        $update -> bindParam(":cost", $productCost);
        $update -> bindParam(":sale", $productSale);
        $update -> bindParam(":stock", $productStock);
        $update -> bindParam(":description", $productDescription);
        $update -> bindParam(":image", $productImage);
        $update -> bindParam(":categoryId", $productCategory);
       //print_r($update);
        if($update -> execute()){
            echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Success",
          text: "Product Update Succssfully ",
          icon: "success",
          button: "OK",
          });
      });
      </script>';
             //echo '<script>window.location = "products.php";</script>';
            
        }else{
             print_r($update -> execute());
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
        
    }else{

        $update = $pdo -> prepare("UPDATE `products` SET `code`=:code,`name`=:name,`cost`=:cost,`sale`=:sale,`stock`=:stock,`description`=:description,`image`=:image,`categoryId`=:categoryId WHERE id = $id");
        //var_dump($update);
        $update -> bindParam(":code", $productCode);
        $update -> bindParam(":name", $productName);
        $update -> bindParam(":cost", $productCost);
        $update -> bindParam(":sale", $productSale);
        $update -> bindParam(":stock", $productStock);
        $update -> bindParam(":description", $productDescription);
        $update -> bindParam(":image", $productImage);
        $update -> bindParam(":categoryId", $productCategory);
       //print_r("------------------------------------------" . $productCategory);
        if($update -> execute()){
              //print_r($update);
            echo '<script type="text/javascript">jQuery(function validation(){
      swal({
          title: "Success",
          text: "Product Update Succssfully ",
          icon: "success",
          button: "OK",
          });
      });
      </script>';
        //echo '<script>window.location = "products.php";</script>';
        }else{
             //print_r($update -> execute());
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
    }
$select = $pdo -> prepare("SELECT * FROM products WHERE id =  $id ");
$select -> execute();
$row = $select -> fetch(PDO::FETCH_ASSOC);
$productId = $row["id"];
$productCode = $row["code"];
$productName = $row["name"];
$productCost = $row["cost"];
$productSale = $row["sale"];
$productStock = $row["stock"];
$productCategory = $row["categoryId"];
$productDescription = $row["description"];
$productImage = $row["image"];

  
    
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
           <form action="" method="post" enctype="multipart/form-data">
              <div class="row">
                   <div class="col-md-6">
                   <div class="form-group">
                    <label for="exampleInputPassword1">BarCode</label>
                    <input type="text" class="form-control" id="" placeholder="Barcode" name="code" value="<?php echo $productCode;?>" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Product</label>
                    <input type="text" class="form-control" id="" placeholder="Name" name="name" value="<?php echo $productName;?>" required>
                  </div>
                    <div class="form-group">
                    <label for="exampleInputPassword1">Cost</label>
                    <input type="number"  min="1" step="1" class="form-control" id="" placeholder="Cost" name="cost" value="<?php echo $productCode;?>" required>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Sale</label>
                    <input type="number"  min="1" step="1" class="form-control" id="" placeholder="Sale" name="sale" value="<?php echo $productSale;?>" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Stock</label>
                    <input type="number"  min="1" step="1" class="form-control" id="" placeholder="stock" name="stock" value="<?php echo $productStock;?>" required>
                  </div>
                 
               </div>
                   <div class="col-md-6">
                   <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category" required> 
                            <option value="" readonly disabled selected>Select Category</option>
                          <?php 
                            $select = $pdo -> prepare("SELECT * FROM categories order by categoryId desc");
                            $select -> execute();
                            while($row = $select -> fetch(PDO::FETCH_ASSOC)){
                                extract($row);
                            
                            ?>
                            
                            <option <?php if($row["categoryname"] == $productCategory) {?>
                                selected = "selected"
                                <?php }?> >
                              <?php echo $row["categoryname"];?></option>
                            
                            <?php }?>
                          
                        </select>
                </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <textarea type="text" class="form-control" id="" placeholder="description" name="description" required><?php echo $productDescription;?></textarea>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Photo</label>
                    <input type="file" class="input-group" name="myFile" >
                    <img src="img/products/<?php echo $productImage;?> " class="img-roumded" width="100px" height="100px" />
                    <p>Upload Image</p>
                    
                  </div>
               </div>
               </div>
               <div class="card-footer">
                 <a href="products.php" class="btn btn-danger" role="button" name="back"><span>back</span></a>
                  <button type="submit" class="btn btn-primary" name="edit">Update</button>
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