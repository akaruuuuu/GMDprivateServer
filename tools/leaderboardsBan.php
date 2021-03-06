<?php
include "../incl/lib/connection.php";
require "../incl/lib/generatePass.php";
require_once "../incl/lib/exploitPatch.php";
$ep = new exploitPatch();
if(isset($_POST["userName"]) AND isset($_POST["password"]) AND isset($_POST["userID"])){
	$userName = $ep->remove($_POST["userName"]);
	$password = $ep->remove($_POST["password"]);
	$userID = $ep->remove($_POST["userID"]);
	$generatePass = new generatePass();
	$pass = $generatePass->isValidUsrname($userName, $password);
	if ($pass == 1) {
		$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName AND isAdmin = 1");	
		$query->execute([':userName' => $userName]);
		if($query->rowCount()==0){
			echo "Account doesn't have moderator access to the server. <a href='leaderboardsBan.php'>Try again</a>";
		}else{
			if(!is_numeric($userID)){
				exit("Invalid userID");
			}
			$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE userID = :id");
			$query->execute([':id' => $userID]);
			if($query->rowCount() != 0){
				echo "Banned succesfully.";
			}else{
				echo "Ban failed.";
			}
		}
	}else{
		echo "Invalid password or nonexistant account. <a href='leaderboardsBan.php'>Try again</a>";
	}
}else{
	echo '<form action="leaderboardsBan.php" method="post">Your Username: <input type="text" name="userName">
		<br>Your Password: <input type="password" name="password">
		<br>Target UserID: <input type="text" name="userID">
		<br><input type="submit" value="Ban"></form>';
}
?>