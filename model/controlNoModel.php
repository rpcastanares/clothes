<?
	// OPEN DB
	$csdb = new DBConfig();
	$csdb->setClothesDB();

	// SET CONTROL NO
	$ctrlno = new Table();
	$ctrlno->setSQLType($csdb->getSQLType());
	$ctrlno->setInstance($csdb->getInstance());
	$ctrlno->setView("controlno_v");
	$ctrlno->setParam("ORDER BY description");
	$ctrlno->doQuery("query");
	$row_ctrlno = $ctrlno->getLists();

	// CLOSE DB
	$csdb->DBClose();
?>