<?php include('db.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Login Portal | Virtual Laboratory</title>
		<link href="login-box.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFF">
<form name="logincheck" method="POST">
<style>
.title{font-size:70px;margin-left:-200px;}
</style>
<div style="padding: 0 0 0 400px;">
	<h1 class="title">VIRTUAL LABORATORY</h1>
<div id="login-box">
<H2><font style="Times New Roman"> Login </font></H2>
<br />
<br />

<div id="login-box-name" style="margin-top:20px;">Username:</div>
<div id="login-box-field" style="margin-top:20px;">
<input name="name" type="text" class="form-login" title="rollno" value="" size="30" maxlength="2048" />
</div>

<div id="login-box-name">Password:</div>
<div id="login-box-field">
<input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" />
</div>

<br />
<br />
<br />
<br />
<br />

<br />
<br />

<br />
<div id="but" style="padding: 20px 110px;">

<button name="login" type="submit" >Login</button>
<!--input name="view" type="submit" value="view"-->
</div>
</form>
</div>
</div>
</body>
</html>
<?php
//login check
if(isset($_POST['login']))
{				session_start();
				$_SESSION['username']=$_POST['name'];
				$_SESSION['password']=$_POST['password'];
				$result=mysql_query("select * from logindetails where rollno = '".$_POST['name']."' and password = md5('".$_POST['password']."');");
				$no = mysql_numrows($result);
				$res = mysql_fetch_array($result);
				if($no == 1 && ($res['priv']==1 || $res['priv']==3))
				{
					header("Location: studentpage.php");
				}
				else
				if($no == 1 && ($res['priv']==2 || $res['priv']==5))
				{
					header("Location: faculty.php");
				}
				else
				if($no == 1 && ($res['priv'] == 10))
				{
					header("Location: layout.php");
				}
				else
					echo "<script type='text/javascript'> alert('Enter correct Username and Password!')</script>";
}
?>