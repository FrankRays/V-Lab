<?php
session_start();
include('db.php');


if(!isset($_SESSION['username']))
header("Location: index.php");
?>
<?php
//php to perform logout
if(isset($_POST['logout']))
{
	//echo "updated";
	unset($_SESSION['username']);
	session_destroy();
	header("Location: index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<title>
			<?php
			echo"Welcome :".$_SESSION['username'];
			?>
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
			<li><a href="studentpage.php"title="">POST</a></li>
			<li><a href="studentinfo.php" title="">Info</a></li>
			<li><a href="studentposts.php" title="">POSTED</a></li>
		
			<?php 
			$d = mysql_query("SELECT priv from logindetails where rollno = '".$_SESSION['username']."'");
			$a_d = mysql_fetch_array($d);
			if($a_d['priv'] == 1 || $a_d['priv'] == 3){
			echo "<li><a href='st_files.php' title=''>Files</a></li>";
			echo "<li><a href='studentfileupload.php' title=''>File Upload</a></li>
			<li><a href='student_labs.php' title=''>Labs</a></li>";
			}
			else
				if($a_d['priv'] == 2 || $a_d['priv'] == 4)
				{
					echo "<li><a href='files.php' title=''>Files</a></li>";
			echo "<li><a href='st_upload.php' title=''>File Upload</a></li>";
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
										$que1 = mysql_query("SELECT name,id,priv from acc_details");
										if(mysql_numrows($que1) == 0)
										{	echo "<option value='none'>(None)</option>";	}
										else
										{	while($que_arr = mysql_fetch_array($que1))
											{
												if($que_arr['priv'] == 2)
												echo "<option value='".$que_arr['id']."'>".$que_arr['name']."</option>";
												else
												if($que_arr['priv'] == 4)
												echo "<option value='".$que_arr['id']."'>".$que_arr['name']." (HOD)</option>";
											}
										}
										echo "</select></h4>";
										echo "<br><br>";
										
											
									?>
									
									<center><pre>Upload File(* Max. size: 5Mb)   <input type="file" name="file" id="file"></pre></center>
									<br>
											<div id='but' style='padding: 0px 0px 0px 240px'><button style='width:100px;' type='submit' value='fileupload' name='fileupload'>Upload File</button>&nbsp;&nbsp;&nbsp;&nbsp;</div>
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
			
			if($im['profileimage'] != '')
			$profileimage="prof_image/".$im['profileimage'];
			else
			$profileimage="prof_image/imagedefault.jpg";
			    //echo $profileimage;
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
		</div>
</div>
<!-- footer ends-->
</body>
</html>



