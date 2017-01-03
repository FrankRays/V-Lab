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
	unset($_SESSION['username']);
	session_destroy();
	header("Location: index.php");
}
?>

<?php
//post to be deleted
if(isset($_POST['deletepost']))
{	
	mysql_query("delete from questions where ind={$_POST['deleteposthid']};");
	
	echo "<div><center><h3 style=color:Green;position:absolute;top:275px;left:670px;>POST Deletion Successful</center></h3></div>";
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
			 <br><br>										
			<?php
			//showing the post datas
			$r = mysql_query("select * from questions order by ind desc;");
			
				while($row=mysql_fetch_array($r))
				{
				
				echo "<form method='POST'>";
				if($row['stu_id']==$_SESSION['username'])
				{
					//echo "<div class='post'>";
					echo "<table>";
					echo "<tr>";
					echo "<td>";
					$requestimagequery=mysql_query("select profileimage from logindetails where rollno='{$row['stu_id']}'");
					$requestimagename=mysql_fetch_array($requestimagequery);
					echo "<h4>POSTED to <u style=color:Black;> ".$row['staff_id']."</h4></u>";
					
					echo "<h2>";
					echo "<span style=\"color:black;font-family:Arial;\">".$row['ques']."</span>";
					echo "<input type='hidden' name='deleteposthid' value='{$row['ind']}'>";
					if($row['ans_set']==1)
					{
						$replyimagequery=mysql_query("select profileimage from logindetails where rollno='{$row['staff_id']}'");
						$replyimagename=mysql_fetch_array($replyimagequery);
						echo "<br><h4>REPLY from <u style=color:Green;>".$row['staff_id']."</u></h4>";
						echo "<h2>";
						echo "<span style=\"color:black;font-family:Arial;\">".$row['ans']."</span>"; 
						echo "<br><br>";
						//echo "</div>";
					}
					else
					echo "<br><h5 style='color:Grey'>NO REPLY Received</h5><br><br>";
					//no answer yet
					
					//echo "</td></tr>";
					//echo "<tr></tr>";
					//echo "<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>";
					echo "<td></td><td>";
					echo "<input type='submit' name='deletepost' value='x'>";
					echo "</td></tr>";										
				}
				echo "</table>";
				echo "</form>";
				
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