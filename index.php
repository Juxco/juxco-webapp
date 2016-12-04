<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

$roleType = get_role_type();

switch ($roleType) {
	case 'busboy':
		header_redirect('busboy');
		break;
	case null:
		echo 'Error loading role from user session';
		break;
	default:
		create_view('index');
}
?>
