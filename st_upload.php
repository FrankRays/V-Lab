<?php
session_start();
include('db.php');


if(!isset($_SESSION['username']))
header("Location: index.php");

//php to perform logout
if(isset($_POST['logout']))
{
	//echo "updated";
	unset($_SESSION['username']);
	session_destroy();
	header("Location: index.php");
}

if(isset($_POST['change']))
	{
		header("Location: settings.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
			<script type="text/javascript" src="js/basiccalendar.js"></script> 
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<title>
			Faculty Page
			</title>
			<meta name="keywords" content="" />
			<meta name="description" content="" />
			<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body background="bg.jpg">
<div id="content">
<!-- header begins -->
<div id="header">
<div id="menu">
		<ul>
			<li><a href="faculty.php"  title="">Home</a></li>
			<li><a href="stud_prof.php" title="">Students Profile</a></li>
			<li><a href="questions.php" title="">Questions</a></li>
			<?php 
			$d = mysql_query("SELECT priv from logindetails where rollno = '".$_SESSION['username']."'");
			$a_d = mysql_fetch_array($d);
			if($a_d['priv'] == 1 || $a_d['priv'] == 3){
			echo "<li><a href='st_files.php' title=''>Files</a></li>";
			echo "<li><a href='studentfileupload.php' title=''>File Upload</a></li>";
			}
			else
				if($a_d['priv'] == 2 || $a_d['priv'] == 5)
				{
					echo "<li><a href='files.php' title=''>Files</a></li>";
			echo "<li><a href='st_upload.php' title=''>File Upload</a></li>
			<li><a href='labs.php' title=''>Labs</a></li>";
				}
			?>
			</ul>
				&nbsp;&nbsp;
				
</div>	

<div id="logo">
</div>
</div>	

<!-- header ends -->
<!-- content begins -->
 <div id="main">

	<div id="right">
	
		    <div class="box">
									<h3><u>File Upload</u></h3>
									<form method="post" enctype="multipart/form-data">
									<?php
										echo "<h4><center>To : <select name='staff_name'></center>";
										$que1 = mysql_query("SELECT stud_details.name,stud_details.id,logindetails.priv from stud_details join logindetails on stud_details.id = logindetails.rollno");
										
										if(mysql_numrows($que1) == 0)
										{
											echo "<option value='none'>(None)</option>";
										}
										else
										{
											while($que_arr = mysql_fetch_array($que1))
											{
												if($que_arr['priv'] == 1)
												echo "<option value='".$que_arr['id']."'>".$que_arr['name']."</option>";
												else
												if($que_arr['priv'] == 3)
												echo "<option value='".$que_arr['id']."'>".$que_arr['name']." (Representative)</option>";
											}
										}
										echo "</select></h4>";
										echo "<br><br>";
										
											
									?>
									
									<center><pre>Upload File(* Max. size: 5Mb)   <input type="file" name="file" id="file"></pre></center>
									<br>
											<div id='but' style='padding: 0px 0px 0px 170px'><button style='width:250px;' type='submit' value='fileupload' name='fileupload'>Post the File to this student</button>&nbsp;&nbsp;&nbsp;&nbsp;</div>
											<!--div id='but'><button style='width:100px;' type='submit' value='filedownload' name='filedownload'>Download File</button></div-->
											<br>
									</form>
									
									<?php
									//uploading the files to server 
									if(isset($_POST['fileupload']))
									{
											if($_POST['staff_name'] == "none")
											{
												echo "<center><font size=2.5 color=red>No staff available..</font></center>";
											}
											else
											{
												if ($_FILES["file"]["error"] > 0)
												{
													
													if($_FILES["file"]["error"] == 4)
													echo "<center><font size=2.5 color=red>Choose a File to Upload.</font></center>";
												}
												else
												{
													
													$allowedExts = array("jpg", "jpeg", "gif", "png","docx","doc","pdf","pptx","ppt","xls","xlsx","rar","txt");
													$ext_array = explode(".", $_FILES["file"]["name"]);
													$extension = strtolower(end($ext_array));
													if (($_FILES["file"]["size"] <= 5242880)&& (in_array($extension, $allowedExts)))
													{
														
													//echo "Target :".$target_path;
														$q = mysql_query("SELECT * from fileupload WHERE rollno ='{$_SESSION['username']}' and filename = '{$_FILES['file']['name']}' and staff_id = '{$_POST['staff_name']}';");
														if(mysql_numrows($q) == 0)
														mysql_query("insert into fileupload values('{$_SESSION['username']}','{$_FILES['file']['name']}','{$_POST['staff_name']}','0');");
														$ind_q = mysql_query("SELECT ind from fileupload WHERE rollno ='{$_SESSION['username']}' and filename = '{$_FILES['file']['name']}' and staff_id = '{$_POST['staff_name']}';");
														$ind_val = mysql_fetch_array($ind_q);
														$target_path = $_POST['staff_name'].$ind_val[0].".".$extension;
														if(move_uploaded_file($_FILES['file']['tmp_name'], "files\\".$target_path))
														{
															echo "<center><font style='color:Green'>Your File has been Uploaded Successfully...</font>";
														}
														
														//echo "inside";
													}
													else
													{
														//echo "inside";
														//echo $_FILES["file"]["size"];
														if(!($_FILES["file"]["size"] < 5242880))
														echo "<center><font size=2.5 color=red>Max. file size must be <b>5Mb</b></font></center>";
														if(!in_array($extension, $allowedExts))
														echo "<center><font size=2.5 color=red>File Format doesn't support</font></center>";
													}
												}
											}
										
									}
									?>
									
										
								
									
			<p class="date"></p></div>
				</div>
  	<div id="left">
	<h3>Account Settings</h3>
			<div class="title_back">
			<ul>
				<li><ul>
					<form method="post"><div id="but">
					<button type="submit" value="change" name="change">Change Settings</button></div>
					<div id="but">
					<button type="submit" value="Logout" name="logout">Logout</button></div></form>
					
					</ul>
			  </li>
			</ul>
			</div>
			<br />
		<h3>Calendar</h3>
		   <div class="title_back">
				<script type="text/javascript">
					var todaydate=new Date()
					var curmonth=todaydate.getMonth()+1 //get current month (1-12)
					var curyear=todaydate.getFullYear() //get current year
					document.write(buildCal(curmonth ,curyear, "main", "month", "daysofweek", "days", 1));
				</script>
			</div>
			<br />
	</div>
<!--content ends -->

<!--footer begins -->

<div style="clear: both"></div>

	</div>
		<div id="footer">
		</div>
</div>
<!-- footer ends-->
</body>
</html>



