<? 
	require_once("inc/global.php");
	require_once("inc/validateuser.php");
	require_once(MODEL_PATH . SIZINGMODEL);
	require_once(MODEL_PATH . MATERIALMODEL);
	require_once(MODEL_PATH . JOBTYPEMODEL);
	require_once(MODEL_PATH . CUSTOMERMODEL);
	require_once(MODEL_PATH . UOMMODEL);
	require_once(CONTROLLER_PATH . CONTROLNOCONTROLLER);
	require_once(CONTROLLER_PATH . ESTIMATECONTROLLER);
?>
<!DOCTYPE html><html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title><?=$title;?></title>
	<meta name="description" content="Clothes Station">
	<meta name="author" content="Clothes Station">
	<meta name="keyword" content="Clothes">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->
	
	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->
	
	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->
		
	<!-- start: Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	<!-- end: Favicon -->
		
</head>
<script type="text/javascript">
	function generateRandomString(length){
		var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		var result = '';
		for(var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
		return result;
	}
	function AddItem(){
		var itemArr = "";
		var size = $("#txtSize").val();
		var piece = $("#txtPieces").val();
		var color = $("#txtColor").val();
		var uom = $("#txtUOM").val();
		var materials = $("#txtMaterials").val();
		var spec = $("#txtSpecification").val();
		var nItemArr = "";
		var Items = "";
		
		if($("#txtItemArr").val() != ""){
			Items += $("#txtItemArr").val() + "::";
		}
		Items += "" + "||" + size + "||" + piece + "||" + color + "||" + uom + "||" + materials + "||" + spec + "::";
		Items = Items.slice(0, -2);
		nItemArr = Items.split("::");

		var cnt = 1;
		var tbl = "";
		tbl += '<table class="table table-bordered table-condensed">';
			tbl += '<tr>';
			  tbl += '<th>#</th>';
			  tbl += '<th>SIZES</th>';
			  tbl += '<th>PCS</th>';
			  tbl += '<th>COLOR</th>';
			  tbl += '<th>UOM</th>';
			  tbl += '<th>MATERIALS</th>';
			  tbl += '<th>SPECIFICATION</th>';
			  tbl += '<th>REMOVE</th>';
			tbl += '</tr>';
		var nItemArray = "";
		// id | sizeid | size | qty | color | uomid | uom | material | specification
		for(var i=0;i<nItemArr.length;i++){
			var id = generateRandomString(6);
			if(nItemArr[i] != ""){
				var item = nItemArr[i].split("||");
				console.log(nItemArr[i]);
				tbl += '<tr>';
				  tbl += '<td align="center">' + cnt + '</td>';
				  tbl += '<td>' + item[2] + '</td>';
				  tbl += '<td align="center">' + item[3] + '</td>';
				  tbl += '<td>' + item[4] + '</td>';
				  tbl += '<td>' + item[6] + '</td>';
				  tbl += '<td>' + item[7] + '</td>';
				  tbl += '<td>' + item[8] + '</td>';
				  var rid = "'" + id + "'";
				  tbl += '<td align="center"><a href="#" onClick="RemoveItem('+rid+')"><img src="img/del_ico.png" width="20" border="0" /></td>';
				tbl += '</tr>';
				nItemArray += id + "||" + item[1] + "||" + item[2] + "||" + item[3] + "||" + item[4] + "||" + item[5] + "||" + item[6] + "||" + item[7] + "||" + item[8] + "::";
				cnt++;
			}
		}
		tbl += '</table>';
		$("#divDetails").html(tbl);

		nItemArray = nItemArray.slice(0, -2);
		$("#txtItemArr").val(nItemArray);
		$("#txtSizes").val("");
		$("#txtPieces").val("");
		$("#txtColor").val("");
		$("#txtMaterials").val("");
		$("#txtSpecification").val("");
	}
	function RemoveItem(val){
		nItemArr = $("#txtItemArr").val().split("::");

		var cnt = 1;
		var tbl = "";
		tbl += '<table class="table table-bordered table-condensed">';
			tbl += '<tr>';
			  tbl += '<th>#</th>';
			  tbl += '<th>SIZES</th>';
			  tbl += '<th>PCS</th>';
			  tbl += '<th>COLOR</th>';
			  tbl += '<th>UOM</th>';
			  tbl += '<th>MATERIALS</th>';
			  tbl += '<th>SPECIFICATION</th>';
			  tbl += '<th>REMOVE</th>';
			tbl += '</tr>';
		var nItemArray = "";
		// id | sizeid | size | qty | color | uomid | uom | material | specification
		for(var i=0;i<nItemArr.length;i++){
			var id = generateRandomString(6);
			if(nItemArr[i] != ""){
				var item = nItemArr[i].split("||");
				if(item[0] != val){
					tbl += '<tr>';
						tbl += '<td align="center">' + cnt + '</td>';
						tbl += '<td>' + item[2] + '</td>';
						tbl += '<td align="center">' + item[3] + '</td>';
						tbl += '<td>' + item[4] + '</td>';
						tbl += '<td>' + item[6] + '</td>';
						tbl += '<td>' + item[7] + '</td>';
						tbl += '<td>' + item[8] + '</td>';
						var rid = "'" + id + "'";
						tbl += '<td align="center"><a href="#" onClick="RemoveItem('+rid+')"><img src="img/del_ico.png" width="20" border="0" /></td>';
					tbl += '</tr>';
					nItemArray += id + "||" + item[1] + "||" + item[2] + "||" + item[3] + "||" + item[4] + "||" + item[5] + "||" + item[6] + "||" + item[7] + "||" + item[8] + "::";
					cnt++;
				}
			}
		}
		tbl += '</table>';
		$("#divDetails").html(tbl);

		nItemArray = nItemArray.slice(0, -2);
		$("#txtItemArr").val(nItemArray);
	}
	function ComputeTotal(){
		var amnt = 0;
		var discount = 0;
		var subtotal = 0;
		var vat = 0;
		var total = 0;
		
		if($("#txtAmount").val() > 0){
			amnt = $("#txtAmount").val();
		}
		if($("#txtDiscount").val() > 0){
			discount = $("#txtDiscount").val();
		}

		subtotal = (parseFloat(amnt) - parseFloat(discount));
		vat = parseFloat(subtotal) * parseFloat(0.12);
		total = (parseFloat(subtotal) + parseFloat(vat));

		$("#txtSubTotal").val(subtotal.toFixed(2));
		$("#txtVat").val(vat.toFixed(2));
		$("#txtTotalAmount").val(total.toFixed(2));
	}
	function RushEstimate(){
		if(document.estimateForm.chkIsRush.checked){
			document.estimateForm.txtLeadTime.readOnly = false;
			document.estimateForm.txtDueDate.disabled = false;
		}else{
			$("#txtLeadTime").val("");
			$("#txtDueDate").val("");
			$("#txtJobType").val("");
			document.estimateForm.txtLeadTime.readOnly = true;
			document.estimateForm.txtDueDate.disabled = true;
		}
	}
</script>
<body>
	<? require_once("inc-box/header.php"); ?>
	
		<div class="container-fluid-full">
			<div class="row-fluid">
					
				<? require_once("inc-box/leftnav-menu.php"); ?>
				
				<!-- start: Content -->
				<div id="content" class="span10">
					<? require_once("views/estimate_add.php");?>
				</div>
				<!-- end: Content -->

			</div><!--/fluid-row-->
		</div>
	
	<? require_once("inc-box/footer.php");?>
	<? require_once("inc-box/default-js.php");?>

	<script type="text/javascript">
		// function getCustomerInfo(){
		// 	var val = $("#txtCustomer").val();
		// 	var strURL = "inc-ajax/divAddEstimateCustInfo.php?id="+val;

		// 	$.ajax({	
		// 		url: strURL,
		// 		type: 'GET',
		// 		datatype: 'json',
		// 		contentType: 'application/json; charset=utf-8',
				
		// 		success: function (data) {
		// 			console.log(data);
		// 			// $("#divMLDates").replaceWith(data);
		// 			// $.unblockUI();
		// 		},	
						
		// 		error: function (request, status, err) {
		// 			alert(status);
		// 			alert(err);
		// 		}
		// 	});	
		// }
		jQuery(document).ready(function() {
			
		});
	</script>
</body>
</html>
