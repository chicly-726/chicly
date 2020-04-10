<?php
session_start();
$action=$_REQUEST['action'];
switch($action)
{
	case 'reg':
	registertabl();
	break;
	case 'authentication':
	loginauthentication();
	break;
	case 'chatbox':
	loadUerdetails();
	break;
	case 'messageinfo':
	UpdateInfo();
	break;
	case "logout":
	Logout();
	break;	
	case "getMsg":
	getMsg();
	break;
	case "edit":
	editOpt();
	break;
	case "Insertregtbl":
	insertRegInfo();
	break;
}
function editOpt()
{
	//echo "test";
	$getinfo=explode("|",$_REQUEST['info']);
	include ('dbconfig/dbconfig.php');	
	$conn=OpenCon();
	$uname=$getinfo[2];
	echo $query="delete from reg_tbl where username='$uname'";
	$selquery=mysqli_query($conn,$query);
	mysqli_close($conn);
	//for($i=0;$i<sizeOf($getinfo);$i++)		
	header('Location:/vcnetTemp/admin/index.php');	
}
function insertRegInfo()
{
	$getinfo=explode("|",$_REQUEST['info']);
	$fname=$getinfo[0];
	$lname=$getinfo[1];
	$email=$getinfo[3];
	$uname=$getinfo[2];
	$pwd=$getinfo[4];
	include ('dbconfig/dbconfig.php');
	$conn=OpenCon();
	$query1 = "insert into reg_tbl(f_name, l_name,email, username,pwd) values ('$fname','$lname','$email','$uname','$pwd')";
	mysqli_query($conn, $query1);
	mysqli_close($conn);
	header('Location:/vcnetTemp/admin/index.php');	
}
function Logout()
{
	
	include ('dbconfig/dbconfig.php');	
	$uname=$_SESSION["Username"];	
	$conn=OpenCon();
	$query="update reg_tbl set temp=1 where username='$uname'";
	$selquery=mysqli_query($conn,$query);
	mysqli_close($conn);
	header('Location:default.php');	

}
function registertabl()
{
include ('dbconfig/dbconfig.php');
$fname=$_REQUEST['fname'];
$lname=$_REQUEST['lname'];
$email=$_REQUEST['email'];
$uname=$_REQUEST['uname'];
$pwd=$_REQUEST['rfmpassword'];
$query1 = "insert into reg_tbl(f_name, l_name,email, username,pwd) values ('$fname','$lname','$email','$uname','$pwd')";
$conn=OpenCon();

if (mysqli_query($conn, $query1)) {
    echo "New record created successfully";
	header('Location:default.php');
} else {
    echo "Error: " . $query1 . "<br>" . mysqli_error($conn);
	header('Location:regerationfrm.php');
}

mysqli_close($conn);
}
function loginauthentication()
{
	include ('dbconfig/dbconfig.php');
	$pwd=$_REQUEST['psw'];
	$uname=$_REQUEST['uname'];
	$_SESSION["Username"] =$uname;
	if($uname=="admin"&& $pwd=="admin")
	{
		header('Location:/vcnetTemp/admin/index');
	}
else
	{
	$conn=OpenCon();
	$query="select * from reg_tbl where username='$uname' and pwd='$pwd'";
	$selquery=mysqli_query($conn,$query);
	if (!(mysqli_num_rows($selquery) > 0))
		{
		echo "<script type='text/javascript'>\n";
		   	echo "alert('Your Username and Password Wrong!');\n";
			echo "location.href='default.php'";
		    echo "</script>"; 
		}
	else{
		$query="update reg_tbl set temp=0 where username='$uname' and pwd='$pwd'";
	$selquery=mysqli_query($conn,$query);
		header('Location:vcmsg');
		}
	mysqli_close($conn);
	}
    // while($arr=mysqli_fetch_assoc($selquery))
    // {
	// echo $arr['username']; 
	// }	
	//header('Location:login_authentication');
}
function loadUerdetails()
{
	include ('dbconfig/dbconfig.php');
	$str= "<table>";
	$str.=  "<th>Name</th><th>Status</th><th>Chating</th>";
	$conn=OpenCon();
	$query="select * from reg_tbl where username='".'$_SESSION["Username"]'."'";
	$selquery=mysqli_query($conn,$query);
	while($arr=mysqli_fetch_assoc($selquery))
    {
	$str.=  "<tr><td>";
	$str.=  $arr['username'];
	$str.=  "</td><td>Online</td><td><input type='submit' value='chat'></td></tr>";
	}
$str.=  "</table>";
mysqli_close($conn);
echo $str; 
return $str;
}
function UpdateInfo(){
	date_default_timezone_set("Asia/Calcutta"); 
	echo "Message info";
	echo $timeget=date("h:i");
	$uname=$_SESSION["Username"];	
	include ('dbconfig/dbconfig.php');
$msginfo=$_REQUEST['msgboxinfo'];
$usinof=$_REQUEST['useinfo'];
$query1 = "insert into msginfo(username,msg,time) values ('$uname','$msginfo','$timeget')";
$conn=OpenCon();
if (mysqli_query($conn, $query1)) {
    echo "New record created successfully";
	header('Location:Message.php?userMsg='.$usinof);
} else {
    echo "Error: " . $query1 . "<br>" . mysqli_error($conn);
	//header('Location:Message.php');
}
mysqli_close($conn);
	
}
function getMsg()
{ 
	 echo "Get Message";
	  $str=$_REQUEST['usname'];
	 if($str!="undefined")
		header('Location:Message.php?userMsg='.$str);
	//$_SESSION["Username"];
	
}
?> 