<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login.php');
}

$role = check_role_type();

switch ($role) {
	case 'busboy':
		header_redirect('busboy.php');
		break;
	case null:
		echo 'Error loading role from user session';
		break;
	default:
		create_view('index');
		exit();
}
?>
