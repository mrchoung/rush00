<?php

function 	get_user_by_id($connect, $id)
{
	if (!($users = mysqli_query($connect, "SELECT * FROM users WHERE id = '".$id."'")))
	{
		echo "ERROR\n";
		die();
	}
	return ($users);
}

function 	get_users($connect)
{
	if (!($users = mysqli_query($connect, "SELECT * FROM users")))
	{
		echo "ERROR\n";
		die();
	}
	return ($users);
}

function 	del_user_by_id($connect, $id)
{
	if (!($query = mysqli_query($connect, "DELETE FROM users WHERE id = '".$id."'"))) {
		echo "FAIL DELETE USER";
		die();
	}
}

function 	new_user($connect, $login, $password, $confirm_password, $email, $user_groupe)
{
	if ($password != $confirm_password)
	{
		echo "Le champs mot de passe et confirmation de mot de passe ne sont pas identique\n";
		echo "<a href='list_new.php?type=users'>Retour au formulaire</a>";
		die();
	}
	$req = $connect->prepare('SELECT * FROM users WHERE login = ? OR email = ?');
	$req->bind_param('ss', $login, $email);
	$req->execute();
	$user = $req->get_result()->fetch_assoc();
	if ($user)
	{
		echo "Ce login ou cet email éxistent déjà ! \n";
		echo "<a href='list_new.php?type=users'>Retour au formulaire</a>";
		die();
	}
	$password = hash("sha512", $password);
	if (!($query = mysqli_query($connect, "INSERT INTO users (login,
															password, 
															email, 
															user_groupe) VALUES('".$login."',
																			'".$password."',
																			'".$email."',
																			'".$user_groupe."')")))
	{
		echo "fail";
		echo "ERROR\n";
		die();
	}
}

function 	modif_user($id, $connect, $login, $password, $confirm_password, $email, $user_groupe)
{
	if ($password != $confirm_password)
	{
		echo "Le champs mot de passe et confirmation de mot de passe ne sont pas identique\n";
		die();
	}
	$req = $connect->prepare('SELECT * FROM users WHERE login = ? OR email = ?');
	$req->bind_param('ss', $login, $email);
	$req->execute();
	$user = $req->get_result();
	if ($user->num_rows > 1)
	{
		echo "Ce login et cet email existent déjà ! \n";
		echo "<a href='list_new.php?type=users'>Retour au formulaire</a>";
		die();
	}
	$user = $user->fetch_assoc();
	if ($user && $user["id"] != $id)
	{
		echo "Ce login ou cet email éxistent déjà ! \n";
		echo "<a href='list_new.php?type=users'>Retour au formulaire</a>";
		die();
	}
	$password = hash("sha512", $password);
	if (!($query = mysqli_query($connect, "UPDATE users SET login = '".$login."',
															password = '".$password."', 
															email = '".$email."', 
															user_groupe = '".$user_groupe."'
															WHERE id = '".$id."'")))
	{
		echo "fail";
		echo "ERROR\n";
		die();
	}
}

?>