<?php
	session_start();
	include('db.php');
	if(isset($_POST['logout']))
	{	unset($_SESSION['username']);
		session_destroy();
	}
	if(isset($_POST['change']))
	{	header("Location: settings.php");}
	if(isset($_SESSION['username'])){	$name = $_SESSION['username'];}
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
			<li><a href='files.php' title=''>Files</a></li>
			<li><a href='st_upload.php' title=''>File Upload</a></li>
			<li><a href='labs.php' title=''>Labs</a></li>
		</ul>
	</div>
	<div id="logo">
	</div>
	 	
</div>	
<!-- header ends -->
<!-- content begins -->
 <div id="main">
	<div id="right">
		    <div id="right_box" class="box">
			<h3>LABS </h3><br />
			<?php
			$sql = mysql_query("SELECT labs FROM acc_details WHERE id='{$name}'");
			while($row=mysql_fetch_array($sql)){
				$labs = $row['labs'];
				$lab_array=explode('*',$labs);
				foreach($lab_array as $la){
					if($la!=''){
						echo "Lab :".$la."<br/>";
					}
				}
			}
			?>
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
		<h3>Options</h3>
		   <div class="title_back">
		   <?php
		   //$time = time() + 19800;
		   //echo "Time : ".$time."<br/>";
		   //echo date('H:i:s=>M/d/Y',$time);
		   
		   //echo date('H:i:s=>M/d/Y',43736500);
		   //$timestamp = mktime($hour, $min, $sec, $month, $day, $year)
		   //$timestamp = mktime(05,01,40,05,22,1971);
		   //echo "<br/>Timee :".$timestamp;
		   ?>
				<ul>
				<div id="but">
					<button type="submit" name="add_lab" onclick="add_lab()">Add a Lab</button></div>
					<div id="but">
					<button type="submit" name="add_faculty" onclick="add_faculty()">Add a Faculty</button></div>
					<div id="but">
					<button type="submit" name="add_students" onclick="add_students()">Add Students</button></div>
					<div id="but">
					<button type="submit" name="add_exe_files" onclick="add_exe_files()">Add Exercise</button></div>
					
				
				</ul>
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
<script>
function add_lab(){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("right_box").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_lab.php",true);
	xmlhttp.send();
}
function add_faculty(){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("right_box").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_faculty.php",true);
	xmlhttp.send();
}
function add_students(){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("right_box").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_students.php",true);
	xmlhttp.send();
}
function add_exe_files(){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("right_box").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_exe_files.php",true);
	xmlhttp.send();
}
function submit_add_laboratory(){
	var name = document.getElementById("lab_name").value;
	var sem_no = document.getElementById("semester_no").value;
	var batch_no = document.getElementById("batch_no").value;
	var dept = document.getElementById("dept").value;
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("lab_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=1&lab_name="+name+"&sem_no="+sem_no+"&dept="+dept+"&batch_no="+batch_no,true);
	xmlhttp.send();
}
function submit_add_faculty(){
	var fac_id = document.getElementById("faculty_id").value;
	var lab_id = document.getElementById("lab_id").value;
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("lab_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=2&fac_id="+fac_id+"&lab_id="+lab_id,true);
	xmlhttp.send();
}
function show_lab_files(lab_id){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("exercise_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=4&lab_id="+lab_id,true);
	xmlhttp.send();
}
function show_lab_students(lab_id){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("exercise_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=3&lab_id="+lab_id,true);
	xmlhttp.send();
}
function add_students_lab(lab_id){
	var students_group = document.getElementById("group_add_students").value;
	var batch_no = document.getElementById("group_add_students_batch").value;
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("exercise_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("POST","add_manipulate.php?add=5&lab_id="+lab_id,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("group_students="+students_group+"&batch_no="+batch_no);
}
</script>
</body>
</html>
