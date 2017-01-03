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
<script type="text/javascript" src="js/basiccalendar.js"></script> 
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Faculty page</title>
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
			echo "<li><a href='labs.php' title=''>Labs</a></li>";
				
				}
			?>
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
			<h3>Welcome <?php echo $_SESSION['username'] ?></h3><br />

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
<p>Copyright &copy; 2012. 
Designed by Batch 1</p>
	</div>
</div>
<!-- footer ends-->
</body>
</html>
