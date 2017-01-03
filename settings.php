<?php
	session_start();
	include('db.php');
	if(isset($_POST['logout']))
	{
		unset($_SESSION['username']);
		session_destroy();
	}
	if(isset($_POST['change']))
	{
		header("Location: settings.php");
	}
		$result=mysql_query("select * from acc_details where id= '".$_SESSION['username']."';");
		$no = mysql_numrows($result);
		$result1 = mysql_fetch_array($result);
		if($no==1)
		{
			$na = $result1['name'];
			$ad = $result1['address'];
			$ma = $result1['email'];
			$ph = $result1['phno'];
		}
		else
		{
			$na = "";
			$ad = "";
			$ma = "";
			$ph = "";
		}
		if(isset($_POST['del_img']))
		{
			$qr = mysql_query("SELECT profileimage from logindetails WHERE rollno='{$_SESSION['username']}'");
			$val  = mysql_fetch_array($qr);
			if($val[0] != "imagedefault.jpg" && $val[0] != "")
			{
			unlink("prof_image\\".$val[0]);
			mysql_query("UPDATE logindetails set profileimage='imagedefault.jpg' WHERE rollno='{$_SESSION['username']}'");
			}
		}
	if(isset($_POST['change_img']))
	{
					if ($_FILES["file"]["error"] > 0)
					{
						if($_FILES["file"]["error"] == 4)
						echo "<div id='msg' style='position:absolute;top:520px;left:885px;'><center><font size=2.5 color=red>Choose a Picture</font></center></div>";
					}
					else{
					$allowedExts = array("jpg", "jpeg", "gif", "png");
					$ext_array = explode(".", $_FILES["file"]["name"]);
					$extension = strtolower(end($ext_array));
					if (($_FILES["file"]["size"] < 1048576)&& in_array($extension, $allowedExts))
					{
						$target_path = $_SESSION['username'].".".$extension;
					
						
						$qr = mysql_query("SELECT profileimage from logindetails WHERE rollno='{$_SESSION['username']}'");
						$val  = mysql_fetch_array($qr);
						mysql_query("update logindetails set profileimage='{$target_path}' where rollno='{$_SESSION['username']}';");
						if($val[0] != "imagedefault.jpg" && $val[0] != ""){unlink("prof_image\\".$val[0]);}
						
						if(move_uploaded_file($_FILES['file']['tmp_name'], "prof_image\\".$target_path))
						{
							echo "<div id='msg' style='position:absolute;top:520px;left:885px;'><center><font style='color:Green'>Picture has been Changed</font></center></div>";
						}
					}
					else
					{
						if(!($_FILES["file"]["size"] < 1048576))
						echo "<div id='msg' style='position:absolute;top:520px;left:885px;'><center><font size=2.5 color=red>Max. file size must be <b> 1Mb </b></font></center></div>";
						if(!in_array($extension, $allowedExts))
						echo "<div id='msg' style='position:absolute;top:520px;left:885px;'><center><font size=2.5 color=red>File must be( .jpeg .jpg .png .gif) only</font></center></div>";
					}
					}
	}
	if(isset($_POST['submit']))
	{
		$err = 0;
		//email verification
		$email = $_POST['email'];
		 if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
		{
			$err = 1;
		}
		$result=mysql_query("select * from acc_details where id= '".$_SESSION['username']."';");
		$no = mysql_numrows($result);
		if($err==0)
		{
			if(($no == 1) && ($_SESSION['password'] == $_POST['pass']) )
			{
	
				$res = mysql_query("UPDATE acc_details set name = '".$_POST['name']."', address = '".$_POST['add']."', email= '".$_POST['email']."', phno= '".$_POST['phno']."' where id='".$_SESSION['username']."';");
				echo "<div id='warn' class='drag'>";
				echo "<font size=2em color=green><b>Updated Successfully</b></font></div>";
			}
			else{
				echo "<div id='warn' class='drag'>";
				echo "<font size=2em color=red><b>Wrong Password!</b></font></div>";
				//echo "<script type='text/javascript'> alert('Enter the correct password!');</script>";
				//header("Location: settings.php");
			}
		}
		else
		{
			echo "<div id='warn' class='drag'>";
				echo "<font size=2em color=red><b>E-mail not valid</b></font></div>";
		}
	}
	if(isset($_SESSION['username']))
	{
		
		$name = $_SESSION['username'];
		//echo "<script type='text/javascript'> alert('No Authentication provided'); </script>";
	}
	else
		header("Location: index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Faculty page</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/basiccalendar.js"></script> 
</head>
<body>
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
			echo "<li><a href='st_upload.php' title=''>File Upload</a></li>";
				}
			?>
			<li><a href="tasks.php" title="">Tasks</a></li>
		</ul>
	</div>	
	<div id="logo">
	</div>
	 	
