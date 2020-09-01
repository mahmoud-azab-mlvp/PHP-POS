<?php session_start(); ?>
<?php
include_once("includes/connectDb.php");
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
function fillProducts($pdo, $id) {
    $answer = '';
    $query = $pdo->prepare("SELECT * FROM products  ORDER BY name asc");
    $query->execute();
    $result = $query->fetchAll();
    foreach ($result as $key => $row) {
        # code...
        $answer  .= '<option value="'.$row["id"].'"';
        if($id == $row["id"]){
            $answer .= 'selected';
        }
        $answer .= '>'.$row["name"].'</option>';
    }
    return $answer;
}

$id = $_GET["id"];
$query = $pdo->prepare("SELECT * FROM invoices WHERE id = '$id'");
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

$documentNumber = $row["documentNumber"];
$customer = $row["customer"];
$phone = $row["phone"];
$date = date("Y-m-d", strtotime($row["date"]));
$subTotal = $row["subTotal"];
$tax = $row["tax"];
$discount = $row["discount"];
$netTotal = $row["netTotal"];
$paid = $row["paid"];
$due = $row["due"];
$paymentType = $row["paymentType"];
$query = $pdo->prepare("SELECT * FROM invoice_details WHERE invoiceId = '$id'");
$query->execute();
$rowInvoiceDetails = $query->fetchAll(PDO::FETCH_ASSOC);
//var_dump($query);
//var_dump($rowInvoiceDetails);
print_r($rowInvoiceDetails);
//var_dump($rowInvoiceDetails["productId"]);




