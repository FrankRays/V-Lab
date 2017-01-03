<?php
	session_start();
	if(isset($_SESSION['username'])){$name = $_SESSION['username']; }
	require_once('db.php');
	$lab_id=$_GET['lab_id'];
	$doc_key=$_GET['key'];
	$ex_no=$_GET['exe'];
	$batch=$_GET['batch'];
	echo "<form method='post' enctype='multipart/form-data'>
	<input type='hidden' name='lab_id' value='{$lab_id}' />
	<input type='hidden' name='doc_key' value='{$doc_key}' />
	<input type='hidden' name='ex_no' value='{$ex_no}' />
	<input type='hidden' name='batch' value='{$batch}' />
	<input type='file' name='file' />
	<input type='submit' name='upload_new_exe' value='Upload this file'>
	</form></h4>";
	if(isset($_POST['upload_new_exe'])){ //submit upload button
		$ex_no = $_POST['ex_no'];
		$lid = $_POST['lab_id'];
		$batch_no = $_POST['batch'];
		$doc_key = $_POST['doc_key'];
		$file_name = $_FILES['file']['name'];
		if ($_FILES["file"]["error"] > 0){
			if($_FILES["file"]["error"] == 4) echo "<center><font size=2.5 color=red>Choose a File to Upload.</font></center>";
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
					$target = $lab_id."_".$ex_no."_".$batch_no;
					if(!is_dir("lab_files/{$target}")){	mkdir("lab_files/{$target}"); }
					if(!is_dir("reports/{$target}")){	mkdir("reports/{$target}"); }
					if(move_uploaded_file($_FILES['file']['tmp_name'],"lab_files/{$target}/{$file_name}"))
					{	echo "<center><font style='color:Green'>Your File has been Uploaded Successfully...</font>"; }
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