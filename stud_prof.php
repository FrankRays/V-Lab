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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/animatedcollapse.js"></script>
<script type="text/javascript">
animatedcollapse.addDiv(1, 'fade=1,height=170px');
animatedcollapse.addDiv(2, 'fade=1,height=140px');
animatedcollapse.addDiv(10, 'fade=1,height=510px');
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
			
				$que1 = mysql_query("SELECT rollno from logindetails where priv = 1 ORDER BY rollno");
				if(isset($_POST['fsub']))
				{
						$no = mysql_query("SELECT * from stud_details where id='".$_POST['roll']."'");
						$n = mysql_numrows($no);
						$pr = 1;
						if(isset($_POST['repchk']))
						$pr = 3;
						mysql_query("UPDATE logindetails SET priv={$pr} where rollno='{$_POST['roll']}'");
						if($n == 1)
						{
						$upda = mysql_query("UPDATE stud_details set name = '".$_POST['sname']."',address = '".$_POST['saddress']."', email = '".$_POST['semail']."', phno = '".$_POST['sphno']."',cgpa = '".$_POST['scgpa']."',rank = '".$_POST['srank']."',achievements = '".$_POST['sachieve']."',remarks = '".$_POST['sremarks']."',grp='{$_POST['sdept1']}',dept='{$_POST['sdept']}' where id='".$_POST['roll']."'");
						}
						echo "<center><font color='green'>".$_POST['roll']." Changed Successfully !!</font></center><br>";
						
				}
				if(isset($_POST['newsub']))
				{
					if($_POST['newroll']!=''){
						$res = mysql_query("SELECT * from logindetails where rollno='".$_POST['newroll']."'");
						$num = mysql_numrows($res);
						$priv =1;
						
						if($num == 0){
							mysql_query("INSERT into logindetails(rollno,password,priv) values('".$_POST['newroll']."',md5('".$_POST['newpass']."'),'{$priv}')");
							mysql_query("INSERT into stud_details(id,labs) values('".$_POST['newroll']."','*')");
							}
						else
							mysql_query("UPDATE logindetails set password='".$_POST['newpass']."' where rollno = '".$_POST['newroll']."'");
						
						echo "<center><font color='green'>".$_POST['newroll']." Created Successfully !!</font></center><br>";
					}
					else{
						echo "<span class='alert'>Roll No cannot be an empty value.</span>";
					}
				}
				if(isset($_POST['group_add_submit'])){
					$res = $_POST['group_add'];
					if($res!=""){
						$res_array = explode(',',$res);
						$success = "";
						$pass_change = "";
						foreach($res_array as $new_roll){
							if($new_roll!=""){
							
								if(!preg_match("/\W/",$new_roll)==false)
								{	echo "<span class='alert'>The roll no \"{$new_roll}\" should contain only alphanumeric characters.</span><br/><br/>";  
								}
								else{
									$new_pass = md5($new_roll);
									$sql = mysql_query("SELECT * FROM logindetails WHERE rollno='{$new_roll}'");
									$c = mysql_num_rows($sql);
									if($c==0){
										mysql_query("INSERT into logindetails(rollno,password,priv) VALUES('{$new_roll}','{$new_pass}','1')");
										mysql_query("INSERT into stud_details(id,labs) values('{$new_roll}','*')");
										$success = $new_roll.", ".$success;
									}
									elseif($c==1){
										mysql_query("UPDATE logindetails set password='{$new_pass}' where rollno = '{$new_roll}'");
										$pass_change = $new_roll.", ".$pass_change;
									}
									else{
										echo "<span class='alert'>Error: ";
										mysql_error();
										echo"</span>";
									}
								}	
							}
						}
						if($success!=""){
							echo "<span class='success'>The Roll no's ".$success." have been created with password same as the roll nos.</span><br/><br/>";
						}
						if($pass_change!=""){
							echo "<span class='alert'>The Roll no's ".$pass_change." already exists. Their password's have been reset to their roll nos.</span><br/><br/>";
						}
					}
					else{
						echo "<span class='warning'>There are no roll nos specified in the list.</span>";
					}
				}
				
					echo "<form method= 'post'>";
					echo "<table><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rollno: </td><td>&nbsp;&nbsp;&nbsp;<select name='list' id='listid' >";
				while($s = mysql_fetch_array($que1))
				{
					echo "<option value='".$s['rollno']."'>".$s['rollno']."</option>";
				}
				echo "</select></td>";
				echo "<td><div id='but'>&nbsp;&nbsp;&nbsp;<button style='width:40px;' type='submit' value='listsub' name='listsub'>View</button></div></td>";
				if($a_d['priv'] == 5 ){
				echo "<td><div id='but'>&nbsp;&nbsp;&nbsp;<button style='width:40px;' type='submit' value='addsub' name='add'>Add</button></div></td>";
				}
				echo "</tr></table></form>";
				
				if(isset($_POST['listsub']))
				{
					//name of the student
					$value = $_POST['list'];
					$res=mysql_query("select profileimage from logindetails where rollno='".$value."'");
					$im=mysql_fetch_array($res);
					if($im['profileimage'] != '')
					$profileimage="prof_image/".$im['profileimage'];
					else
					$profileimage="prof_image/	imagedefault.jpg";
					//echo $profileimage;
					
					echo "<div style='position:absolute;top:280px;left:930px;'><img style='border:3px solid #fff;' src='".$profileimage."' width='80' height='100' ></image></div>";
					
					//value retrieval
					//
					//
					$que11 = mysql_query("SELECT * from stud_details where id = '".$value."'");
					$r = mysql_query("SELECT priv from logindetails where rollno = '{$value}'");
					$q = mysql_fetch_array($que11);
					$r1 = mysql_fetch_array($r);
					echo "<div style='top:7px;position:relative;border: 0px solid #0ff ;'><div style='padding: 10px 0px 10px 220px;'><b>Rollno : </b>".$value."";
					if($a_d['priv'] == 5 ){
						echo "<a href='javascript:animatedcollapse.toggle(10)'>&nbsp;&nbsp;&nbsp;<font color=red><u>Edit</u></font></a>";
					}
					echo "</div>";
					echo "<table><tr></tr><tr></tr><tr></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
					echo "<a href='javascript:animatedcollapse.toggle(1)'>
						<font color=green>General Information</font></a></td><td>&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<a href='javascript:animatedcollapse.toggle(2)'>
						<font color=green>Academic Details</font></a></td></tr></table><br><br>";
					echo "<div id='1' style='width: 595px; background: #ededed; display:none;'>";
					echo "<table cellspacing='10'>";
					echo "<tr><td><b>Name     : </b></td><td>".$q['name']."</td></tr>";
					echo "<tr><td><b>Department    : </b></td><td>".$q['dept']."</td></tr>";
					echo "<tr><td><b>Group     : </b></td><td>".$q['grp']."</td></tr>";
					echo "<tr><td><b>Address : </b></td><td>".$q['address']."</td></tr>";
					echo "<tr><td><b>Email : </b></td><td>".$q['email']."</td></tr>";
					echo "<tr><td><b>Phone Number : </b></td><td>".$q['phno']."</td></tr></table></div>";	
					echo "<div id='2' style='width: 595px; background: #ededed; display:none;'>";
					echo "<table cellspacing='10'><tr><td><b>CGPA : </b></td><td>".$q['cgpa']."</td></tr>";
					echo "<tr><td><b>Rank : </b></td><td>".$q['rank']."</td></tr>";
					echo "<tr><td><b>Remarks : </b></td><td>".$q['remarks']."</td></tr>";
					echo "<tr><td><b>Achievements : </b></td><td>".$q['achievements']."</td></tr></table>";
				
					echo "</div>";
					
					echo "<div id='10' style='width: 595px; background: #ededed; display:none;'>";
					echo "<form method='post'><table cellspacing='10'>";
					echo "<tr><td><b>Name     : </b></td><td><input type='text' value='".$q['name']."' name='sname' /></td></tr>";
					echo "<tr><td><b>Department     : </b></td><td><input type='text' value='".$q['dept']."' name='sdept' /></td></tr>";
					echo "<tr><td><b>Group     : </b></td><td><input type='text' value='".$q['grp']."' name='sdept1' /></td></tr>";
					echo "<tr><td><b>Address : </b></td><td><textarea name='saddress' rows=4 >".$q['address']."</textarea></td></tr>";
					echo "<tr><td><b>Email : </b></td><td><input type='text' value='".$q['email']."' name='semail'></td></tr>";
					echo "<tr><td><b>Phone Number : </b></td><td><input type='text' value='".$q['phno']."' name='sphno'></td></tr>";	
					echo "<tr><td><b>CGPA : </b></td><td><input type='text' value='".$q['cgpa']."' name='scgpa'></td></tr>";
					echo "<tr><td><b>Rank : </b></td><td><input type='text' value='".$q['rank']."' name='srank'></td></tr>";
					echo "<tr><td><b>Remarks : </b></td><td><textarea name='sremarks' rows=4 >".$q['remarks']."</textarea></td></tr>";
					echo "<tr><td><b>Achievements : </b></td><td><textarea name='sachieve' rows=4 >".$q['achievements']."</textarea></td></tr>";
					if($r1['priv'] == 3)
					echo "<tr><td></td><td><input type='checkbox' checked='true' name='repchk'><b>&nbsp;&nbsp;&nbsp;&nbsp;Representative</b></td></tr></table>";
					else
					echo "<tr><td></td><td><input type='checkbox' name='repchk'><b>&nbsp;&nbsp;&nbsp;&nbsp;Representative</b></td></tr></table>";
					echo "<div id='but' style='padding:0px 0px 0px 125px'><button style='width:70px;' type='submit' value='fsubmit' name='fsub'>Submit</button>&nbsp;";
					echo "&nbsp;&nbsp;&nbsp;<button style='width:70px;' type='reset'>Clear</button></div>";
					echo "<input type='hidden' name='roll' value='".$value."'>";
					echo "</form></div></div>";	
					
				
				
				}
				
				if((isset($_POST['add']))||(isset($_GET['add'])))
				{
					echo "<div style='padding: 30px 0px 0px 140px;'>";
					echo "<form method='post' action='stud_prof.php?add=1'>";
					echo "<table><tr><td>Rollno: </td><td><input type='text' name='newroll' /></td></tr>";
					echo "<tr><td>Default Password: </td><td><input type='text' name='newpass' /></td></tr><tr></tr>";
					echo "<tr><td></td><td><div id='but' style='padding:5px 0px 0px 0px'><button style='width:70px;' type='submit' value='newsubmit' name='newsub'>Create</button>&nbsp;&nbsp;&nbsp;&nbsp";
					echo "<button style='width:70px;' type='reset' value='newsubmit' name='newres'>Clear</button></div></td></tr></table></form></div>";
					echo "<div id='but'><br/><b><u>Add a group of people </b><i></i></u><br/>
					<form method='post' action='stud_prof.php?add=1'><textarea name='group_add' rows='4' cols='40' placeholder='Enter the rollnos separated with comma E.g, 10i310,10i338,10i349,11i464... Password is same as the rollno'></textarea>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='submit' name='group_add_submit' style='height:60px;margin-top:0px;position:absolute;'>Add to the list</button></form></div>";
				}
			?>
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
Designed by Batch 11</p>
	</div>
</div>
<!-- footer ends-->
</body>
</html>
