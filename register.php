<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_form_post('registerSubmit') === 0) {
	// create connection
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_USER_ACCOUNTS);

	// check connection
	if ($conn->connect_errno) {
		die('couldnt connect to db');
	}

	// prepare sql statement
	$stmt = $conn->prepare('INSERT INTO ' . TABLE_USER_ACCOUNTS  . '(email, username, password, role_type) VALUES (?, ?, ?, ?)');
	$stmt->bind_param('ssss', $registerEmail, $registerUsername, $registerPassword, $registerRoleType);

	// set params and  execute sql statement
	$registerEmail = $_POST['registerEmail'];
	$registerUsername = $_POST['registerUsername'];
	$registerPassword = $_POST['registerPassword'];
	$registerRoleType = $_POST['registerRoleType'];
	$stmt->execute();

	// check execution of sql statement

	// close connection
	$stmt->close();
	$conn->close();

	// check database for new record just added

	// redirect
	header_redirect('index.php');
}

if (check_login_state() === 0) {
	header_redirect('index.php');
}

create_view("register");
?>
