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
		
	if(isset($_POST['sub_ans']))
	{
		$i = $_POST['sub_ans'];
		$query = mysql_query("UPDATE questions set ans = '".$_POST[$i]."' where ind = '".$_POST['sub_ans']."';");
		mysql_query("UPDATE questions set ans_set = 1 where ind = '".$_POST['sub_ans']."'; ");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!DOCTYPE HTML>

<script type="text/javascript" src="js/basiccalendar.js"></script> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/animatedcollapse.js"></script>
<script type="text/javascript">
			<?php 
				$y = 0;
				$count = 0;
				$que1 = mysql_query("select ind from questions where staff_id = '".$_SESSION['username']."' and ans_set = 0;");
				while($w = mysql_fetch_array($que1))
				{
					$s[$y] = $w['ind'];
					$y = $y + 1;
					$var = $w['ind'];
					$count = $count + 1;
				}
				$e = mysql_numrows($que1);
				
				while($count!=0)
				{
					$s[$y] = ++$var;
					$count = $count - 1;
					$y = $y+1;
				}
				
			?>
	var sa = ["<?php echo join("\",\"",$s); ?>"];
	var n = <?php echo $e; ?>;
	
	for(i=0;i<n;i++){
	animatedcollapse.addDiv(sa[i], 'fade=1,height=50px');}
	for(i=n;i<(2*n);i++){
	animatedcollapse.addDiv(sa[i], 'fade=1,height=100px');}
	
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

animatedcollapse.init();
</script>
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
			<li><a href="faculty.php"  title="Home page">Home</a></li>
			<li><a href="stud_prof.php" title="">Students Profile</a></li>
			<li><a href="questions.php" title="current page">Questions</a></li>
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
	</div>	
	<div id="logo">
	</div>
	 	
</div>	
<!-- header ends -->
<!-- content begins -->
 <div id="main">
	<div id="right">
		    <div class="box">

				<?php 
						$que1 = mysql_query("select ind,ques,stu_id from questions where staff_id = '".$_SESSION['username']."' and ans_set = 0;");
						$que2 = mysql_query("select ind,ques,stu_id from questions where staff_id = '".$_SESSION['username']."' and ans_set = 0;");
						$va = 0;
						//$que2 = mysql_query("select a.name as name from acc_details a join questions q on a.id = q.stud_id where q.staff_id = '".$_SESSION['username']."' and q.ans_set = 0;");
						$i = 0;
						while($s1 = mysql_fetch_array($que2))
						{
							$va = $s1['ind'];
						}
						
						while($s = mysql_fetch_array($que1))
						{
							// $s1 = mysql_fetch_array($que2);
							$var = $s['ind'];
							echo "<p><b>Question ".($i+1)." :</b>&nbsp;&nbsp;&nbsp;";
							echo "<a href='javascript:animatedcollapse.toggle(".$var.")'>";
							echo "<font color=red>View</font></a>&nbsp;&nbsp;&nbsp;<!--posted by: <font color=grey>".$s['stu_id']."</font--> </p>";
							echo "<div id='".$var."' style='width: 600px; background: #f5f5f5; display:none;'>";
							echo "".$s['ques']."<br /><p></p><p></p>";					
							echo "<div id='answer'><a href='javascript:animatedcollapse.toggle(".($va+1).")'><font color=blue>Answer</font></a></div>";
							echo "<div id='date'>Posted by: <a href='stud_prof.php'><font color=red>".$s['stu_id']."</font></a></div><p></p>";
							echo "<div id='".($va+1)."' style='width: 600px; background: #f5f5f5; display:none; position:relative;'>";
							echo "<form method='post'>";
							echo "<textarea name='".$s['ind']."' rows=4 cols=73> </textarea>";
							echo "<div id='but'><button style='width:70px;' type='submit' name='sub_ans' value='".$s['ind']."'>Submit</button> &nbsp;&nbsp;&nbsp;";
							echo "<button type='reset' style='width:70px;' name='clr_ans' value='clear'>Clear</button></div>";
							echo "</form>";
							echo "</div>";
							echo "</div><hr style='margin: 1em 0' />";
							$va = $va+1;
							$i = $i + 1;
						}
				?>

			</div>
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
Designed by Batch 11</p>
	</div>
</div>
<!-- footer ends-->
</body>
</html>
