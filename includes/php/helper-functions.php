<?php
// Returns 0 if logged in, 1 if otherwise
function check_login_state() {
	if (isset($_SESSION['USER_LOGIN_STATE']) && $_SESSION['USER_LOGIN_STATE'] === 0) {
		return 0;
	} else {
		return 1;
	}
}
function header_redirect($url, $statusCode = 303) {
	header('Location: ' . $url, true, $statusCode);
	die();
}
?>
