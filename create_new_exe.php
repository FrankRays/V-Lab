<?php
	session_start();
	if(isset($_SESSION['username'])){$name = $_SESSION['username']; }
	require_once('db.php');
	
	$lab_id = $_GET['lab_id'];
	
	$sql = mysql_query("SELECT DISTINCT ex_no FROM experiment_table WHERE lab_id='{$lab_id}' ");
	$c = mysql_num_rows($sql);
	$exe_no = $c+1;
	
	echo "<form method='post' enctype='multipart/form-data'>
	<input type='hidden' name='exe_no' value='{$exe_no}' />
	<input type='hidden' name='lab_id' value='{$lab_id}' />
	<table>
	<tr><th style='text-align:left'>Ex No :</th><td>{$exe_no}</td></tr>
	<tr><th style='text-align:left'>Batch No :</th><td><select name='batch_no'>";
	$s = mysql_query("SELECT batch from lab_table WHERE lab_id='{$lab_id}'");
	while($r = mysql_fetch_array($s)){ $cc=$r['batch'];}
	for($i=1;$i<=$cc;$i++){
		echo "<option value='{$i}'>{$i}</option>";
	}
	echo "</select><b>   &nbsp;&nbsp;&nbsp;&nbsp;Exercise Name :</b><input type='text' size='30' name='ex_name' maxlength='100' /></td></tr>
	<tr><th style='text-align:left'>Access Key :</th><td><input type='text' id='access_key_field' name='doc_key' maxlength='30' />
	<input type='button' onclick='generate_key()' value='Generate' /></td></tr>
	
	<tr><th>Starting Date :</th><td>
	D<select name='start_day'>"; for($i=1;$i<=31;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	M<select name='start_month'>"; for($i=1;$i<=12;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	Y<select name='start_year'>"; for($i=2013;$i<=2030;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	</td></tr><tr><th>Starting Time :</th><td>
	H<select name='start_hour'>"; for($i=0;$i<=23;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	M<select name='start_min'>"; for($i=0;$i<=59;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	</td></tr>
	
	<tr><th>Ending Date :</th><td>
	D<select name='end_day'>"; for($i=1;$i<=31;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	M<select name='end_month'>"; for($i=1;$i<=12;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	Y<select name='end_year'>"; for($i=2013;$i<=2030;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	</td></tr><tr><th>Ending Time :</th><td>
	H<select name='end_hour'>"; for($i=0;$i<=23;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	M<select name='end_min'>"; for($i=0;$i<=59;$i++){echo "<option value='{$i}'>{$i}</option>"; }echo"</select>
	</td></tr>
	
	
	
	<tr><th style='text-align:left'>File :</th><td>
	<input type='file' name='file' />
	<input type='submit' name='create_new_exe' value='Upload this file'>
	</td></tr>
	</table></form></h4>";
	
	if(isset($_POST['create_new_exe'])){ //submit upload button
		
		$ex_no = $_POST['exe_no'];
		$lid = $_POST['lab_id'];
		$batch_no = $_POST['batch_no'];
		$ex_name = $_POST['ex_name'];
		$doc_key = $_POST['doc_key'];
		$file_name = $_FILES['file']['name'];
		$s_year = $_POST['start_year'];
		$s_month = $_POST['start_month'];
		$s_day = $_POST['start_day'];
		$s_hour = $_POST['start_hour'];
		$s_min = $_POST['start_min'];
		$e_year = $_POST['end_year'];
		$e_month = $_POST['end_month'];
		$e_day = $_POST['end_day'];
		$e_hour = $_POST['end_hour'];
		$e_min = $_POST['end_min'];
		
		$start_time = mktime($s_hour, $s_min, 00, $s_month, $s_day, $s_year);
		$end_time = mktime($e_hour, $e_min, 00, $e_month, $e_day, $e_year);
		 
		if (($_FILES["file"]["error"] > 0)||($ex_name=="")){
			if($_FILES["file"]["error"] == 4) echo "<center><font size=2.5 color=red>Choose a File to Upload.</font></center>";
			if($ex_name == "") echo "<center><font size=2.5 color=red>Exercise should have a name.</font></center>";
		}
		else
		{													
			$allowedExts = array("jpg", "jpeg", "gif", "png","docx","doc","pdf","pptx","ppt","xls","xlsx","rar","txt");
			$ext_array = explode(".", $_FILES["file"]["name"]);
			$extension = strtolower(end($ext_array));
			if (($_FILES["file"]["size"] <= 5242880)&& (in_array($extension, $allowedExts)))
			{
				$sql = mysql_query("INSERT INTO experiment_table(lab_id,ex_no,batch,doc_key,doc_name) VALUES('{$lid}','{$ex_no}','{$batch_no}','{$doc_key}','{$file_name}')");
				if($sql){
					$sql1 = mysql_query("INSERT INTO doc_key_details(doc_key,start_time,end_time,ex_name) VALUES('{$doc_key}','{$start_time}','{$end_time}','{$ex_name}')");
					if($sql1){
						$target = $lab_id."_".$ex_no."_".$batch_no;
						if(!is_dir("lab_files/{$target}")){	mkdir("lab_files/{$target}"); }
						if(!is_dir("reports/{$target}")){	mkdir("reports/{$target}"); }
						if(move_uploaded_file($_FILES['file']['tmp_name'],"lab_files/{$target}/{$file_name}"))
						{	echo "<center><font style='color:Green'>Your File has been Uploaded Successfully...</font>"; }
					}
					$sql2 = mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lab_id}' AND ex_no='{$ex_no}'");
					$aa = mysql_num_rows($sql2);
					if($aa==1) mysql_query("ALTER TABLE mt_{$lab_id} ADD exe_{$ex_no} varchar(3)");
				}
			}
			else
			{	if(!($_FILES["file"]["size"] < 5242880))
				echo "<center><font size=2.5 color=red>Max. file size must be <b>5Mb</b></font></center>";
				if(!in_array($extension, $allowedExts))
				echo "<center><font size=2.5 color=red>File Format doesn't support</font></center>";
			}
		}
	}
?>
<script>
function generate_key(){
	var random = Math.floor((Math.random()*1000000000000)+1);
	document.getElementById("access_key_field").value = random;
	return true;
}
</script>