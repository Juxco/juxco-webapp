<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_form_post('loginSubmit') === 0) {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_USER_ACCOUNTS);

	if ($conn->connect_errno) {
		die('couldnt connect to db');
	}

	$stmt = $conn->prepare('SELECT password, role_type FROM ' . TABLE_USER_ACCOUNTS . ' WHERE email=?');
	$stmt->bind_param('s', $loginEmail);

	$loginEmail = $_POST['loginEmail'];

	$stmt->execute();
	$stmt->bind_result($loginPassword, $roleType);
	$stmt->fetch();

	$stmt->close();
	$conn->close();

	if ($_POST['loginPassword'] === $loginPassword) {
		invert_login_state();
		$_SESSION['ROLE_TYPE'] = $roleType;
	} else {
		echo '<script type="text/javascript">alert(\'Invalid credentials.\');</script>';
	}
}

if (check_login_state() === 0) {
	header_redirect('index');
}

create_header();
create_view('login');
create_footer();
?>
