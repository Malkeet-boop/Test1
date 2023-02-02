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
$module_id=$_SESSION["agent_id"];
echo $id=$_REQUEST['id'];

$settingValuePrimaryContact = $commonfunction->getSettingValue("Primary Email");
$settingValuePrimaryEmail=$commonfunction->getSettingValue("Primary Email"); 
$settingValueAgentImagePathVerification  =$commonfunction->getSettingValue("AgentImagePathVerification  ");
$settingValueAdminImagePath=$commonfunction->getSettingValue("AdminImagePath");
$settingValueAgentImage  = $commonfunction->getSettingValue("Agent Image");
$settingValueAdminImage  = $commonfunction->getSettingValue("Admin Image");
$settingValueAdminImagePathOther = $commonfunction->getSettingValue("AdminImagePathOther");
$settingValueAgentImagePathOther = $commonfunction->getSettingValue("AgentImagePathOther");
$settingValueCompletedStatus = $commonfunction->getSettingValue("Completed Status");

$settingValueAdminImagePathSupport = $commonfunction->getSettingValue("AdminImagePathSupport");

$qry="select subject,description,ticket_date,ticket_attachment,sender_id,status from tw_ticket_system where id='".$id."' ";
$retVal=$sign->FunctionJson($qry);
$decodedJSON=json_decode($retVal);
$title=$decodedJSON->response[0]->subject;
$description=$decodedJSON->response[1]->description;
$ticket_date=$decodedJSON->response[2]->ticket_date;
$view_files=$decodedJSON->response[3]->ticket_attachment;
$sender_id=$decodedJSON->response[4]->sender_id;
$status=$decodedJSON->response[5]->status;
	
$qry1="select reply_date,reply_attachment,reply_by,replier_id, reply from tw_ticket_reply where ticket_id='".$id."' order by id desc";
$retVal1=$sign->FunctionJson($qry1);
$decodedJSON1=json_decode($retVal1);

$qryReplyCnt="select count(*) as cnt from tw_ticket_reply where ticket_id='".$id."'";
$retValCnt=$sign->Select($qryReplyCnt);
 
