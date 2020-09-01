<?php session_start(); ?>
<?php
include_once("includes/connectDb.php");
//session_start();
if ($_SESSION['email'] == "" or $_SESSION["role"] == "User") {
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
              <!-- form start -->
                    <div class="col-md-12">
                      <div class="form-group">
                       <div style="overflow-x: auto;">
                          <table class="table table-striped " id="tblOrders">

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>DocNumber</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Tax</th>
                                            <th>Discount</th>
                                            <th>Net Total</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                            <th>Payment</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select = $pdo->prepare("SELECT * FROM invoices order by id desc ");
                                        $select->execute();

                                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                                            echo '
                                   <tr>
                                   <td>' . $row->id . '</td>
                                   <td>' . $row->documentNumber . '</td>
                                   <td>' . $row->customer . '</td>
                                   <td>' . $row->date . '</td>
                                   <td>' . $row->subTotal . '</td>
                                   <td>' . $row->tax . '</td>
                                   <td>' . $row->discount . '</td>
                                   <td>' . $row->netTotal . '</td>
                                   <td>' . $row->paid . '</td>
                                   <td>' . $row->due . '</td>
                                   <td>' . $row->paymentType . '</td>

                                   <td>
                                   <a href="print_order.php?id=' . $row->id . '" class="btn btn-warning" role="button" name="print"><span class="glyphicon glyphicon-print" style="color:#fffff" data-toggle="tooltip" title="print"></span></a>
                                   <a href="print_order_a2.php?id=' . $row->id . '" class="btn btn-warning" role="button" name="print"><span class="glyphicon glyphicon-print" style="color:#fffff" data-toggle="tooltip" title="print"></span></a>
                                   <a href="vieworder.php?id=' . $row->id . '" class="btn btn-info" role="button" name="view"><span class="glyphicon glyphicon-eye-open" style="color:#fffff" data-toggle="tooltip" title="view"></span></a>
                                   <a href="editorder.php?id=' . $row->id . '" class="btn btn-primary" role="button" name="edit"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tooltip" title="edit"></span></a>
                                   <button id=' . $row->id . ' class="btn btn-danger btnDelete"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="delete"></span></button>
                                
                                   
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
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script>
  $(document).ready(function() {

    $('.btnDelete').click(function() {
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
              data: {
                idd: id
              },
              type: "post",
              success: function(data) {
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
<script>
  $(document).ready(function() {

    $('.btnDelete').click(function() {
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
              url: "deleteorder.php",
              data: {
                idd: id
              },
              type: "post",
              success: function(data) {
                tdh.parents('tr').hide();
                console.log(id);

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