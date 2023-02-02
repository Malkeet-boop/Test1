<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$date=date("d-m-Y h:i:sa");

$subject=$sign->escapeString($_POST["subject"]);
$description=$sign->escapeString($_POST["description"]);
$ticket_attachment=$_POST["ticket_attachment"];
$agent_id=$_SESSION["agent_id"];
$module_type="AGENT";
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$qry1="insert into tw_ticket_system (sender_id,panel,subject,description,ticket_attachment,status,created_by,created_on,created_ip,modified_by,modified_on,modified_ip) values('".$agent_id."','Agent','".$subject."','".$description."','".$ticket_attachment."','".$settingValuePendingStatus."','".$agent_id."','".$date."','".$ip_address."','".$agent_id."','".$date."','".$ip_address."')";
$retVal1 = $sign->FunctionQuery($qry1);

if($retVal1=="Success"){
	echo "Success";
}
else{
	echo "error";
}

?>
