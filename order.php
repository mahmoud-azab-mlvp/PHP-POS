<?php session_start();?>
<?php
include_once("includes/connectDb.php");
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
function fillProducts($pdo){
  $answer = '';
   $query= $pdo -> prepare("SELECT * FROM products ORDER BY name asc");
  $query -> execute();
  $result = $query -> fetchAll();
  foreach($result as $row){
    $answer .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
  }
  return $answer;
}
if(isset($_POST["save"])){

  $documentNumber = $_POST["documentNumber"];
  $customer = $_POST["customer"];
  $phone = $_POST["phone"];
  $date = date("Y-m-d", strtotime($_POST["date"]));
  $subTotal = $_POST["subTotal"];
  $tax = $_POST["tax"];
  $discount = $_POST["discount"];
  $netTotal = $_POST["netTotal"];
  $paid = $_POST["paid"];
  $due = $_POST["due"];
  $paymentType = $_POST["rb"];

  $query = $pdo -> prepare("INSERT INTO `invoices`( `documentNumber`, `customer`, `phone`, `date`, `subTotal`, `tax`, `discount`, `netTotal`, `paid`, `due`, `paymentType`) VALUES (:documentNumber, :customer, :phone, :date, :subTotal, :tax , :discount, :netTotal, :paid, :due, :paymentType)");
  $query -> bindParam(":documentNumber", $documentNumber);
  $query -> bindParam(":customer", $customer);
  $query -> bindParam(":phone", $phone);
  $query -> bindParam(":date", $date);
  $query -> bindParam(":subTotal", $subTotal);
  $query -> bindParam(":tax", $tax);
  $query -> bindParam(":discount", $discount);
  $query -> bindParam(":netTotal", $netTotal);
  $query -> bindParam(":paid", $paid);
  $query -> bindParam(":due", $due);
  $query -> bindParam(":paymentType", $paymentType);
  $query -> execute();
  //Details
  $arrProductId = $_POST["productId"];
  $arrProductCode = $_POST["productCode"];
  $arrPorductName = $_POST["productName"];
  $arrPorductStock = $_POST["productStock"];
  $arrProductPrice = $_POST["productPrice"];
  $arrProductQty = $_POST["productQty"];
  $arrProductTotal = $_POST["productTotal"];
  $arrDate = $date;
  // 2rd in
  $invoiceId = $pdo -> lastInsertId();
  if($invoiceId != null){
  for ($i=0; $i < count($arrProductId); $i++) {
    $remQty = $arrPorductStock[$i] - $arrProductQty[$i];
    if($remQty <0){
      return "Order is Not completed";
    }else{
      $query = $pdo -> prepare("UPDATE products SET stock = '$remQty' WHERE id = '$arrProductId[$i]'");
      $query -> execute();

    }
  $query = $pdo -> prepare("INSERT INTO `invoice_details`(`invoiceId`, `productId`, `code`, `name`, `price`, `qty`, `total`, `date`) VALUES (:invoiceId,:productId,:code,:name,:price,:qty,:total,:date)");
  $query -> bindParam(":invoiceId", $invoiceId);
  $query -> bindParam(":productId", $arrProductId[$i]);
  $query -> bindParam(":code", $arrProductCode[$i]);
  $query -> bindParam(":name", $arrPorductName[$i]);
  $query -> bindParam(":price", $arrProductPrice[$i]);
  $query -> bindParam(":qty", $arrProductQty[$i]);
  $query -> bindParam(":total", $arrProductTotal[$i]);
  $query -> bindParam(":date", $arrDate);
  $query -> execute();
    }
    
  }
  echo '<script type="text/javascript">jQuery(function validation(){
    swal({
        title: "Good job!",
        text: "Details Matched!",
        icon: "success",
        button: "Loading ....!",
        });
    });
    </script>';
  
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
      <form action="order.php" method="post" name="">
        <div class="box-header with-border">
          <h3 class="box-title">Create Order</h3>
        </div>
        <div class="box-body">
           <div class="row">
            <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Client</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                        </div>
                    <input type="text" class="form-control"  placeholder="Customer" name="customer" required>
                    </div>
                  </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Mobile</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                        </div>
                    <input type="text" class="form-control"  placeholder="Phone" name="phone" required>
                    </div>
                  </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="form-group">
                    <label for="exampleInputPassword1">Document</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                        </div>
                    <input type="text" class="form-control"  placeholder="Document No.." name="documentNumber" required>
                    </div>
                  </div>
                   </div>
                   <div class="col-md-6">
                     <!-- Date -->
              <div class="form-group">
                <label>Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker" name="date" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                </div>
                <!-- /.input group -->
              </div>
                   </div>
                 </div>
              <!-- /.form group -->
            </div>

           </div>
        </div>
        <div class="box-body">
            <div class="col-md-12">
              <div style="overflow-x: auto;">
                <table class="table table-striped" id="tblProducts">
                   <thead>
                    <tr>
                        <th>#</th>
                        <th>Id</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>
                             <button type="button" name="add" class="btn btn-success btn-sm btnAdd"><span><i class="fa fa-plus"></i></span></button>
                        </th>
                    </tr>
                   </thead>
                </table>
              </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group">
                    <label for="exampleInputPassword1">SubTotal</label>
                        <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                        <input type="number" class="form-control"  placeholder="SubTotal" id="subTotal" name="subTotal" required readonly>
                        </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Tax</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                    <input type="number" class="form-control"  placeholder="Tax" id="tax" name="tax" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Discount</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                    <input type="number" class="form-control" placeholder="Discount" id="discount" name="discount" 
                    required>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                    <label for="exampleInputPassword1">Total</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                    <input type="number" class="form-control"  placeholder="Total" id="netTotal" name="netTotal" required readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Paid</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                    <input type="number" class="form-control" placeholder="Paid" id="paid" name="paid" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Due</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-usd"></i>
                        </div>
                    <input type="number" class="form-control" placeholder="Due" id="due" name="due" required readonly>
                    </div>
                    <br>
                    <!-- radio -->
                    <label for="">Payment Method</label>
              <div class="form-group">
                <label>
                  Cash
                  <input type="radio" name="rb" class="minimal" value="Cash" checked>
                </label>
                <label>
                  Card
                  <input type="radio" name="rb" class="minimal" value="Card">
                </label>
                <label>
                  Check
                  <input type="radio" name="rb" class="minimal" value="Check">
                </label>
              </div>
                  </div>
                </div>

            </div>
        </div>
        <div align="center">
          <input type="submit" class="btn btn-info" name="save" value="Save">
        </div>
      </form>
    </div>
    </section>
    
