<?php
	session_start();
	require_once('db.php');
?>
<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
<style>
input{height:25px; width:250px; font-style:italic;}
</style>
<h3>Add a Laboratory</h3>
<br/><br/>
<div id="lab_content">
<table cellspacing="10">
<tr>
	<th  width="100">Lab Name :</th>
	<td><input type="text" id="lab_name" name="lab_name" placeholder="lab name" /></td>
</tr>
<tr>
	<th>Semester :</th>
	<td><select name="semester_no" id="semester_no">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select></td>
</tr>
<tr>
	<th>Number of batches :</th>
	<td><select name='batch_no' id='batch_no'>
	<?php for($i=1;$i<20;$i++) echo "<option value='{$i}'>{$i}</option>"; ?>
	</select></td>
</tr>
<tr>
	<th>Department :</th>
	<td><select name="dept" id="dept">
	<option value="Automobile">Automobile</option>
	<option value="Bio Medical">Bio Medical </option>
	<option value="Bio Technology">Bio Technology</option>
	<option value="Computer Science G1">Computer Science G1</option>
	<option value="Computer Science G2">Computer Science G2</option>
	<option value="ECE G1">ECE G1</option>
	<option value="ECE G2">ECE G2</option>
	<option value="EEE G1">EEE G1</option>
	<option value="EEE G2">EEE G2</option>
	<option value="EEE Sandwich">EEE Sandwich</option>
	<option value="Information Technology G1">Information Technology G1</option>
	<option value="Information Technology G1">Information Technology G2</option>
	<option value="Civil">Civil</option>
	<option value="Mechanical G1">Mechanical G1</option>
	<option value="Mechanical G2">Mechanical G2</option>
	<option value="Mechanical Sandwich">Mechanical Sandwich</option>
	<option value="Robotics">Robotics</option>
	</select></td>
</tr>
</table>
<div id="but">
<button type="button" onclick="submit_add_laboratory()">Add Laboratory</button>
</div>
</div>