if (isset($_POST["save"])) {

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

    $query = $pdo->prepare("INSERT INTO `invoices`( `documentNumber`, `customer`, `phone`, `date`, `subTotal`, `tax`, `discount`, `netTotal`, `paid`, `due`, `paymentType`) VALUES (:documentNumber, :customer, :phone, :date, :subTotal, :tax , :discount, :netTotal, :paid, :due, :paymentType)");
    $query->bindParam(":documentNumber", $documentNumber);
    $query->bindParam(":customer", $customer);
    $query->bindParam(":phone", $phone);
    $query->bindParam(":date", $date);
    $query->bindParam(":subTotal", $subTotal);
    $query->bindParam(":tax", $tax);
    $query->bindParam(":discount", $discount);
    $query->bindParam(":netTotal", $netTotal);
    $query->bindParam(":paid", $paid);
    $query->bindParam(":due", $due);
    $query->bindParam(":paymentType", $paymentType);
    $query->execute();
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
    $invoiceId = $pdo->lastInsertId();
    if ($invoiceId != null) {
        for ($i = 0; $i < count($arrProductId); $i++) {
            $remQty = $arrPorductStock[$i] - $arrProductQty[$i];
            if ($remQty < 0) {
                return "Order is Not completed";
            } else {
                $query = $pdo->prepare("UPDATE products SET stock = '$remQty' WHERE id = '$arrProductId[$i]'");
                $query->execute();
            }
            $query = $pdo->prepare("INSERT INTO `invoice_details`(`invoiceId`, `productId`, `code`, `name`, `price`, `qty`, `total`, `date`) VALUES (:invoiceId,:productId,:code,:name,:price,:qty,:total,:date)");
            $query->bindParam(":invoiceId", $invoiceId);
            $query->bindParam(":productId", $arrProductId[$i]);
            $query->bindParam(":code", $arrProductCode[$i]);
            $query->bindParam(":name", $arrPorductName[$i]);
            $query->bindParam(":price", $arrProductPrice[$i]);
            $query->bindParam(":qty", $arrProductQty[$i]);
            $query->bindParam(":total", $arrProductTotal[$i]);
            $query->bindParam(":date", $arrDate);
            $query->execute();
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
if(isset($_POST["update"])){
    // Steps for update order
    //1- get values from text feilds and form array in variables
    //Header and footer
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
    //Details
    $arrProductId = $_POST["productId"];
    $arrProductCode = $_POST["productCode"];
    $arrPorductName = $_POST["productName"];
    $arrPorductStock = $_POST["productStock"];
    $arrProductPrice = $_POST["productPrice"];
    $arrProductQty = $_POST["productQty"];
    $arrProductTotal = $_POST["productTotal"];
    //2- write update query for products stock
    foreach ($rowInvoiceDetails as $key => $itemInvoicDetails) {
        # code...
        $query = $pdo -> prepare("UPDATE products SET stock=stock+".$itemInvoicDetails['qty']." WHERE id = '".$itemInvoicDetails['productId']."'");
        $query -> execute();
    }
    
    //3- write delete query for invoices_details table date where invoiceId
    $query = $pdo -> prepare("DELETE FROM `invoice_details` WHERE invoiceId = $id");
    $query -> execute();
    // 4- 
    $query = $pdo -> prepare("UPDATE `invoices` SET `documentNumber`=:documentNumber,`customer`=:customer,`phone`=:phone,`date`=:date,`subTotal`=:subTotal,`tax`=:tax,`discount`=:discount,`netTotal`=:netTotal,`paid`=:paid,`due`=:due,`paymentType`=:paymentType WHERE invoiceId = $id");
    $query->bindParam(":documentNumber", $documentNumber);
    $query->bindParam(":customer", $customer);
    $query->bindParam(":phone", $phone);
    $query->bindParam(":date", $date);
    $query->bindParam(":subTotal", $subTotal);
    $query->bindParam(":tax", $tax);
    $query->bindParam(":discount", $discount);
    $query->bindParam(":netTotal", $netTotal);
    $query->bindParam(":paid", $paid);
    $query->bindParam(":due", $due);
    $query->bindParam(":paymentType", $paymentType);
    $query->execute();
    
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
    $invoiceId = $pdo->lastInsertId();
    if ($invoiceId != null) {
        for ($i = 0; $i < count($arrProductId); $i++) {
            
            // 5 - write select query for products table to get stock value
            $query = $pdo -> prepare("SELECT * FROM products WHERE id = '".$arrProductId[$i]."'");
            $query -> execute();
            while($rowProduct = $query -> fetch(PDO::FETCH_OBJ)){
                $dbStock[$i] = $rowProduct -> stock;
                 $remQty =  $dbStock[$i] - $arrProductQty[$i];
                if ($remQty < 0) {
                    return "Order is Not completed";
                } else {
                    //6-
                    $query = $pdo->prepare("UPDATE products SET stock = '$remQty' WHERE id = '$arrProductId[$i]'");
                    $query->execute();
                }
            }
            //7-
           
            $query = $pdo->prepare("INSERT INTO `invoice_details`(`invoiceId`, `productId`, `code`, `name`, `price`, `qty`, `total`, `date`) VALUES (:invoiceId,:productId,:code,:name,:price,:qty,:total,:date)");
            $query->bindParam(":invoiceId", $id);
            $query->bindParam(":productId", $arrProductId[$i]);
            $query->bindParam(":code", $arrProductCode[$i]);
            $query->bindParam(":name", $arrPorductName[$i]);
            $query->bindParam(":price", $arrProductPrice[$i]);
            $query->bindParam(":qty", $arrProductQty[$i]);
            $query->bindParam(":total", $arrProductTotal[$i]);
            $query->bindParam(":date", $date);
            $query->execute();
        }
        header('location: orders.php');
        echo '<script>window.location = "orders.php";</script>';
        echo "updte successfully";
    }

}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order Sale
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
                                            <input type="text" class="form-control" placeholder="Customer" name="customer" value="<?php echo $customer; ?>" required>
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
                                            <input type="text" class="form-control" placeholder="Phone" name="phone" value="<?php echo $phone; ?>" required>
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
                                            <input type="text" class="form-control" placeholder="Document No.." name="documentNumber" value="<?php echo $documentNumber; ?>" required>
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
                                            <input type="text" class="form-control pull-right" id="datepicker" name="date" value="<?php echo $date; ?>" data-date-format="yyyy-mm-dd">
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
                                            <button type="button" name="add" class="btn btn-info btn-sm btnAdd"><span><i class="fa fa-plus"></i></span></button>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                //var_dump($rowInvoiceDetails);
                                foreach ($rowInvoiceDetails as  $itemInvoiceDetails) {
                                    # code...
                                    //var_dump($itemInvoiceDetails);
                                    $query = $pdo->prepare("SELECT * FROM products WHERE id = '{$itemInvoiceDetails['productId']}'");
                                    $query->execute();
                                    $rowProduct = $query->fetch(PDO::FETCH_ASSOC);
                                    print_r($itemInvoiceDetails['productId']);
                                    //var_dump($rowProduct["code"]);
                                ?>
                                    <tr>
                                       <?php
                                       $key=1;
                                       echo '<td>'.'<input type="number" class="form-control " id="number" name="number" style="width:60px;" value="' . $key . '" min="1" required>' . '</td>';
                                       echo '<td>'.'<select class="form-control productIdEdit" name="productId[]" style="width: 250px;><option value="">Select Options</option> '.fillProducts($pdo, $itemInvoiceDetails["productId"]).'</select>'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productCode" name="productCode[]"  value="'.$itemInvoiceDetails["code"].'" required>'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productName" name="productName[]" value="'.$itemInvoiceDetails["name"].'" readonly>'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productStock" name="productStock[]" value="'.$rowProduct["stock"].'" readonly>'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productPrice" name="productPrice[]" value="'.$itemInvoiceDetails["price"].'" >'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productQty" name="productQty[]" value="'.$itemInvoiceDetails["qty"].'" >'.'</td>';
                                       echo '<td>'.'<input type="text" class="form-control productTotal" name="productTotal[]" value="'.$itemInvoiceDetails["total"].'" readonly>'.'</td>';
                                       echo '<td>'.'<center>'.'<button type="button" name="remove" class="btn btn-danger btn-sm btnRemove"><span><i class="fa fa-times-circle"></i></span></button>'.'</center>'.'</td>';
                                       $key= $key +1;
                                       ?>
                                    </tr>
                                <?php } ?>

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
                                    <input type="number" class="form-control" placeholder="SubTotal" id="subTotal" name="subTotal" value="<?php echo $subTotal; ?>" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tax</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Tax" id="tax" name="tax" value="<?php echo $tax; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Discount</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Discount" id="discount" value="<?php echo $discount; ?>" name="discount" required>
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
                                    <input type="number" class="form-control" placeholder="Total" id="netTotal" name="netTotal" value="<?php echo $netTotal; ?>" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Paid</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Paid" id="paid" name="paid" value="<?php echo $paid; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Due</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Due" id="due" name="due" value="<?php echo $due; ?>" required readonly>
                                </div>
                                <br>
                                <!-- radio -->
                                <label for="">Payment Method</label>
                                <div class="form-group">
                                    <label>
                                        Cash
                                        <input type="radio" name="rb" class="minimal" value="Cash" <?php echo ($paymentType == "Cash") ? 'checked' : '' ?>>
                                    </label>
                                    <label>
                                        Card
                                        <input type="radio" name="rb" class="minimal" value="Card" <?php echo ($paymentType == "Card") ? 'checked' : '' ?>>
                                    </label>
                                    <label>
                                        Check
                                        <input type="radio" name="rb" class="minimal" value="Check" <?php echo ($paymentType == "Check") ? 'checked' : '' ?>>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div align="center">
                    <input type="submit" class="btn btn-success" name="update" value="Update">
                </div>
            </form>
        </div>
    </section>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- scripts -->
<!-- Main Footer -->
<?php include_once("includes/footbar.php") ?>
<!-- Control Sidebar -->
<?php include_once("includes/outbar.php") ?>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
<?php include_once("includes/footer.php") ?>
<script>
    $(document).ready(function() {
        $('.productIdEdit').select2();
            $(".productIdEdit").on("change", function(e) {
                var productId = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: "get_product.php",
                    method: "get",
                    data: {
                        id: productId
                    },
                    dataType: "json",
                    success: function(data) {
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
        $(document).on("click", ".btnAdd", function() {
            var key = 1;
            var html = '';
            html += '<tr>';
            html += '<td>' + '<input type="number" class="form-control " id="number" name="number" style="width:60px;" value="' + key + '" min="1" required>' + '</td>';
            html += '<td>' + '<select class="form-control productId" name="productId[]" style="width:250px;height:70px;"><option value="">select</option><?php echo fillProducts($pdo,''); ?></select>' + '</td>';
            html += '<td>' + '<input type="text" class="form-control productCode" name="productCode[]" required>' + '</td>';
            html += '<td>' + '<input type="text" class="form-control productName" name="productName[]" required>' + '</td>';
            html += '<td>' + '<input type="text" class="form-control productStock" name="productStock[]" required readonly>' + '</td>';
            html += '<td>' + '<input type="text" class="form-control productPrice" name="productPrice[]" required>' + '</td>';
            html += '<td>' + '<input type="number" class="form-control productQty" name="productQty[]" required>' + '</td>';
            html += '<td>' + '<input type="text" class="form-control productTotal" id ="productTotal" name="productTotal[]" required readonly>' + '</td>';
            html += '<td>' + '<center>' + '<button type="button" name="add" class="btn btn-danger btn-sm btnRemove"><span><i class="fa fa-times-circle"></i></span></button>' + '</center>' + '</td>'
            key = key + 1;
            html += '</tr>';
            $("#tblProducts").append(html);
            $('.productId').select2();
            $(".productId").on("change", function(e) {
                var productId = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: "get_product.php",
                    method: "get",
                    data: {
                        id: productId
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log("data", data);
                        tr.find(".productCode").val(data["code"]);
                        tr.find(".productName").val(data["name"]);
                        tr.find(".productStock").val(data["stock"]);
                        tr.find(".productPrice").val(data["sale"]);
                        tr.find(".productQty").val(1);
                        var produtQty = tr.find(".productQty").val();
                        var procutPrice = tr.find(".productPrice").val();
                        tr.find(".productTotal").val(produtQty * procutPrice);
                        $("#paid").val("");
                        calculate(0, 0);
                        $("#paid").val("");
                    }
                })
            });
        });
        $(document).on("click", ".btnRemove", function() {
            $(this).closest("tr").remove();
            $("#paid").val("");
            calculate(0, 0);
            
        })
        $("#tblProducts").delegate(".productQty", "keyup change", function() {
            var qty = $(this);
            var tr = $(this).parent().parent();

            if ((qty.val() - 0) > (tr.find(".productStock").val() - 0)) {

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
                $("#paid").val("");
                calculate(0, 0);
                 
            } else {
                var produtQty = tr.find(".productQty").val();
                var procutPrice = tr.find(".productPrice").val();
                tr.find(".productTotal").val(produtQty * procutPrice);
                 $("#paid").val("");
                calculate(0, 0);
                
            }
        });

        function calculate(dis, paid) {
            var subTotal = 0;
            var tax = 0;
            var discount = dis;
            var netTotal = 0;
            var paidAmount = paid;
            var tr = $(this).parent().parent();
            $(".productTotal").each(function() {
                subTotal = subTotal + ($(this).val() * 1);
            });
            tax = 0.05 * subTotal;
            netTotal = subTotal + tax
            netTotal = netTotal - discount;
            due = paidAmount - netTotal;

            $("#subTotal").val(subTotal.toFixed(2));
            $("#tax").val(tax.toFixed(2));
            $("#netTotal").val(netTotal.toFixed(2));
            $("#discount").val(discount);
            $("#due").val(due.toFixed(2))

        }
        $("#discount").keyup(function() {
            var discount = $(this).val();
            calculate(discount, 0);
             $("#paid").val("");
        });
        $("#paid").keyup(function() {
            var paid = $(this).val();
            var discount = $("#discount").val();
            calculate(discount, paid);
        })

    });
</script>