</div>

    <!-- Main content -->
    
    <!-- /.content -->
  <!-- /.content-wrapper -->

  <!-- scripts -->
  <!-- Main Footer -->
  <?php include_once("includes/footbar.php")?>
  <!-- Control Sidebar -->
  <?php include_once("includes/outbar.php")?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
<?php include_once("includes/footer.php")?>
<script>
  $(document).ready(function () {
    $(document).on("click", ".btnAdd", function(){
        var key = 1;
        var html = '';
        html += '<tr>';
        html += '<td>' + '<input type="number" class="form-control " id="number" name="number" style="width:60px;" value="'+key+'" min="1" required>' + '</td>';
        html += '<td>' + '<select class="form-control select2 productId" name="productId[]" style="width:250px;height:70px;"><option value="">select</option><?php echo fillProducts($pdo);?></select>' + '</td>';
        html += '<td>' + '<input type="text" class="form-control productCode" name="productCode[]" required>' + '</td>';
        html += '<td>' + '<input type="text" class="form-control productName" name="productName[]" required>' + '</td>';
        html += '<td>' + '<input type="text" class="form-control productStock" name="productStock[]" required readonly>' + '</td>';
        html += '<td>' + '<input type="text" class="form-control productPrice" name="productPrice[]" required>' + '</td>';
        html += '<td>' + '<input type="number" class="form-control productQty" name="productQty[]" required>' + '</td>';
        html += '<td>' + '<input type="text" class="form-control productTotal" id ="productTotal" name="productTotal[]" required readonly>' + '</td>';
        html += '<td>' + '<center>' + '<button type="button" name="add" class="btn btn-danger btn-sm btnRemove"><span><i class="fa fa-times-circle"></i></span></button>' +'</center>'+ '</td>'
        key  += 1;
        html += '</tr>';
        key +=1
        $("#tblProducts").append(html);
        $('.select2').select2();
        $(".productId").on("change", function (e) { 
          var productId = this.value;
          var tr = $(this).parent().parent();
          $.ajax({
            url: "get_product.php",
            method: "get", 
            data: {id: productId},
            dataType: "json",
            success: function (data) {
              console.log("data", data);
              tr.find(".productCode").val(data["code"]);
              tr.find(".productName").val(data["name"]);
              tr.find(".productStock").val(data["stock"]);
              tr.find(".productPrice").val(data["sale"]);
              tr.find(".productQty").val(1);
              var produtQty = tr.find(".productQty").val();
              var procutPrice = tr.find(".productPrice").val();
              tr.find(".productTotal").val(produtQty * procutPrice);
              calculate(0, 0);
                $("#paid").val("");
              }
          })
         });
    });
    $(document).on("click", ".btnRemove", function(){
        $(this).closest("tr").remove();
        calculate(0, 0);
        $("#paid").val("");
    })
    $("#tblProducts").delegate(".productQty", "keyup change", function(){
      var qty = $(this);
      var tr = $(this).parent().parent();
      
      if((qty.val() - 0) > (tr.find(".productStock").val() - 0)) {
        
        swal({
            title: "warning!",
            text: "Please fill feild",
            icon: "warning",
            button: "OK",
            });
        qty.val(1);
        var produtQty = tr.find(".productQty").val();
        var procutPrice = tr.find(".productPrice").val();
        tr.find(".productTotal").val(produtQty * procutPrice);
        calculate(0, 0);
        $("#paid").val("");
      }else{
        var produtQty = tr.find(".productQty").val();
        var procutPrice = tr.find(".productPrice").val();
        tr.find(".productTotal").val(produtQty * procutPrice);
        calculate(0, 0);
        $("#paid").val("");
      }
    });
    function calculate(dis, paid) {
      var subTotal = 0;
      var tax = 0;
      var discount = dis;
      var netTotal = 0;
      var paidAmount = paid;
      var tr = $(this).parent().parent();
      $(".productTotal").each(function(){
        subTotal = subTotal + ($(this).val() * 1);
      });
      tax = 0.05 * subTotal;
      netTotal = subTotal + tax 
      netTotal = netTotal - discount;
      due =   paidAmount - netTotal ;

      $("#subTotal").val(subTotal.toFixed(2));
      $("#tax").val(tax.toFixed(2));
      $("#netTotal").val(netTotal.toFixed(2));
      $("#discount").val(discount);
      $("#due").val(due.toFixed(2))
    
      }
      $("#discount").keyup(function () {
        var discount = $(this).val();
        $("#paid").val("");
        calculate(discount, 0);
       
      });
      $("#paid").keyup(function () {
        var paid = $(this).val();
        var discount = $("#discount").val();
        calculate(discount, paid);
        })
    
  });
</script>
<script>
 //Date picker
    $('#datepicker').datepicker({
     timePicker: true,    
      
    })
</script>