</div>	
<!-- header ends -->
<!-- content begins -->
 <div id="main">
	<div id="right">
		    <div class="box">
			<div style="position:absolute;top:300px;left:880px;width:100px;height:100px;">
			<?php
			$res=mysql_query("select profileimage from logindetails where rollno='{$_SESSION['username']}'");
			while($im=mysql_fetch_array($res)){
			if($im['profileimage'] != '')
			$profileimage="prof_image/{$im['profileimage']}";
			else
			$profileimage="prof_image/imagedefault.jpg";
			 }
			echo "<img src='{$profileimage}' width='100' height='100' ></image>";
			
			?>
			</div>
			<h3>Change Accounts Settings</h3><br />
			<div id="form" style=" padding: 17px 0px 15px 15px;">
			<form method="post" name="change" enctype="multipart/form-data">
			<table>
			<tr><td>Name 	 : </td><td><input type="text" name="name" size=24 value="<?php echo $na; ?>"/></td></tr><tr></tr>
			<tr><td>Address  : </td><td><textarea type="text" name="add" rows=5 cols=20 ><?php echo $ad; ?></textarea></td></tr><tr></tr>
			<tr><td>Email ID : </td><td><input type="text" name="email" size=24 value="<?php echo $ma; ?>" /></td></tr><tr></tr>
			<tr><td>Ph.no    : </td><td><input type="text" name="phno" size=24 value="<?php echo $ph; ?>" /></td></tr><tr></tr>
			<tr><td>Password : </td><td><input type="password" name="pass" size=24 /> * Required</td></tr><tr></tr></table>
			<div>
			<div id="but" style="position:absolute;top:420px;left:880px;">
			<input type="file" name="file" id="file" /><pre>(*Max.size 1Mb)</pre><button type="submit" name="change_img" style="width:150px;">Change Picture</button></div>
			<div id="but" style="position:absolute;top:485px;left:880px;"><button type="submit" name="del_img" style="width:150px;">Delete Picture</button></div>
			</div>
			<div id="tab" style="padding:10px 0px 0px 60px; "><table>
			<tr><td></td><td><div id="but" style="padding:0px 10px; width:70px;"><button type="submit" name="submit" style="width:70px;"> Submit</button></div></td><td>
							<div id="but" style=" width:60px;"><button type="reset" name="cancel" style="width:70px;">Cancel</button></div></td></tr>
			</table>
			</div>
			</form>
			</div>
			<!--p class="date"><img src="images/comment.gif" alt="" /> <a href="http://www.free-css-layouts.com/bz99wxw.php?go=wxw">Comments(2)</a></p--></div>
			<div class="box">
			</div>
	</div>
  	<div id="left">

		<h3>Account Settings</h3>
			<div class="title_back">
			<ul>
				<li><ul>
					<form method="post"><div id="but">
					<button type="submit" value="change" name="change">Change Settings</button></div>
					<div id="but">
					<button type="submit" value="Logout" name="logout">Logout</button></div>
					</form>
					
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
<p>Copyright &copy; 2012. 
Designed by Batch 11</p>
	</div>
</div>
<!-- footer ends-->
</body>
</html>
