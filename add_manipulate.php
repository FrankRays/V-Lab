<?php
		session_start();
		if(isset($_SESSION['username'])){	$name = $_SESSION['username']; }
		require_once('db.php');
		
	if($_GET['add']==1){ //add Laboratory
		$lab_name = $_GET['lab_name'];
		$sem_no = $_GET['sem_no'];
		$batch_no = $_GET['batch_no'];
		$dept = $_GET['dept'];
		
		if($lab_name==NULL){ echo "Lab name cannot be empty"; die;}
		$count=0;
		$que = mysql_query("SELECT * FROM lab_table");
		while($row=mysql_fetch_array($que)){ $count = $count+1; }
		
		$lsuffix = 1000+$count;
		$lab_id = "L".$lsuffix;

		$sql = mysql_query("INSERT INTO lab_table(lab_id,lab_name,dept,sem,batch) VALUES('{$lab_id}','{$lab_name}','{$dept}','{$sem_no}','{$batch_no}');");
		if($sql){
			$sql1=mysql_query("SELECT labs FROM acc_details WHERE id='{$name}'");
			while($row = mysql_fetch_array($sql1)){ 
				$lab1=$row['labs'];
				$lab2=$lab1.$lab_id."*";
				$s = mysql_query("UPDATE acc_details SET labs='{$lab2}' WHERE id='{$name}'");
				$s1 = mysql_query("CREATE TABLE mt_{$lab_id}(roll_no varchar(10))");
				echo "<span style='color:green'>Creation Success...!!!</span>";
			}
		}else{
			echo "No lab record created";
		}
	}
	else if($_GET['add']==2){ //add Faculty
		$fac_id = $_GET['fac_id'];
		$lab_id = $_GET['lab_id'];
	//	echo "Fac :".$fac_id."  Lab id :".$lab_id;
		$q = mysql_query("SELECT labs from acc_details WHERE id='{$fac_id}' and labs LIKE'%{$lab_id}%'");
		$n = mysql_num_rows($q);
		
		if($n==0){
			$sql = mysql_query("SELECT labs FROM acc_details WHERE id='{$fac_id}'");
			while($row = mysql_fetch_array($sql)){ 
					$lab1=$row['labs'];
					$lab2=$lab1.$lab_id."*";
					$s = mysql_query("UPDATE acc_details SET labs='{$lab2}' WHERE id='{$fac_id}'");
				echo "<br/><br/><span style='color:green'>Creation Success...!!!</span>";
			}
		}
		else{
			echo "<br/><br/><br/><span style='color:red'>This faculty have been already added to this lab.</span>";
		}
	}
	else if($_GET['add']==3){ //add students
				$lab_id = $_GET['lab_id'];
		$sql = mysql_query("SELECT * FROM lab_table WHERE lab_id='{$lab_id}'");
		while($r = mysql_fetch_array($sql)){
			$lab_name = $r['lab_name'];
			$lab_dept = $r['dept'];
			$lab_sem = $r['sem'];
			echo "<u><h4 style='color:black'>{$lab_name}</h4></u>";
			echo "<br/><br/>
			<table>
				<tr>
				<th style='text-align:left'>Department </th><th>:</th>
				<td>{$lab_dept}</td>
				</tr>
				<tr>
				<th style='text-align:left'>Semester </th><th>:</th>
				<td>{$lab_sem}</td>
				</tr>
				<tr>
				<th style='text-align:left'>Faculties Incharge </th><th>:</th>
				<td><br/>";
				$s = mysql_query("SELECT id,name FROM acc_details WHERE labs LIKE '%{$lab_id}%'");
				while($a = mysql_fetch_array($s)){
					echo $a['name']." ( ".$a['id']." )<br/>"; 
				}
				echo"</td></tr></table>";
		}
		
		//display the students name under each lab into batches.
		$qr = mysql_query("SELECT batch from lab_table WHERE lab_id='{$lab_id}'");
		while($rr = mysql_fetch_array($qr)){ $dd = $rr['batch'];}
		
		$sql1 = mysql_query("SELECT id FROM stud_details WHERE labs LIKE '%{$lab_id}%'");
		$c = mysql_num_rows($sql1);
		if($c==0){
			echo "<span class='alert'>There are no students in this lab.</span>";
		}
		else{
			//Display the students names
			echo "<br/><br/><b>The students that can access this lab are,<br/><br/></b>";
			for($i=1;$i<=$dd;$i++){
				echo "<u><b><br/><br/>Batch {$i}:</b></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$qr1 = mysql_query("SELECT id FROM stud_details WHERE labs LIKE '%{$lab_id}-{$i}%'");
				while($rr1 = mysql_fetch_array($qr1)){
					echo $rr1['id']." ";
				}
			}
		}
		
		$sql = mysql_query("SELECT batch FROM lab_table WHERE lab_id='{$lab_id}'");
		while($row=mysql_fetch_array($sql)){ $count = $row['batch']; }
		
		echo "<br/><br/><b>Batch :</b><select id='group_add_students_batch'>";
		for($i=1;$i<=$count;$i++)
			echo "<option value='{$i}'>{$i}</option>";
		echo "</select>
		<b>Add new Students to this lab :</b>
		<div id='but'>
		<textarea id='group_add_students' rows='4' cols='40' placeholder='Enter the rollnos separated with comma E.g, 10i310,10i338,10i349,11i464...'></textarea>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' value='{$lab_id}' onclick='add_students_lab(this.value)' name='add_student_submit' style='height:60px;margin-top:0px;position:absolute;'>Add these students to this lab</button>
		</div>";
	}
	else if($_GET['add']==5){ //add group students submit
		$lab_id = $_GET['lab_id'];
		$res = $_POST['group_students'];
		$batch_no = $_POST['batch_no'];
		if($res!=""){
			$res_array = explode(',',$res);
			$success = "";
			$warning = "";
			$error = "";
			foreach($res_array as $new_roll){
				if($new_roll!=""){
					if(!preg_match("/\W/",$new_roll)==false)
					{	echo "<span class='alert'>The roll no \"{$new_roll}\" should contain only alphanumeric characters.</span><br/><br/>";  
					}
					else{
						$sql = mysql_query("SELECT * FROM logindetails WHERE rollno='{$new_roll}'");
						$c = mysql_num_rows($sql);
						if($c==0){
							$error = $new_roll.", ".$error;
						}
						else if($c==1){
							$sql1 = mysql_query("SELECT labs FROM stud_details WHERE id='{$new_roll}'");
							while($row1=mysql_fetch_array($sql1)){
								$labs = $row1['labs'];
								$sql2 = mysql_query("SELECT * FROM stud_details WHERE id='{$new_roll}' AND labs LIKE '%{$lab_id}%'");
								$d = mysql_num_rows($sql2);
								if($d==0){
									$new_labs = $labs.$lab_id."-".$batch_no."*";
									mysql_query("UPDATE stud_details SET labs='{$new_labs}' WHERE id='{$new_roll}'");
									$success = $new_roll.", ".$success;
								}
								else{
									$warning = $new_roll.", ".$warning;
								}
								
							}
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
				echo "<span class='success'>The Roll no's ".$success." have been added to this lab.</span><br/><br/>";
			}
			if($warning!=""){
				echo "<span class='warning'>The student with the rollno's ".$warning." have already been added to this lab.</span><br/><br/>";
			}
			if($error!=""){
				echo "<span class='alert'>The Login's for the Roll no's ".$error." have not been created. It cannot be added to the lab right now.</span><br/><br/>";
			}
			
			
		}
		else{
			echo "<span class='warning'>There are no roll nos specified in the list.</span>";
		}
	}
	else if($_GET['add']==4){ //add exercise files
		$lab_id = $_GET['lab_id'];
		$sql = mysql_query("SELECT * FROM lab_table WHERE lab_id='{$lab_id}'");
		while($r = mysql_fetch_array($sql)){
			$lab_name = $r['lab_name'];
			$lab_dept = $r['dept'];
			$lab_sem = $r['sem'];
			echo "<u><h4 style='color:black'>{$lab_name}</h4></u>";
			echo "<br/><br/>
			<table>
				<tr>
				<th style='text-align:left'>Department </th><th>:</th>
				<td>{$lab_dept}</td>
				</tr>
				<tr>
				<th style='text-align:left'>Semester </th><th>:</th>
				<td>{$lab_sem}</td>
				</tr>
				<tr>
				<th style='text-align:left'>Faculties Incharge </th><th>:</th>
				<td><br/>";
				$s = mysql_query("SELECT id,name FROM acc_details WHERE labs LIKE '%{$lab_id}%'");
				while($a = mysql_fetch_array($s)){
					echo $a['name']." ( ".$a['id']." )<br/>"; 
				}
				echo"</td></tr></table>";
		}
		
		//display the files under each lab
		$sql1 = mysql_query("SELECT DISTINCT ex_no FROM experiment_table WHERE lab_id='{$lab_id}'");
		$c = mysql_num_rows($sql1);
		if($c==0){
			echo "<span class='alert'>There are no exercises created.</span>";
		}
		else{
			echo "<br/><u><h4 style='color:black'>View the files in the {$lab_name} Folder</h4></u><br/>";
			$sql2 = mysql_query("SELECT DISTINCT ex_no,batch FROM experiment_table WHERE lab_id='{$lab_id}' ORDER BY ex_no,batch");
				while($row2=mysql_fetch_array($sql2)){
					echo "<div class='box'>";
					$ex_no = $row2['ex_no'];
					$batch = $row2['batch'];
					$sql3 = mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lab_id}' AND ex_no='{$ex_no}' AND batch='{$batch}' LIMIT 1");
					while($row3=mysql_fetch_array($sql3)){
						$doc_key = $row3['doc_key'];
						$doc_name = $row3['doc_name'];
						$sql4 = mysql_query("SELECT * FROM doc_key_details WHERE doc_key='{$doc_key}'");
						while($row4=mysql_fetch_array($sql4)){
							$ex_name = $row4['ex_name'];
							$start_time = $row4['start_time'];
							$end_time = $row4['end_time'];
						}
						//$stime = date('H:i:s=>M/d/Y',$start_time);
						$stime = date('d/m/Y  H:i',$start_time);
						$etime = date('d/m/Y  H:i',$end_time);
						//$etime = date('H:i:s=>M/d/Y',$end_time);
						echo "Ex No:{$ex_no} Batch:{$batch} <br/>Ex Name:{$ex_name}<br/>";
						echo "Starting time:{$stime}<br/> Ending Time:{$etime}<br/>";
						echo "Access Key:{$doc_key}<br/><br/>
						<iframe style='float:right;margin-top:-100px;' src='upload_new_exe.php?lab_id={$lab_id}&key={$doc_key}&exe={$ex_no}&batch={$batch}' width='300px' height='150px' frameborder='0' ></iframe>";
						
						$sql5 = mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lab_id}' AND ex_no='{$ex_no}' AND batch='{$batch}'");
						while($row5=mysql_fetch_array($sql5)){
							$doc_name = $row5['doc_name'];
							echo "<u><a style='color:orange' target='_blank' href='lab_files/{$lab_id}_{$ex_no}_{$batch}/{$doc_name}'>{$doc_name}</a></u><br/><br/>";
						}
					}
				echo "</div>";
			}
		}
		//create new exercise
		echo "<div class='box'>
		<br/><u><h4 style='color:black'>Create a new exercise</h4></u>
		<iframe src='create_new_exe.php?lab_id={$lab_id}' width='100%' height='300px' frameborder='0' ></iframe>";
		echo "</div>";
	}
	else if($_GET['add']==6){//View students lab files
		$lab_batch_name = $_GET['lab_batch'];
		$lbn_arr = explode('**',$lab_batch_name);
		$lab_batch = $lbn_arr[0];
		$lab_name = $lbn_arr[1];
		$lb_arr = explode('-',$lab_batch);
		$lab = $lb_arr[0];
		$batch = $lb_arr[1];
		
		echo "<iframe src='student_exe_upload.php?lab_id={$lab}&lab_name={$lab_name}' width='100%' height='200px' frameborder='1' ></iframe>";
		
		$sql=mysql_query("SELECT DISTINCT ex_no,doc_key FROM experiment_table WHERE lab_id='{$lab}' AND batch='{$batch}' ORDER by ex_no ASC");
		while($row=mysql_fetch_array($sql)){
			$ex_no=$row['ex_no'];
			$doc_key=$row['doc_key'];
			$s=mysql_query("SELECT * FROM doc_key_details WHERE doc_key='{$doc_key}'");
			while($r=mysql_fetch_array($s)){
				$ex_name = $r['ex_name'];
				$s_time = $r['start_time'];
				$e_time = $r['end_time'];
			}
			$p_time = time() + 19800;
			$start_time = date('d/m/Y H:i',$s_time);
			$end_time = date('d/m/Y H:i',$e_time);
			$pass_value=$doc_key."-".$s_time."-".$e_time."-".$p_time."-".$lab."-".$batch."-".$ex_no."-".$ex_name;
			echo "<div id='get_doc_key_div' onclick='get_key(\"{$pass_value}\")' class='box'>
			Ex.No : {$ex_no}   Ex.Name : {$ex_name} <br/>
			Start Time : {$start_time} <br/>
			End Time : {$end_time} <br/>
			<p style='float:right;font-size:18px;margin-top:-30px;color:#f5f5f5;'>Click to access the files.</p>";
			echo"</div>";
			echo "<div id='exe_container_{$lab}_{$batch}' style='visibility:hidden;position:absolute;'>";
			$sql1=mysql_query("SELECT * FROM experiment_table WHERE lab_id='{$lab}' AND batch='{$batch}' AND ex_no='{$ex_no}'");
			while($row1=mysql_fetch_array($sql1)){
					$doc_name=$row1['doc_name'];
					echo "<u><a style='color:purple' target='_blank' href='lab_files/{$lab}_{$ex_no}_{$batch}/{$doc_name}'>{$doc_name}</a></u><br/><br/>";
			}
			echo "</div>";
		}
	}
?>
