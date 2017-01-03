<?php
session_start();
	if(isset($_SESSION['username'])){	$name = $_SESSION['username'];}
	require_once('db.php');
?>
<div id="lab_content">
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	<style>
	.exercise1{width:250px; }
	.exercise2{width:250px; margin-left:280px; margin-top:-23px;}
	</style>
	<h3>Click on the Lab to view the Exercise files</h3><br/><br/>
	<div id="exercise_wrapper">
	<?php
		$sql = mysql_query("SELECT labs FROM acc_details WHERE id='{$name}'");
		while($disp = mysql_fetch_array($sql)){$r_ret=$disp['labs'];}
		if($r_ret=="*"){
			echo "<span style='color:red'>You have no access to currently available laboratories</span>";
		}
		else{
			$exe = "exercise1";
			$r_arr = explode('*',$r_ret);
			foreach($r_arr as $lab){
				if($lab!=''){
					$q = mysql_query("SELECT lab_name FROM lab_table WHERE lab_id='{$lab}'");
					while($row=mysql_fetch_array($q)){
						echo "<div id='but' class='{$exe}'><button type='submit' value='{$lab}' onclick='show_lab_files(this.value)'>{$row['lab_name']}</button></div>";
						if($exe=="exercise1"){$exe="exercise2";}else{$exe="exercise1";}
					}
				}
			}
		}
	?>
	</div>
	<div class="box" style="background-color:white" id="exercise_content">
	<h4 style="color:black">Click a lab to view the files under the lab</h4>
	</div>
</div>