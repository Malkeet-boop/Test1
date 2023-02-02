<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValuePendingStatus=$commonfunction->getSettingValue("Pending Status"); 
$settingValueOngoingStatus=$commonfunction->getSettingValue("Ongoing Status"); 


$agent_id=$_SESSION["agent_id"];
$module_type="AGENT";

	$qry="Select ts.id,ts.ticket_date,ts.subject,vsm.verification_status from tw_ticket_system ts INNER JOIN tw_verification_status_master vsm ON ts.status=vsm.id where (ts.status='".$settingValueOngoingStatus."' or ts.status='".$settingValuePendingStatus."') and ts.sender_id='".$agent_id."' and ts.panel='Agent'";
	
	$qry1="select count(*) as cnt from tw_ticket_system where (status='".$settingValueOngoingStatus."' or status='".$settingValuePendingStatus."') and sender_id='".$agent_id."' and panel='Agent'";

$retVal = $sign->FunctionJSON($qry);
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Subject</th><th>Ticket date</th><th>Status</th><th>View</th></tr></thead><tbody>";

while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$ticket_date = $decodedJSON2->response[$count]->ticket_date;
	$count=$count+1;
	$subject = $decodedJSON2->response[$count]->subject;
	$count=$count+1;
	$status = $decodedJSON2->response[$count]->verification_status;
	$count=$count+1;
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$subject."</td>";
		$table.="<td>".date("d-m-Y H:i:s",strtotime($ticket_date))."</td>";
		$table.="<td>".$status."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='ViewRecord(".$id.")'><i class='ti-eye'></a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	