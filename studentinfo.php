<?php

session_start();
include('db.php');

if(!isset($_SESSION['username']))
header("Location: index.php");
?>
<?php

//userinfo
if(isset($_POST['changeinfo']))
{	
	
	$err = 0;
	//email verification
	$email = $_POST['uemail'];
		 if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
		{
			$err = 1;
		}	
	if($_POST['new'] == '1' && $err == 0)
	{
	if(mysql_query("Insert into stud_details values('{$_SESSION['username']}','{$_POST['uname']}','{$_POST['uadd']}','{$_POST['uemail']}','{$_POST['uphno']}','{$_POST['ucgpa']}','','','','{$_POST['dept']}','{$_POST['dept1']}');"))
	{
	echo "<center><font style=color:Green;position:absolute;top:820px;left:900px;>Information updated</font></center>";
	}
	}
	else if($_POST['new'] == '0' && $err == 0){
	if(mysql_query("UPDATE stud_details SET name='{$_POST['uname']}',address='{$_POST['uadd']}',email='{$_POST['uemail']}',phno='{$_POST['uphno']}',cgpa={$_POST['ucgpa']},grp='{$_POST['dept1']}',dept='{$_POST['dept']}' WHERE id = '{$_SESSION['username']}'"))
	echo "<center><font style=color:Green;position:absolute;top:820px;left:900px;>Information updated</font></center>";
	}
	else
	if($err == 1)
	echo "<center><font style=color:Red;position:absolute;top:820px;left:900px;>Not a valid E-mail ID</font></center>";
	
}
//perform password check

if(isset($_POST['changepassword']))
{
$old=mysql_query("select * from logindetails where rollno = '".$_SESSION['username']."';");
$oldcheck=mysql_fetch_array($old);
$oldcheck1=$_POST['oldpassword'];
	if($oldcheck['password'] == md5($oldcheck1))
	{				
						if($_POST['newpassword'] == $_POST['newpasswordcheck'])
						{
							if(mysql_query("UPDATE logindetails SET password=md5('{$_POST['newpassword']}') where rollno='{$_SESSION['username']}';"))
							echo "<center><font style=color:Green;position:absolute;top:435px;left:900px;>Password Changed</font></center>";		
						}
						else
						echo "<center><font style=color:red;position:absolute;top:435px;left:900px;>New Password didnot match</font></center>";	
	}	
	else
	{
		echo "<center><font color='red' style=position:absolute;top:435px;left:900px;>Old Password didnot match</font></center>";
	}
}		




//php to perform logout

