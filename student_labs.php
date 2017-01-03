<?php
	session_start();
	include('db.php');
	if(isset($_POST['logout']))
	{	unset($_SESSION['username']);
		session_destroy(); }
	if(isset($_SESSION['username'])){	$name = $_SESSION['username'];} else header("Location: index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="js/basiccalendar.js"></script> 
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo"Welcome :".$_SESSION['username']; ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="content">
<!-- header begins -->
<div id="header">
<div id="menu">
	<ul>
		<li><a href="studentpage.php"title="">POST</a></li>
		<li><a href="studentinfo.php" title="">Info</a></li>
		<li><a href="studentposts.php" title="">POSTED</a></li>
		<li><a href='st_files.php' title=''>Files</a></li>
		<li><a href='studentfileupload.php' title=''>File Upload</a></li>
		<li><a href='student_labs.php' title=''>Labs</a></li>
	</ul>
	</div>	
	<div id="logo"></div>
</div>
<!-- header ends -->
<!-- content begins -->
 <div id="main">
	<div id="right">
		<div class="box">
		<br/>
<div id="lab_content">
	<style>
	.exercise1{width:250px; }
	.exercise2{width:250px; margin-left:280px; margin-top:-23px;}
	#get_doc_key_div:hover{background:url(images/black.jpg); color:white; cursor:pointer;}
	</style>
	
	<div id="lab_wrapper">
	<?php
	
		
		
		$sql = mysql_query("SELECT labs FROM stud_details WHERE id='{$name}'");
		while($disp = mysql_fetch_array($sql)){$r_ret=$disp['labs'];}
		if($r_ret=="*"){
			echo "<span style='color:red'>You have no access to currently available laboratories</span>";
		}
		else{
			$exe = "exercise1";
			$lb_arr = explode('*',$r_ret);
			foreach($lb_arr as $lab_batch){
				if($lab_batch!=''){
					$l_arr = explode('-',$lab_batch);
					$lab = $l_arr[0];
					$batch = $l_arr[1];
					$q = mysql_query("SELECT lab_name FROM lab_table WHERE lab_id='{$lab}'");
					while($row=mysql_fetch_array($q)){
						$lab_name = $row['lab_name'];
						$p = $lab_batch."**".$lab_name;
						echo "<div id='but' class='{$exe}'><button type='submit' value='{$p}' onclick='show_lab_files(this.value)'>{$lab_name}</button></div>";
						if($exe=="exercise1"){$exe="exercise2";}else{$exe="exercise1";}
					}
				}
			}
		}
	?>
	</div>
	<div id="lab_content_wrapper" class="box" style="background-color:white" >
	<h4 style="color:black">Click a lab to view the exercises under it.</h4>
	</div>
</div>
	</div>
	</div>
	<div id="left">

	<h3>Actions</h3>
				<table>
					<tr></tr><tr><td>
						<form name="logout" method="post">
						<div id='but'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button style='width:150px;' type='submit' value='logout' name='logout'>Logout</button></div>
						</form>
					</td></tr>
				</table>
					<br>
		<h3>Profile Image</h3>
			<div class="title_back">
			<?php
			$res=mysql_query("select profileimage from logindetails where rollno='{$_SESSION['username']}'");
			$im=mysql_fetch_array($res);
			if($im['profileimage'] != '') $profileimage="prof_image/".$im['profileimage'];
			else $profileimage="prof_image/imagedefault.jpg";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='".$profileimage."' style='width:110px ;height:150px'></image>";
			?>		
								
			</div>
			<br />
		</div>

<!--content ends -->
<!--footer begins -->
<div style="clear: both"></div>
	</div>

<div id="footer">
<p>Copyright &copy; 2012. 
Designed by Batch 11</p>
	</div>
</div>
<!-- footer ends-->
<script>
function show_lab_files(lab_batch){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{document.getElementById("lab_content_wrapper").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=6&lab_batch="+lab_batch,true);
	xmlhttp.send();
}

function get_key(array){
	var arr=array.split("-");
	var key = arr[0];
	var start_time = arr[1];
	var end_time = arr[2];
	var current_time = arr[3];
	var lab = arr[4];
	var batch = arr[5];
	var ex_no = arr[6];
	var ex_name = arr[7];
	if((current_time >= start_time)&&(current_time <= end_time)){
		var x;
		var input_key=prompt("Please enter the key to access the file","access key");
		if (input_key!=null)
		  {
		  if(input_key==key){
			myWindow=window.open('','','width=500,height=300');
			var container = document.getElementById("exe_container_"+lab+"_"+batch).innerHTML;
			myWindow.document.write("<title>Download Files</title><p><b>Ex No : "+ex_no+"<br/>Ex Name : "+ex_name+"</b><hr/><br/>"+container+"</p>");
			myWindow.focus();
		  }
		  else
			alert('You have provided a wrong key.\nPlease enter the correct key.');
		  }
		 else{
			alert('Enter a valid key');
		 }
	}
	else
		alert('You cannot view the file now');
}
</script>
</body>
</html>