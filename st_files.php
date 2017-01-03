<?php
	session_start();
	include('db.php');
	if(isset($_POST['logout']))
	{
		unset($_SESSION['username']);
		session_destroy();
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
<title><?php
			echo"Welcome :".$_SESSION['username'];
			?></title>
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
	</div>	
	<div id="logo">
	</div>
	 	
</div>	
<!-- header ends -->
<!-- content begins -->
 <div id="main">
	<div id="right">
			<div class="box">
			<h3>Files</h3>
			<br />
			<?php
			
				// delete file in database
				if(isset($_POST['del']))
				{
					
					$qr = mysql_query("SELECT filename from fileupload WHERE staff_id='{$_SESSION['username']}' and ind = ".$_POST['hid_val'].";");
					$str = mysql_fetch_array($qr);
					$ext_array = explode(".",$str[0]);
					$extension = strtolower(end($ext_array));
					unlink("files\\".$_SESSION['username'].$_POST['hid_val'].".".$extension);
					mysql_query("DELETE from fileupload WHERE staff_id = '".$_SESSION['username']."' and ind = ".$_POST['hid_val'].";");
				}
				
				//content in box...
				$f_que = mysql_query("SELECT * from fileupload WHERE staff_id = '".$_SESSION['username']."'");
				$i = 0;
				if(mysql_numrows($f_que)== 0)
				{
					echo "<center><font color=red>No File(s) Available</font></center>";
				}
				echo "<form method='post'>";
				
				while($f_arr = mysql_fetch_array($f_que))
				{
					echo "<div class='box'><div id='but'>";
					echo ($i+1).". <b>".$f_arr['filename']."  </b><font color=green>&nbsp;&nbsp;(posted by ".$f_arr['rollno'].")</font>";
					echo "<div style='float:right;'><button style='width:70px;' type='submit' value='f_down' name='f_down'>Download</button>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button style='width:70px;' type='submit' value='del' name='del'>Delete</button></div></div>";
					echo "<input type='hidden' name='hid_val' value='".$f_arr['ind']."'>";
					echo "</div>";
					$i = $i + 1;
					
				}
				echo "</form>";
				
				//download script
				if(isset($_POST['f_down']))
				{
					unlink("files\\".$_SESSION['username'].$_POST['hid_val'].".".$extension);
					//
					$que = mysql_query("SELECT filename from fileupload WHERE staff_id = '".$_SESSION['username']."' and ind = ".$_POST['hid_val']."");
					$res = mysql_fetch_array($que);
					$ext_array = explode(".",$res[0]);
					$extension = strtolower(end($ext_array));
					$fullPath ='files\\'.$_SESSION['username'].$_POST['hid_val'].".".$extension;
					// Must be fresh start 
					if( headers_sent() ) 
					die('Headers Sent'); 
					// Required for some browsers 
					if(ini_get('zlib.output_compression')) 
					ini_set('zlib.output_compression', 'Off'); 
					// File Exists? 
					if( file_exists($fullPath) )
					{ 

					// Parse Info / Get Extension 
					$fsize = filesize($fullPath); 
					$path_parts = pathinfo($fullPath); 
					$ext = strtolower($path_parts["extension"]); 

					// Determine Content Type 
					switch ($ext) { 
					case "pdf": $ctype="application/pdf"; break; 
					case "exe": $ctype="application/octet-stream"; break; 
					case "zip": $ctype="application/zip"; break; 
					case "doc": $ctype="application/msword"; break; 
					case "xls": $ctype="application/vnd.ms-excel"; break; 
					case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
					case "gif": $ctype="image/gif"; break; 
					case "png": $ctype="image/png"; break; 
					case "jpeg": 
					case "jpg": $ctype="image/jpg"; break; 
					default: $ctype="application/force-download"; 
					} 

					header("Pragma: public"); // required 
					header("Expires: 0"); 
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
					header("Cache-Control: private",false); // required for certain browsers 
					header("Content-Type: $ctype"); 
					header("Content-Disposition: attachment; filename=\"".$res[0]."\";" ); 
					header("Content-Transfer-Encoding: binary"); 
					header("Content-Length: ".$fsize); 
					ob_clean(); 
					flush(); 
					readfile( $fullPath );
					}
					else
					echo "<center><font color=red>File not Found!!</font></center>";
				}
				
				
			
			?>
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
<p>Copyright &copy; 2012. 
Designed by Batch 11</p>
	</div>
</div>
<!-- footer ends-->
</body>
</html>
