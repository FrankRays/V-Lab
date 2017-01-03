<?php
	session_start();
	if(isset($_SESSION['username'])){$name = $_SESSION['username']; }
	require_once('db.php');
	
	$lab_id = $_GET['lab_id'];
	if(isset($_POST['upload_file'])){ //submit upload button
		$ex_no = $_POST['exe_no'];
		$lid = $_POST['lab_id'];
		$file_name = $_FILES['file']['name'];
		
		if ($_FILES["file"]["error"] > 0)
		{	if($_FILES["file"]["error"] == 4)
			echo "<center><font size=2.5 color=red>Choose a File to Upload.</font></center>";
		}
		else
		{													
			$allowedExts = array("jpg", "jpeg", "gif", "png","docx","doc","pdf","pptx","ppt","xls","xlsx","rar","txt");
			$ext_array = explode(".", $_FILES["file"]["name"]);
			$extension = strtolower(end($ext_array));
			if (($_FILES["file"]["size"] <= 5242880)&& (in_array($extension, $allowedExts)))
			{
				$sql = mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lid}' AND ex_no='{$ex_no}'");
				$n = mysql_num_rows($sql);
				if($n == 0){
					
				}else{
					$sql1 = mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lid}' LIMIT 1");
					while($row1 = mysql_fetch_array($sql1)){
						$doc_key = $row1['doc_key'];
						$start_time = $row1['start_time'];
						$end_time = $row1['end_time'];
						$sql2 = mysql_query("INSERT INTO experiment_table(lab_id,ex_no,batch_no)");
					
					}
				}
				
				/*$q = mysql_query("SELECT * from fileupload WHERE rollno ='{$_SESSION['username']}' and filename = '{$_FILES['file']['name']}' and staff_id = '{$_POST['staff_name']}';");
				if(mysql_numrows($q) == 0)
				mysql_query("insert into fileupload values('{$_SESSION['username']}','{$_FILES['file']['name']}','{$_POST['staff_name']}','0');");
				$ind_q = mysql_query("SELECT ind from fileupload WHERE rollno ='{$_SESSION['username']}' and filename = '{$_FILES['file']['name']}' and staff_id = '{$_POST['staff_name']}';");
				$ind_val = mysql_fetch_array($ind_q);
				$target_path = $_POST['staff_name'].$ind_val[0].".".$extension;
				if(move_uploaded_file($_FILES['file']['tmp_name'], "files\\".$target_path))
				{
					echo "<center><font style='color:Green'>Your File has been Uploaded Successfully...</font>";
				}*/
				
				
			}
			else
			{	if(!($_FILES["file"]["size"] < 5242880))
				echo "<center><font size=2.5 color=red>Max. file size must be <b>5Mb</b></font></center>";
				if(!in_array($extension, $allowedExts))
				echo "<center><font size=2.5 color=red>File Format doesn't support</font></center>";
			}
		}
	}
	else{
		echo "<form method='post' enctype='multipart/form-data'>
		<input type='hidden' name='lab_id' value='{$lab_id}' />
		<table>
		<tr><th>Ex No :</th><td><select name='exe_no'>";
		for($i=1;$i<=30;$i++){
			echo "<option value='{$i}'>{$i}</option>";
		}
		echo"</select></td></tr>
		<tr><th>File :</th><td>
		<input type='file' name='file' />
		<input type='submit' name='upload_file' value='Upload this file'>
		</td></tr>
		</table></form></h4>";
	}
?>
<script>
function show_lab_files(lab_id){
	var xmlhttp;
	if (window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
	else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("exercise_content").innerHTML=xmlhttp.responseText;}}
	xmlhttp.open("GET","add_manipulate.php?add=4&lab_id="+lab_id,true);
	xmlhttp.send();
}
</script>