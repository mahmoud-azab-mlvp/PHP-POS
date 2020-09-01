// $(document).ready(function () {
//     $(document).on("click", ".btnAdd", function(){
//         var key = 1;
//         var html = '';
//         html += '<tr>';
//         html += '<td>' + '<input type="number" class="form-control " id="number" name="number" value="'+key+'" min="1" required>' + '</td>';
//         html += '<td>' + '<select class="form-control productId" name="productId[]" id=""><option value="">select</option><?php echo fillProducts($pdo);?></select>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productCode" name="productCode[]" required>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productName" name="productName[]" required>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productStock" name="productStock[]" required readonly>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productPrice" name="productPrice[]" required>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productQty" name="productQty[]" required>' + '</td>';
//         html += '<td>' + '<input type="text" class="form-control productTotal" name="productTotal[]" required readonly>' + '</td>';
//         html += '<td>' + '<center>' + '<button type="button" name="add" class="btn btn-danger btn-sm btnRemove"><span><i class="fa fa-times-circle"></i></span></button>' +'</center>'+ '</td>'
//         key  += 1;
//         html += '</tr>';
//         key +=1
//         $("#tblProducts").append(html);
       
//     });
//     $(document).on("click", ".btnRemove", function(){
//         $(this).closest("tr").remove();
//     })
//     $(document).on("click", ".btnAdd", function () {
//         //key ++
//       })
//   });