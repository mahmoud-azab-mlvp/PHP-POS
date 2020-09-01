<?php session_start();?>
<?php 
//session_start();
include_once("includes/connectDb.php");

if($_SESSION['email'] == "" OR $_SESSION['role'] == "User"){
    header('location:index.php');
    echo '<script>window.location = "index.php";</script>';
}
if($_SESSION['role'] == "Admin"){    
include_once("includes/header.php");
  include_once("includes/navbar.php");
  include_once("includes/sidebar_admin.php");
}else {
   include_once("includes/header.php");
  include_once("includes/navbar.php");
  include_once("includes/sidebar_user.php");
}
if(isset($_POST["delete"])){
    $delete = "DELETE FROM products WHERE id = ''";
    if($delete -> execute()){
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
            <div class="box-header with-border">
          <h3 class="box-title">blank</h3>
        </div>
        <!-- form start -->
        <div class="box-body">
              <!-- /.card-header -->
              <!-- form start -->
                    <div class="col-md-12">
                      <div class="form-group">
                       <div style="overflow-x: auto;">
                       <table class="table table-striped" id="tblProducts">
                           <thead>
                            <tr>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Cost</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Caregory</th>
                                <th>Description</th>
                                <th>Option\</th>
                            </tr>
                           </thead>
                           <tbody>
                            <?php
                               $select = $pdo -> prepare("SELECT * FROM products order by id ");
                               $select -> execute();
                               
                               while($row = $select -> fetch (PDO::FETCH_OBJ)){
                                   
                                   echo'
                                   <tr>
                                   <td>' . $row -> id . '</td>
                                   <td>' . $row -> code . '</td>
                                   <td>' . $row -> name . '</td>
                                   <td>' . $row -> cost . '</td>
                                   <td>' . $row -> sale . '</td>
                                   <td>' . $row -> stock . '</td>
                                   <td><img src="img/products/'. $row -> image .'" class="img-roumded" width="40px" height="40px" /></td>
                                   <td>' . $row -> categoryId . '</td>
                                   <td>' . $row -> description . '</td>

                                   <td>
                                   <a href="viewproduct.php?id=' . $row -> id.'" class="btn btn-info" role="button" name="view"><span class="glyphicon glyphicon-eye-open" style="color:#fffff" data-toggle="tooltip" title="view"><i class="far fa-list-alt"></i></span></a>
                                   <a href="editproduct.php?id=' . $row -> id.'" class="btn btn-primary" role="button" name="edit"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip" title="edit"><i class="fas fa-edit"></i></span></a>
                                   <button id='. $row -> id.' class="btn btn-danger btnDelete"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="delete"></span></button>
                                
                                   
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
                </div>
      </div>
    </section>
  
  </div>
  <!-- /.content-wrapper -->
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
<?php include_once("includes/footer.php") ?>

<script>
  $(document).ready( function () {
    $('#tblProducts').DataTable({
        "order": [[0, "desc"]]
    });
} );
</script>
<script>
  $(document).ready( function () {
   $('[data-toggle="tooltip"]').tooltip();
} );
</script>
<script>
   $(document).ready( function () {
       
     $('.btnDelete').click(function(){
        //alert("Hello");
         var tdh = $(this);
         var id = $(this).attr("id");
         var data = new FormData();
         data.append("id", id);
         //ert(id);
         swal({
  title: "Are you sure Do you want to Delete?",
  text: "Once deleted, you will not be able to recover this imaginary file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      $.ajax({
             url: "deleteproduct.php",
             data : {
                 idd: id
             },
             type: "post",
             success: function(data){
                 tdh.parents('tr').hide();
                 //console.log(id);
                 
             }
         })
      
    swal("Poof! Your imaginary file has been deleted!", {
      icon: "success",
    });
  } else {
    swal("Your imaginary file is safe!");
  }
});
         
    });
   });
</script>
<!-- Main Footer -->
 <?php include_once ("includes/footer.php");?>