<?
	session_start();
	define(BASE_URL, "http://localhost:81/clothes/");
	
	// define(INCLUDE_PATH, "include/");
	define(INC_PATH, "inc/");
	define(MODEL_PATH, "model/");
	define(VIEW_PATH, "views/");
	define(CONTROLLER_PATH, "controller/");
	define(FILES_PATH, "files/");
	define(ESTIMATEATTACHMENTS, FILES_PATH . "estimates/");
	// define(COMPANYPICS, FILES_PATH . "companypics/");
	// define(EQUIPMENTPICS, FILES_PATH . "equipmentpics/");
	// define(COMPANYLOGOS, COMPANYPICS . "logo/");
	// define(COMPANYSIGNATURES, COMPANYPICS . "signature/");
	// define(ASSIGNEEATTACHMENTS, FILES_PATH . "assigneeattachment/");
	// define(POATTACHMENTS, FILES_PATH . "poattachments/");
	define(MODELS, "_models.php");
	define(VIEWS, "_views.php");
	define(CONTROLLERS, "_controllers.php");
	define(DATABASE, "Database.php");
	define(DBCONFIG, "DBConfig.php");
	define(TABLE, "Table.php");
	define(BROWSER, "Browser.php");
	define(MESSAGEALERT, "MessageAlert.php");
	define(FUNCTIONS, "functions.php");
	define(PHPMAILER, "class.phpmailer.php");
	define(SMTP, "class.smtp.php");
	define(FPDF, "fpdf.php");

	$title = "CLOTHES DESIGN INC.";
	$today = date("Y-m-d h:i:s");
	$data_animate_time = 100;
	$data_animate_type = 'fadeIn';
	$userid = $_SESSION['userid'];
	// $userid = "alladinx";
?>