<?php 
session_start();
if(!isset($_SESSION["agent_id"])){
	header("Location:pgAgentLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$agent_id = $_SESSION["agent_id"];

$settingValuePending=$commonfunction->getSettingValue("Pending Status"); 
$settingValueOngoingStatus=$commonfunction->getSettingValue("Ongoing Status"); 
$settingValueCompletedStatus=$commonfunction->getSettingValue("Completed Status"); 

$allCount = $sign->Select("select count(*) as cnt from tw_ticket_system where sender_id='". $agent_id."'");
$completedCount = $sign->Select("select count(*) as cnt from tw_ticket_system where sender_id='" .$agent_id."' and status = '".$settingValueCompletedStatus."'");
$pendingCount = $sign->Select("select count(*) as cnt from tw_ticket_system where sender_id='".$agent_id."' and status='".$settingValuePending."'");

$ongoingCount = $sign->Select("select count(*) as cnt from tw_ticket_system where sender_id='".$agent_id."' and status='".$settingValueOngoingStatus."'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Agent Tickets</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>
 <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
		<?php
			include_once("navTopHeader.php");
		?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
		<?php
			include_once("navRightSideSetting.php");
		?>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
        <?php
			include_once("navSideBar.php");
		?>
      <!-- partial -->
	<div class="main-panel">        
        <div class="content-wrapper">
			<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
					<div class="card">
					<div class="card-body">
					<div class="row">
						
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 grid-margin" id="Pending">
							<div class="card bg-linkedin d-flex align-items-center bg-danger text-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<div class="ms-3 text-white">
											<h6 onclick="showData();">Pending</h6>
											<h6><?php echo $pendingCount ?></h6>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 grid-margin" id="Ongoing">
							<div class="card bg-linkedin d-flex align-items-center bg-warning text-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<div class="ms-3 text-white">
											<h6 onclick="showData();">Ongoing</h6>
											<h6><?php echo $ongoingCount ?></h6>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 grid-margin" id="Completed">
							<div class="card bg-linkedin d-flex align-items-center bg-success text-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center ">
									<div class="ms-3 text-white">
											<h6 onclick="showData();">Completed</h6>
											<h6><?php echo $completedCount ?></h6>
									</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 grid-margin" id="ALL" >
							<div class="card bg-facebook d-flex align-items-center bg-info text-center">
								<div class="card-body">
									<div class="d-flex flex-row align-items-center">
										<div class="ms-3 text-white">
											<h6 onclick="showData();">All</h6>
											<h6><?php echo $allCount ?></h6>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					</div>
				</div>	
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
					<div class="card">		
						<div class="card-body">
							<h4 class="card-title">Collection Point Tickets
							<a href="javascript:void(0)" class="float-right" onclick="ViewHistory();"><i class="ti-bookmark-alt pointer-cursor"> View History</i></a>
							<p><a href="javascript:void(0)"  onclick="showData();" title="Return to previous page">« Go Back</a></p>
							</h4>
							<div class="table-responsive">
								<table id="tableData" class="table">

								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php
			include_once("footer.php");
		?>
        <!-- partial -->
	</div>
      <!-- main-panel ends -->
</div>
    <!-- page-body-wrapper ends -->
</div>
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	showData("");
	document.getElementById("ALL").style.cursor="pointer";
	document.getElementById("Completed").style.cursor="pointer";
	document.getElementById("Ongoing").style.cursor="pointer";
	document.getElementById("Pending").style.cursor="pointer";
});

function showData(){
		$.ajax({
			type:"POST",
			url:"apiGetViewAgentTicketMaster.php",
			data:{},
			success:function(response){
				console.log(response);
				$("#tableData").html(response);
	
				$('#tableData').DataTable({
					"responsive":true,
					"destroy":true,
					"bPaginate":true,
					"bLengthChange":true,
					"bFilter":true,
					"bSort":true,
					"bInfo":true,
					"retrieve": true,
					"bAutoWidth":false,
	                "scrollXInner":true
				});
			}
	});
}
function ViewRecord(id){
	window.location.href = "pgAgentViewTicketEdit.php?type=view&id="+id;
}
function ViewHistory(){

	$.ajax({
			type:"POST",
			url:"apiGetViewTicketHistory.php",
			data:{},
			success:function(response){
				console.log(response);
				$("#tableData").html(response);
	
				$('#tableData').DataTable({
					"responsive":true,
					"destroy":true,
					"bPaginate":true,
					"bLengthChange":true,
					"bFilter":true,
					"bSort":true,
					"bInfo":true,
					"retrieve": true,
					"bAutoWidth":false,
	                "scrollXInner":true
	            });
			}
		});
}
</script>
</body>
</html>