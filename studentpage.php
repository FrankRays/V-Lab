<?php
session_start();
$post_act=0;
$post_act1=0;
include('db.php');

if(!isset($_SESSION['username']))
header("Location: index.php");
?>
<?php
//if profile image is not set 
$imagesetquery = mysql_query("select profileimage from logindetails where rollno='{$_SESSION['username']}'");
$imageset= mysql_fetch_array($imagesetquery);
if($imageset['profileimage']=="")
mysql_query("update logindetails set profileimage='imagedefault.jpg' where rollno='{$_SESSION['username']}'");
?>

<?php
//post all

if(isset($_POST['postall']))
{
		$e = mysql_query("SELECT grp from stud_details where id='{$_SESSION['username']}'");
		$e1 = mysql_fetch_array($e);
		$val1 = strtoupper($e1['grp']);
		$val2 = strtolower($e1['grp']);
		$res1 = mysql_query("create view sample as select * from logindetails join stud_details on logindetails.rollno = stud_details.id where logindetails.priv = 1 or logindetails.priv = 3");
		$res=mysql_query("select rollno from sample where grp = '{$val1}' or grp = '{$val2}' ;");
		while($row=mysql_fetch_array($res))
		mysql_query("insert into questions values('{$_SESSION['username']}','{$row['rollno']}','{$_POST['postcontent']}','','0','');");
		$post_act=1;
}


//php to perform logout

if(isset($_POST['logout']))
{
	//echo "updated";
	unset($_SESSION['username']);
	session_destroy();
	header("Location: index.php");
}
?>

<?php
//post is to be stored

if(isset($_POST['postbutton']))
{

	if($_POST['tostaff']!='')
	{
		if(mysql_query("insert into questions values('{$_SESSION['username']}','{$_POST['tostaff']}','{$_POST['postcontent']}','','0','');"))
		$post_act = 1;
	}
	else
		$post_act1 = 1;
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
			<li><a href="studentpage.php"title="">Post</a></li>
			<li><a href="studentinfo.php" title="">Info</a></li>
			<li><a href="studentposts.php" title="">Posted</a></li>
		
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
			
			<h3>Post Yours</h3><br/>
			<form name="postform" method="POST">
				<h4>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							To : <select name="tostaff"> 
								<?php
									$que1 = mysql_query("SELECT name,id,priv from acc_details");
										if(mysql_numrows($que1) == 0)
										{
											echo "<option value='none'>(None)</option>";
										}
									else
									{
											while($que_arr = mysql_fetch_array($que1))
											{
												if($que_arr['priv'] == 2)
												echo "<option value='".$que_arr['id']."'>{$que_arr['name']}</option>";
												else if($que_arr['priv'] == 5)
												echo "<option value='".$que_arr['id']."'>{$que_arr['name']} (Tutor)</option>";
											}
										}
									?>
							  </select></h4>
				<br>
				<br>
				<textarea name="postcontent" style="background:white"rows="4" cols="70"></textarea>
				<br>
				<center>
				<div id='but'><button style='width:70px;' type='submit' value='postbutton' name='postbutton'>Post</button></div>
				<?php
				$res=mysql_query("select * from logindetails where rollno='".$_SESSION['username']."'");
				$res1=mysql_fetch_array($res);
				if($res1['priv'] == 3 && (mysql_numrows($res) == 1))
				{
					echo "<br><br><h4>To post all students ... </h4><br>";
					echo"<div id='but'><button style='width:70px;' type='submit' value='postall' name='postall'>Post all</button></div>";
				}
				if($post_act == 1){
				echo "<center><font color='green' size='2'>Post Successful</font></center>";
				$post_act=0;
				}
				if($post_act1 == 1){
				echo "<center><font color='red' size='2'>* Select a staff</font></center>";
				$post_act1=0;
				}
				?>
				</center>
														
			</form>
			<p class="date"></p></div>
			<div>
			<br>
			</div>
	        <div>
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



