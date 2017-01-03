<?php 
//Initialize session
		session_start();
		
		include('db.php');
date_default_timezone_set('Asia/Kolkata');
$date = date('m/d/Y H:i:s', time());
echo "The current time is: " . $date;

		$err1 = 0;$user=0;
		if(isset($_POST['logout']))
			{
				unset($_SESSION['username']);
				session_destroy();
			}
			if(isset($_POST['sub_mit']))
			{
				$username = $_POST['uname'];
				$passw = $_POST['pass'];
				$type = $_POST['th'];
				if($username == "")
				$user=1;
				
				if($user==0)
				{
				$m = mysql_query("SELECT * from logindetails WHERE rollno = '".$username."';");
				$n = mysql_numrows($m);
				$p=md5($passw);
				if($n == 0)
				{
					mysql_query("INSERT into logindetails(rollno,password,priv,profileimage) VALUES('{$username}','{$p}','{$type}','')");
					$err1 =2;
					
					$res = mysql_query("INSERT into acc_details(id,name,address,email,phno,priv,labs) VALUES('".$username."','','','','','".$type."','*');");
				}
				else
				if($n != 0)
				$err1=1;
				}
				
			}
		if(!isset($_SESSION['username'])) 
		{
		header('Location: index.php');
		}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Admin Portal</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="stylesl.css" />
</head>
<body>

<div id="container">
  <div id="header">
    <h1>Add New Account: Teachers/Tutors</h1>
  </div>
  <div id="wrapper">
    <div id="content">
		<fieldset>
		<b>User Details:</b>
		<form method="post" >
		<table>
		<tr><td>Username:</td><td><input type="text" name="uname" /></td></tr>
		<tr><td>Password:</td><td><input type="text" name="pass" /></td></tr>
		<tr><td>Type:</td><td><select name="th">
							<option value="2">Teacher</option>
						<!--	<option value="4">HOD</option> -->
							<option value="5">Tutor</option>
							</select></td></tr>
		</table><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div id='but' style="padding:0px 0px 0px 75px;"><button style="width:100px;" type="submit" name="sub_mit" value="sub_mit">Submit</button></div>
		</form>			
		<?php if($err1!=0){
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if($err1 == 2)
			echo "&nbsp;&nbsp;<font color=green>Record Added Successfully!</font><br>";	
			if($err1 == 1)
			echo "<font color=red>Account already exists!!</font><br>";	
			if($user==1)
			echo "<font color=red>Username field is empty!!</font><br>";	
		}
		?>
		</fieldset>
    </div>
  </div>
  <div id="navigation">
    <p><strong>Navigation Here</strong></p>
    <ul>
	<form method="post">
      <li><div id='but' style="padding:0px 0px 0px 25px;"><button style="width:150px;" type="submit" name="logout" value="logout">Logout</button></div></a></li>
	  </form>
    </ul>
  </div>
  <div id="footer">
    <p>Designed by  Dijil.V.V, Raghul.T, Sriram Koushik, Gokul.S.P</p>
  </div>
</div>
</body>
</html>
