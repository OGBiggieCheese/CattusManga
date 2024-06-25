<?php

function emptyInput($email, $username, $pwd, $pwd2){
	isset($result);
		if(empty($email) || empty($username) || empty($pwd) || empty($pwd2)){
		$result = true;
	}
	else{
		$result = false;
	}
	return $result;
}

function invalidUid($username){
	isset($result);
	if(!preg_match("/^[a-zA-Z][0-9a-zA-Z_]{3,12}[0-9a-zA-Z]*$/", $username)){
		$result = true;
	}
	else{
		$result = false;
	}
	return $result;

}

function invalidEmail($email){
	isset($result);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$result = true;
	}
	else{
		$result = false;
	}
	return $result;


}
function pwdMatch($pwd,$pwd2){
	isset($result);
	if($pwd !== $pwd2){
		$result = true;
	}
	else{
		$result = false;
	}
	return $result;

}
function uidExist($conn,$username,$email){
	$sql = "SELECT * FROM users LEFT JOIN profiles
        ON users.ID = profiles.user_id WHERE Name = ? OR Email = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: register.php?error=stmterror");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss",$username,$email);
 	mysqli_stmt_execute($stmt);

 	$resultData = mysqli_stmt_get_result($stmt);

 	if ($row = mysqli_fetch_assoc($resultData)){
 		return $row;
 	}
 	else {
 		$result = false;
 		return $result;
 	}
 	mysqli_stmt_close($stmt);
}
function createUser($conn, $email, $username, $pwd, $role_user = 2){
	$sql = "INSERT INTO users (`Name`, `Email`, `Password`, `profile_pic`, `activation_date`, `desactivation_date`, `suscriptions_ID`, `email_validated`, `points`) VALUES(?, ?, ?, '../Img/avatar.png', NOW(), NULL, 0, 0,100);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: register.php?error=stmterror");
		exit();
	}
	$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "sss",$username, $email, $hashedpwd);
 	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$puser = mysqli_escape_string($conn, $username);

	$getUser = "SELECT ID FROM users WHERE Name = '" . $puser . "';";
	$result = mysqli_query($conn, $getUser);
$datau = array();
if (mysqli_num_rows($result) > 0) {
  while ($data = mysqli_fetch_assoc($result)) {
    $datau[] = $data;
  }
}
echo $datau[0]['ID'];
	
		$createProfile = "INSERT INTO userprofile (User_ID, show_badges, favourite_manga_ID, show_favourite, show_likes, description, show_interests, show_role, bg_dir) VALUES(". $datau[0]['ID'] . ", 1, NULL, 1, 1, '', 1, 0, NULL); INSERT INTO profiles (role_id, user_id) VALUES(". $role_user .",". $datau[0]['ID'].");";
		$send = mysqli_multi_query($conn, $createProfile);
	
	setcookie("usernamec", $puser, time()+500);
 	header("location: login.php?error=none");
	exit();

}

function emptyLogInput($username, $pwd){
	isset($result);
		if(empty($username) || empty($pwd)){
		$result = true;
	}
	else{
		$result = false;
	}
	return $result;
}

 function logInUser($conn, $username, $pwd, $remember){
 	$uidExist = uidExist($conn,$username,$username);
 	if ($uidExist === false){
 		header("location: login.php?error=wronglogin");
		exit();
 	}
	$hashedpwd = $uidExist["Password"];
	$checkpwd = password_verify($pwd, $hashedpwd);

	if ($checkpwd === false){
		header("location: login.php?error=wronglogin");
		exit();

	}
	else if ($checkpwd === true && $remember == true){
		session_start();
		setcookie("remember", $remember);
		setcookie("pass", $pwd);
		setcookie("user", $username);
        $_SESSION['datos'] = $uidExist;
        header("location: homepage.php");
	}
	else if ($checkpwd === true && $remember == NULL){
		session_start();
		unset($_COOKIE['pass']);
		unset($_COOKIE['remember']);
		unset($_COOKIE['user']);
		setcookie("remember", null);
		setcookie("pass", null);
		setcookie("user", null);
        $_SESSION['datos'] = $uidExist;
        header("location: homepage.php");
	}
 }
