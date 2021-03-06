<?
	// SAVE ESTIMATE
	if(isset($_POST['estimateAdd']) && !empty($_POST['estimateAdd']) && $_POST['estimateAdd'] == 1){
		// GET CONTROL NO
		$newNum = getNewCtrlNo("ESTIMATE");

		$balance = null;
		$tDate = Date("Y-m-d h:i:s");
		$custCode = $_POST['txtCustomer'];
		$jobtype = $_POST['txtJobType'];
		$specs = $_POST['txtSpecification'];
		
		// ATTACHMENT
		$nFile = null;
		if(!empty($_FILES['txtAttachment']['name'])){
			$file = $_FILES['txtAttachment']['name'];
			$nFile = date("Ymdhis") . $file;
		}
		
		$downpayment = str_replace(",","",$_POST['txtDownPayment']);
		$amount = str_replace(",","",$_POST['txtAmount']);
		$discount = str_replace(",","",$_POST['txtDiscount']);
		$subtotal = str_replace(",","",$_POST['txtSubTotal']);
		$vat = str_replace(",","",$_POST['txtVat']);
		$totalamount = str_replace(",","",$_POST['txtTotalAmount']);
		if($downpayment > 0){
			$balance = str_replace(",","",$_POST['txtBalance']);
		}
		$items = explode("::",$_POST['txtItemArray']);
		
		$isRush = 0;
		$duedate = dateFormat($_POST['txtDueDate'],"Y-m-d");
		if(!empty($_POST['chkIsRush'])){
			$isRush = 1;
			$duedate = dateFormat($_POST['txtDueDateDesc'],"Y-m-d");
		}
		$leadtime = $_POST['txtLeadTime'];
		
		// UPDATE CONTROL NO
		UpdateCtrlNo("ESTIMATE");
		
		$dir = ESTIMATEATTACHMENTS . Date("Ym") . "/" . $newNum;

		// OPEN DB
		$csdb = new DBConfig();
		$csdb->setClothesDB();

		// INSERT ESTIMATE MASTER
		$estmst = new Table();
		$estmst->setSQLType($csdb->getSQLType());
		$estmst->setInstance($csdb->getInstance());
		$estmst->setTable("estimatemaster");
		$estmst->setField("quoteReferenceNo,transactionDate,customerCode,isRush,jobType,leadTime,dueDate,attachment,downpayment,amount,discount,subTotal,vat,totalAmount,balance,createdBy");
		$estmst->setValues("'$newNum','$tDate','$custCode','$isRush','$jobtype','$leadtime','$duedate','$nFile','$downpayment','$amount','$discount','$subtotal','$vat','$totalamount','$balance','$userid'");
		$estmst->doQuery("save");
		$newid = $estmst->getNewID();
		
		// id | sizeid | size | qty | color | uomid | uom | material | specification
		for($i=0;$i<count($items);$i++){
			$item = explode("||",$items[$i]);
			$size = $item[1];
			$qty = $item[3];
			$color = $item[4];
			$uom = $item[5];
			$mat = $item[7];
			$spec = $item[8];

			// INSERT ESTIMATE DETAIL
			$estdtl = new Table();
			$estdtl->setSQLType($csdb->getSQLType());
			$estdtl->setInstance($csdb->getInstance());
			$estdtl->setTable("estimatedetail");
			$estdtl->setField("estimateMasterId,quoteReferenceNo,specification,size,quantity,color,uom,material");
			$estdtl->setValues("'$newid','$newNum','$spec','$size','$qty','$color','$uom','$mat'");
			$estdtl->doQuery("save");
		}

		if($_FILES['txtAttachment']['size'] > 0){
			if (!file_exists($dir . "/" . $nFile)) {
				mkdir($dir, 0777, true);
			}
			move_uploaded_file($_FILES['txtAttachment']['tmp_name'], $dir . '/' . $nFile);
		}

		// CLOSE DB
		$csdb->DBClose();

		$alert = new MessageAlert();
		$alert->setMessage("New estimate successfully saved.");
		$alert->setURL(BASE_URL . "estimate_edit.php?edit=1&id=".$newNum);
		$alert->Alert();
	}
	// END SAVE ESTIMATE

	// EDIT ESTIMATE
	if(isset($_GET['edit']) && !empty($_GET['edit']) && $_GET['edit'] == 1){
		$id = $_GET['id'];

		// OPEN DB
		$csdb = new DBConfig();
		$csdb->setClothesDB();

		// SET ESTIMATES MASTER
		$estmst = new Table();
		$estmst->setSQLType($csdb->getSQLType());
		$estmst->setInstance($csdb->getInstance());
		$estmst->setView("estimatemaster_v");
		$estmst->setParam("WHERE quoteReferenceNo = '$id'");
		$estmst->doQuery("query");
		$row_estmst = $estmst->getLists();
		$estMstId = $row_estmst[0]['estimateMasterId'];

		if(count($row_estmst) == 0){
			$alert = new MessageAlert();
			$alert->setMessage("Invalid URL!");
			$alert->setURL(BASE_URL . "estimates.php");
			$alert->Alert();
		}

		// SET ESTIMATES DETAIL
		$estdtl = new Table();
		$estdtl->setSQLType($csdb->getSQLType());
		$estdtl->setInstance($csdb->getInstance());
		$estdtl->setView("estimatedetail_v");
		$estdtl->setParam("WHERE quoteReferenceNo = '$id'");
		$estdtl->doQuery("query");
		$row_estdtl = $estdtl->getLists();

		// id | sizeid | size | qty | color | uomid | uom | material | specification
		for($i=0;$i<count($row_estdtl);$i++){
			$rand = generateRandomString(6);
			$itemArray .= $row_estdtl[$i]['id']
						 . "||" . $row_estdtl[$i]['size']
						 . "||" . $row_estdtl[$i]['sizeDesc']
						 . "||" . $row_estdtl[$i]['quantity']
						 . "||" . $row_estdtl[$i]['color']
						 . "||" . $row_estdtl[$i]['uom']
						 . "||" . $row_estdtl[$i]['uomDesc']
						 . "||" . $row_estdtl[$i]['material']
						 . "||" . $row_estdtl[$i]['specification'] . "::";
		}
		$itemArray = rtrim($itemArray,"::");

		// CLOSE DB
		$csdb->DBClose();
	}
	// END EDIT ESTIMATE

	// UPDATE ESTIMATE
	if(isset($_POST['estimateUpdate']) && !empty($_POST['estimateUpdate']) && $_POST['estimateUpdate'] == 1){
		$id = $_GET['id'];
		$curFile = $_POST['txtCurrentAttachment'];
		$estMstId = $_POST['estMstId'];
		$status = $_POST['txtStatus'];
		$remarks = $_POST['txtRemarks'];

		// ATTACHMENT
		$nFile = $_POST['txtCurrentAttachment'];
		if(!empty($_FILES['txtAttachment']['name'])){
			$file = $_FILES['txtAttachment']['name'];
			$nFile = date("Ymdhis") . $file;
		}

		$downpayment = str_replace(",","",$_POST['txtDownPayment']);
		$amount = str_replace(",","",$_POST['txtAmount']);
		$discount = str_replace(",","",$_POST['txtDiscount']);
		$subtotal = str_replace(",","",$_POST['txtSubTotal']);
		$vat = str_replace(",","",$_POST['txtVat']);
		$totalamount = str_replace(",","",$_POST['txtTotalAmount']);
		$balance = str_replace(",","",$_POST['txtBalance']);
		
		$items = explode("::",$_POST['txtItemArray']);

		$dir = ESTIMATEATTACHMENTS . Date("Ym") . "/" . $id;

		// OPEN DB
		$csdb = new DBConfig();
		$csdb->setClothesDB();

		// DELETING ITEM
		for($a=0;$a<count($row_estdtl);$a++){
			$exist = 0;
			$itemid = $row_estdtl[$a]['id'];

			// CHECK IF ITEM IN TABLE EXIST IN CURRENT ITEMS
			for($b=0;$b<count($items);$b++){
				$item = explode("||",$items[$b]);
				if($item[0] == $itemid){
					$exist++;
				}
			}
			
			// DELETE ITEM
			if($exist == 0){
				// DELETE ESTIMATE DETAILS
				$delestmst = new Table();
				$delestmst->setSQLType($csdb->getSQLType());
				$delestmst->setInstance($csdb->getInstance());
				$delestmst->setTable("estimatedetail");
				$delestmst->setParam("WHERE quoteReferenceNo = '$id' AND id = '$itemid'");
				$delestmst->doQuery("delete");
			}
		}

		// ADDING ITEM
		// id | sizeid | size | qty | color | uomid | uom | material | specification
		for($i=0;$i<count($items);$i++){
			$item = explode("||",$items[$i]);
			$size = $item[1];
			$qty = $item[3];
			$color = $item[4];
			$uom = $item[5];
			$mat = $item[7];
			$spec = $item[8];
			$exist = 0;

			// CHECK IF CURRENT ITEMS EXIST IN TABLE
			for($a=0;$a<count($row_estdtl);$a++){
				$itemid = $row_estdtl[$a]['id'];
				if($item[0] == $itemid){
					$exist++;
				}
			}

			// ADD ITEM
			if($exist == 0){
				// INSERT ESTIMATE DETAIL
				$estdtl = new Table();
				$estdtl->setSQLType($csdb->getSQLType());
				$estdtl->setInstance($csdb->getInstance());
				$estdtl->setTable("estimatedetail");
				$estdtl->setField("estimateMasterId,quoteReferenceNo,specification,size,quantity,color,uom,material");
				$estdtl->setValues("'$estMstId','$id','$spec','$size','$qty','$color','$uom','$mat'");
				$estdtl->doQuery("save");
			}
		}

		// UPDATE ESTIMATES MASTER
		$estmst = new Table();
		$estmst->setSQLType($csdb->getSQLType());
		$estmst->setInstance($csdb->getInstance());
		$estmst->setTable("estimatemaster");
		$estmst->setValues("attachment = '$nFile', downpayment = '$downpayment', amount = '$amount', discount = '$discount', subTotal = '$subtotal', vat = '$vat', totalAmount = '$totalamount', balance = '$balance', status = '$status', modifiedDate = '$today', modifiedBy = '$userid', remarks = '$remarks'");
		$estmst->setParam("WHERE quoteReferenceNo = '$id'");
		$estmst->doQuery("update");

		switch($status){
			case 1:
					// GET CONTROL NO
					$joNo = getNewCtrlNo("JOBORDER");

					// SAVE JOB ORDER MASTER
					$jomst = new Table();
					$jomst->setSQLType($csdb->getSQLType());
					$jomst->setInstance($csdb->getInstance());
					$jomst->setTable("jobordermaster");
					$jomst->setField("jobOrderReferenceNo,quoteReferenceNo,createdDate,createdBy");
					$jomst->setValues("'$joNo','$id','$today','$userid'");
					$jomst->doQuery("save");
					
					// UPDATE CONTROL NO
					UpdateCtrlNo("JOBORDER");

					for($i=0;$i<count($items);$i++){
						$item = explode("||",$items[$i]);
						$size = $item[1];
						$qty = $item[3];
						$color = $item[4];
						$uom = $item[5];
						$mat = $item[7];
						$spec = $item[8];

						// INSERT JOB ORDER DETAIL
						$jodtl = new Table();
						$jodtl->setSQLType($csdb->getSQLType());
						$jodtl->setInstance($csdb->getInstance());
						$jodtl->setTable("joborderdetail");
						$jodtl->setField("jobOrderReferenceNo,specification,size,quantity,color,uom,material");
						$jodtl->setValues("'$joNo','$spec','$size','$qty','$color','$uom','$mat'");
						$jodtl->doQuery("save");
					}

					// SET JOB ORDER MASTER
					$getjomst = new Table();
					$getjomst->setSQLType($csdb->getSQLType());
					$getjomst->setInstance($csdb->getInstance());
					$getjomst->setView("jobordermaster_v");
					$getjomst->setCol("id");
					$getjomst->setParam("WHERE jobOrderReferenceNo = '$joNo'");
					$getjomst->doQuery("query");
					$row_getjomst = $getjomst->getLists();
					$joid = $row_getjomst[0]['id'];

					// UPDATE JOB ORDER DETAIL
					$updjodtl = new Table();
					$updjodtl->setSQLType($csdb->getSQLType());
					$updjodtl->setInstance($csdb->getInstance());
					$updjodtl->setTable("joborderdetail");
					$updjodtl->setValues("jobOrderMasterId = '$joid'");
					$updjodtl->setParam("WHERE jobOrderReferenceNo = '$joNo'");
					$updjodtl->doQuery("update");
					
					$msg = $id . " successfully acknowledged.";
				break;
			case 3:
					$msg = $id . " successfully canceled.";
				break;
			default: $msg = $id . " successfully updated."; break;
		}
		
		// CLOSE DB
		$csdb->DBClose();

		if(isset($_FILES['txtAttachment']['name']) && !empty($_FILES['txtAttachment']['name'])){
			if($_FILES['txtAttachment']['size'] > 0){
				if (!file_exists($dir . "/" . $nFile)) {
					mkdir($dir, 0777, true);
				}
				move_uploaded_file($_FILES['txtAttachment']['tmp_name'], $dir . '/' . $nFile);
			}
		}

		// CLOSE DB
		$csdb->DBClose();

		$alert = new MessageAlert();
		$alert->setMessage($msg);
		$alert->setURL(BASE_URL . "estimate_edit.php?edit=1&id=".$id);
		$alert->Alert();
	}
	// END UPDATE ESTIMATE

	// SEARCH ESTIMATE
	if(isset($_POST['estimateSearch']) && !empty($_POST['estimateSearch']) && $_POST['estimateSearch'] == 1){
		$xDate = "";
		$estNo = "";
		$jtNo = "";
		$stat = "";
		// TRANSACTION DATE
		if(!empty($_POST['txtFrom']) && !empty($_POST['txtTo'])){
			$dtfrom = dateFormat($_POST['txtFrom'],"Y-m-d");
			$dtto = dateFormat($_POST['txtTo'],"Y-m-d");
			$xDate = " AND transactionDate between '$dtfrom 00:00:00' AND '$dtto 23:59:00'";
		}else if(!empty($_POST['txtFrom']) && empty($_POST['txtTo'])){
			$dtfrom = dateFormat($_POST['txtFrom'],"Y-m-d");
			$dtto = dateFormat($_POST['txtFrom'],"Y-m-d");
			$xDate = " AND transactionDate between '$dtfrom 00:00:00' AND '$dtto 23:59:00'";
		}else if(empty($_POST['txtFrom']) && !empty($_POST['txtTo'])){
			$dtfrom = dateFormat($_POST['txtTo'],"Y-m-d");
			$dtto = dateFormat($_POST['txtTo'],"Y-m-d");
			$xDate = " AND transactionDate between '$dtfrom 00:00:00' AND '$dtto 23:59:00'";
		}else{ }

		// ESTIMATE NO
		if(isset($_POST['txtEstimateNo']) && !empty($_POST['txtEstimateNo'])){
			$estimateNo = $_POST['txtEstimateNo'];
			$estNo = " AND quoteReferenceNo = '$estimateNo'";
		}

		// CUSTOMER CODE
		if(isset($_POST['txtCustomerCode']) && !empty($_POST['txtCustomerCode'])){
			$customerCode = $_POST['txtCustomerCode'];
			$cCode = " AND customerCode = '$customerCode'";
		}

		// JOB TYPE
		if(isset($_POST['txtJobTypeCode']) && !empty($_POST['txtJobTypeCode'])){
			$jobTypeNo = $_POST['txtJobTypeCode'];
			$jtNo = " AND jobType = '$jobTypeNo'";
		}

		// STATUS
		if(isset($_POST['txtStatus']) && !empty($_POST['txtStatus']) || $_POST['txtStatus'] != ""){
			$status = $_POST['txtStatus'];
			$stat = " AND status = '$status'";
		}

		// OPEN DB
		$csdb = new DBConfig();
		$csdb->setClothesDB();

		// SET ESTIMATES MASTER
		$estimates = new Table();
		$estimates->setSQLType($csdb->getSQLType());
		$estimates->setInstance($csdb->getInstance());
		$estimates->setView("estimatemaster_v");
		$estimates->setParam("WHERE 1 $xDate $estNo $cCode $jtNo $stat ORDER BY transactionDate DESC");
		$estimates->doQuery("query");
		$row_estimates = $estimates->getLists();

		// CLOSE DB
		$csdb->DBClose();
	}
	// END SEARCH ESTIMATE
?>