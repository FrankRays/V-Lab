<?php
session_start();
if(isset($_SESSION['username'])){	$name = $_SESSION['username'];}
	require_once('db.php');
?>
<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
<h3>Add a Faculty to a Laboratory</h3>
<div id="lab_content">
<?php
	$q = mysql_query(" SELECT labs FROM acc_details WHERE id='{$name}'");
	while($r = mysql_fetch_array($q)){ $labs = $r['labs'];}
	if($labs!="*"){
		echo "<table cellspacing='20px'><tr><th>Lab Name</th><td><select id='lab_id'>";
			$sql=mysql_query("SELECT labs FROM acc_details WHERE id='{$name}'");
			while($row=mysql_fetch_array($sql)){
				$lab_id_array = explode('*',$row['labs']);
				foreach($lab_id_array as $ra){
					if($ra!=""){
						$q = mysql_query("SELECT * FROM lab_table WHERE lab_id='{$ra}'");
						while($dis = mysql_fetch_array($q)){
							$lab_name = $dis['lab_name'];
							$dept = $dis['dept'];
							$sem = $dis['sem'];
							echo "<option value='{$ra}'>{$lab_name} Dept: {$dept} Sem: {$sem}</option>";
						}
					}		
				}
			}
		echo "</select></td></tr><tr><th>Faculty Name</th><td><select id='faculty_id'>";
				$sql=mysql_query("SELECT * FROM acc_details WHERE id!='{$name}' ORDER by name");
				while($row = mysql_fetch_array($sql)){
					$id=$row['id'];
					$fac_name=$row['name'];
					echo "<option value ='{$id}'>{$fac_name} ( {$id} ) </option>";
				}
		echo "</select></td></tr></table><div id='but'><button type='submit' onclick='submit_add_faculty()'>Add this faculty</button></div> ";
	}
	else{
		echo "<br/><br/><span class='alert'>Currently you have no access to any labs.</span>";
	}
?>
</div>