if(isset($_POST['logout']))
{
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
				if($a_d['priv'] == 2 || $a_d['priv'] == 5)
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
					<form method="POST">
						
						<center><h3>Change Password </h3></center>
						<?php
						echo "<br><br>";
						echo "Old Password   &nbsp;&nbsp;&nbsp;&nbsp;<input type='password' name='oldpassword'>";
						echo "<br><br>";
						echo "New Password  &nbsp;&nbsp;&nbsp;<input type='password' name='newpassword'>";
						echo "<br><br>";
						echo "Retype Password   <input type='password' name='newpasswordcheck'>";
						echo "<br><br>";
						echo "<center><div id='but'><button style='width:70px;' type='submit' value='changepassword' name='changepassword'>Change</button></div></center>";
						
						?>
					</form>

			</div>
			<div class="box">
			<center><h3>Change Profile Picture </h3>(* Max size: 1Mb)</center><br><br>
			<form method="post" enctype="multipart/form-data">
			<pre>       Change Profile picture :<input type="file" name="file" id="file" /></center></pre>
			<br>
					<center><div id='but'><button style='width:100px;' type='submit' value='changeprofilepic' name='changeprofilepic'>ChangePicture</button></div></center>
					
			</form>
			
			<?php
			if(isset($_POST['changeprofilepic']))
			{
					if ($_FILES["file"]["error"] > 0)
					{
						if($_FILES["file"]["error"] == 5)
						echo "<center><font size=2.5 color=red>No file Uploaded</font></center>";
					}
					else{
					$allowedExts = array("jpg", "jpeg", "gif", "png","JPG","JPEG","GIF","PNG");
					$ext_array = explode(".", $_FILES["file"]["name"]);
					$extension = end($ext_array);
					if (($_FILES["file"]["size"] < 1048576)&& in_array($extension, $allowedExts))
					{
						$qr = mysql_query("SELECT profileimage from logindetails WHERE rollno='{$_SESSION['username']}'");
						$val  = mysql_fetch_array($qr);
						if($val[0] != "imagedefault.jpg" && $val[0] != ""){
						unlink("prof_image\\".$val[0]);}
						$target_path = $_SESSION['username'].".".$extension;
					//echo "Target :".$target_path;
						if(move_uploaded_file($_FILES['file']['tmp_name'], "prof_image\\".$target_path))
						{
							echo "<center><font style='color:Green'>Picture has been Changed</font>";
						}
						mysql_query("update logindetails set profileimage='{$target_path}' where rollno='{$_SESSION['username']}';");
						
						//echo "inside";
					}
					else
					{
						//echo "inside";
						//echo $_FILES["file"]["size"];
						if(!($_FILES["file"]["size"] < 1048576))
						echo "<center><font size=2.5 color=red>Max. file size must be <b> 1Mb </b></font></center>";
						if(!in_array($extension, $allowedExts))
						echo "<center><font size=2.5 color=red>File must be( .jpeg .jpg .png .gif) only</font></center>";
					}
					}
			}
			?>
			
			</div>
	        <div class="box">
			<form method="POST">			
						<center><h3>Change User Information </h3></center><br><br>
						
						<?php
							$new=0;
							$que11 = mysql_query("SELECT * from stud_details where id = '".$_SESSION['username']."'");
							$q = mysql_fetch_array($que11);
							$n = mysql_numrows($que11);
							$name ="";
							$add = "";
							$email ="";
							$phno ="";
							$cgpa ="";
							$dept="";
							$group="";
							if($n ==1)
							{
								$name = $q['name'];
								$add = $q['address'];
								$email = $q['email'];
								$phno = $q['phno'];
								$cgpa = $q['cgpa'];
								$dept = $q['dept'];
								$group = $q['grp'];
								$new = 0;
							}
							else
							$new = 1;
							echo "<table cellspacing='10'>";
							echo "<tr><td><b>Name     : </b></td><td><input type='text' size='30' value='".$name."' name='uname'></td></tr>";
							echo "<tr><td><b>Department     : </b></td><td><input type='text' size='30' value='".$dept."' name='dept'></td></tr>";
							echo "<tr><td><b>Group     : </b></td><td><input type='text' size='30' value='".$group."' name='dept1'></td></tr>";
							echo "<tr><td><b>Address : </b></td><td><textarea name='uadd' rows=3 cols=24>".$add."</textarea></td></tr>";
							echo "<tr><td><b>Email : </b></td><td><input type='text' size='30' value='".$email."' name='uemail'></td></tr>";
							echo "<tr><td><b>Phone Number : </b></td><td><input type='text' size='30' value='".$phno."' name='uphno'></td></tr>";
							echo "<tr><td><b>CGPA : </b></td><td><input type='text' size='30' value='".$cgpa."' name='ucgpa'></td></tr></table>";
							echo "<input type='hidden' name='new' value='".$new."'>";
						?>
						
						<center><div id='but'><button style='width:150px;' type='submit' value='changeinfo' name='changeinfo'>Change Information</button></div></center>
						
			</form>
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
			if($im['profileimage'] != '')
			$profileimage="prof_image/".$im['profileimage'];
			else
			$profileimage="prof_image/imagedefault.jpg";
			    //echo $profileimage;
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='{$profileimage}' width='110' height='150' ></image>";
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