$qry2="select agent_name,agent_photo,mobilenumber from tw_agent_details where id='".$sender_id."' ";
$retVal2=$sign->FunctionJson($qry2);
$decodedJSON2=json_decode($retVal2);
$agent_name=$decodedJSON2->response[0]->agent_name;
$agent_photo=$decodedJSON2->response[1]->agent_photo;
$mobilenumber=$decodedJSON2->response[2]->mobilenumber;
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |AGENT Tickets</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- tw-css:start -->
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
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
							<h2><?php echo $title; ?></h2>
							<small><span class="ti-calendar"></span> <?php echo date("d-m-Y H:i:s",strtotime($ticket_date));?> | <span class="ti-user"></span> <?php echo $agent_name; ?></small>
							<hr>
							<p><?php echo $description; ?> </p>
							<?php if($view_files!=""){ ?>
								<hr>
								<a href="<?php echo $settingValueAgentImagePathVerification.$mobilenumber."/".$view_files ?>" target="__blank"><i class="icon-paper menu-icon"></i> View File</a>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
			<!-- ########################################## -->
			<?php if($status!=$settingValueCompletedStatus){?>
			<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label for="txtReply">Reply <code></code></label>
								<textarea class="form-control" id="txtReply" maxlength="1000" rows="4" placeholder="Text"></textarea>			
							</div>
							<div class="form-group">
								<label for="Document_Proof">Reply attachment</label><br>
								<input type="file" name="Document_Proof" accept=".png, .jpg, .jpeg, .pdf" id="Document_Proof" placeholder="reply attachment" onchange="showname();" />				
							</div>
							<button type="button" id="btnReply" class="btn btn-success" onclick="addrecord();">Post Reply</button>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
							<!-- ########################################## -->
							<?php
							$count=0;
							$i=1;
								while($retValCnt>=$i) {
									$reply_date=$decodedJSON1->response[$count]->reply_date;
									$count=$count+1;
									$reply_attachment=$decodedJSON1->response[$count]->reply_attachment;
									$count=$count+1;
									$reply_by=$decodedJSON1->response[$count]->reply_by;
									$count=$count+1;
									$replier_id=$decodedJSON1->response[$count]->replier_id;
									$count=$count+1;
									$reply=$decodedJSON1->response[$count]->reply;
									$count=$count+1;
									if($reply_by=="ADMIN")
									{
										$qrySubAdminName="select name from tw_sub_admin where id='".$replier_id."'";
										$sub_admin_name=$sign->SelectF($qrySubAdminName,"name");
									}
							?>
							
							<div class="row">
								<div class="col-md-12">
										<?php if ($reply_by=="AGENT") { ?>
										<div class="row">
											<div class="col-lg-8 offset-lg-3 col-md-8 offset-md-3 col-sm-10 col-xs-10 col-10 chat-right">
												<small><span class="ti-calendar"></span><?php echo date("d-m-Y H:i:s",strtotime($reply_date));  ?> | <span class="ti-user"></span> <?php echo $agent_name; ?></small>
												<hr>
												<p><?php echo $reply; ?></p>
												
												<?php if (!empty($reply_attachment)) { ?>
												<a href="<?php echo $settingValueAgentImagePathVerification.$mobilenumber."/".$reply_attachment; ?>" target="__blank"><i class="ti-download"> Attachment</i></a>
												
												<?php } ?>
											</div>
											<div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 col-2">
											<?php if (!empty($agent_photo)) { ?>
												<img src="<?php echo $settingValueAgentImagePathVerification.$mobilenumber."/".$agent_photo; ?>" class="img-sm rounded-circle mb-3" />
											<?php }else{?>
												<img src="<?php echo $settingValueAgentImagePathOther.$settingValueAgentImage; ?>" class="img-sm rounded-circle mb-3" />

											<?php }?>
											</div>
										</div>
										<?php } else { ?>
										<div class="row">
											<div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 col-2">
												<img src="<?php echo $settingValueAdminImagePathOther."admin_right_side_logo.png"; ?>" class="img-sm rounded-circle mb-3" />
											</div>
											<div class="col-lg-8 col-md-8 col-sm-10 col-xs-10 col-10 chat-left">
												<small><span class="ti-calendar"></span> <?php echo date("d-m-Y H:i:s",strtotime($reply_date));  ?> | <span class="ti-user"></span> <?php echo $reply_by; ?></small>
												<hr>
												<p><?php echo $reply; ?></p>
												
												<?php if (!empty($reply_attachment)) { ?>
												<a href="<?php echo $settingValueAdminImagePathSupport.'Agent'."/".$reply_attachment ?>" target="__blank"><i class="ti-download"> Attachment</i></a>
												<?php } ?>
											</div>
										</div>
										<?php } ?>
								</div>
							</div>
							<br>
							<?php
								$i=$i+1;
								}
							?>
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
<!-- container-scroller -->
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
var hdnIDimg="";
var mobilenumber="<?php echo $mobilenumber; ?>";
var id="<?php echo $id; ?>";
$('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$(this).parent().addClass('has-danger');
			$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
});
function setErrorOnBlur(inputComponent)
{
	var valplaceholder = $("#" +inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
			$("#" +inputComponent).focus();
		}

	}
	else
	{
		$("#" +inputComponent).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
}
function setError(inputComponent)
{
	
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Please enter valid " + valplaceholder;
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
	
}

function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}
function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}
function showname() {
var name = document.getElementById('Document_Proof'); 
hdnIDimg = name.files.item(0).name;
var name = document.getElementById("Document_Proof").files[0].name;
var form_data2 = new FormData();
var ext = name.split('.').pop().toLowerCase();
	if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
	{
		$('#Document_Proof').val("");
	}
var oFReader = new FileReader();
oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
var f = document.getElementById("Document_Proof").files[0];
var fsize = f.size||f.fileSize;
var path = "<?php echo $settingValueAgentImagePathVerification; ?>"+mobilenumber+"/"+name;
var result = checkFileExist(path);
	if(fsize > 5000000)
	{
	   alert("Image File Size is very big");
	}
	else if (result == true) {
			showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
				if(confirmed==true){
						form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

					   $.ajax({
						url:"upload.php",
						method:"POST",
						data: form_data2,
						contentType: false,
						cache: false,
						processData: false,
						beforeSend:function(){
						},   
						success:function(data)
						
						{
							console.log(data);
							hdnIDimg=data;
							adddata();
						}
					   });
				}
				
			});
	} 
	else
	{
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

	   $.ajax({
		url:"upload.php",
		method:"POST",
		data: form_data2,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		},   
		success:function(data)
		
		{
			console.log(data);
			hdnIDimg=data;
			adddata();
		}
	   });
	}	 
};
function setError(inputComponent)
{
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Please enter valid " + valplaceholder;
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
}

function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}

function addrecord(){
		var buttonHtml = $('#btnAddrecord').html();
		if(!validateBlank($("#txtReply").val())){
			setErrorOnBlur("txtReply");
		}
		else{
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		
			$.ajax({
				type:"POST",
				url:"apiAddAgentReplyTicketMaster.php",
				data:{reply:$("#txtReply").val(),reply_attachment:hdnIDimg, ticket_id: new URLSearchParams(window.location.search).get('id')},
				
				success:function(response){
					console.log(response);
					enableButton('#btnAddrecord','Update Record');
					if($.trim(response)=="Success"){
						showAlertRedirect("Success","Reply Posted Successfully","success","pgAgentViewTicketEdit.php?type=view&id="+id);
						
						$("#collection_point_name").val("");
						$("#date").val("");
						$("#Description").val("");
						$("#txtreply_attachment").val("");
											
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Ticket already exist","warning");
						
					}
					else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						
					}
					$('#btnAddrecord').html(buttonHtml);
				}
			});
		}	 
}	
</script>	
</body>
</html>