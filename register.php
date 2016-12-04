<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_form_post('registerSubmit') === 0) {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_USER_ACCOUNTS);

	if ($conn->connect_errno) {
		die('couldnt connect to db');
	}

	$stmt = $conn->prepare('INSERT INTO ' . TABLE_USER_ACCOUNTS  . '(email, username, password, role_type) VALUES (?, ?, ?, ?)');
	$stmt->bind_param('ssss', $registerEmail, $registerUsername, $registerPassword, $registerRoleType);

	$registerEmail = $_POST['registerEmail'];
	$registerUsername = $_POST['registerUsername'];
	$registerPassword = $_POST['registerPassword'];
	$registerRoleType = $_POST['registerRoleType'];
	$stmt->execute();

	$stmt->close();
	$conn->close();

	header_redirect('index');
}

if (check_login_state() === 0) {
	header_redirect('index');
}

create_view('register');
?>
