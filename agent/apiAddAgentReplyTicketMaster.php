<?php
session_start();
	// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$reply=$sign->escapeString($_POST["reply"]);
$ticket_id= $_POST['ticket_id'];
$reply_attachment=$_POST["reply_attachment"];
	
date_default_timezone_set("Asia/Kolkata");
$date=date("d-m-Y h:i:sa");
$user=$_SESSION["agent_id"];
$reply_by="AGENT";
	
$retVal=$sign->FunctionJson("select id from tw_agent_details where mobilenumber	 = '".$user."'");
$decodedJSON=json_decode($retVal);
$replier_id=$decodedJSON->response[0]->id;
	
	$qry1="insert into tw_ticket_reply (ticket_id,reply,reply_attachment,reply_by,replier_id,created_by,created_on,created_ip,modified_by,modified_on,modified_ip) values('".$ticket_id."','".$reply."','".$reply_attachment."','".$reply_by."','".$replier_id."','".$user."','".$date."','".$ip_address."','".$user."','".$date."','".$ip_address."')";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		
		echo "error";
	}

?>
