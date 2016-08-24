<?php
// Returns 0 if logged in, 1 if otherwise
function check_login_state() {
	if (isset($_SESSION['USER_LOGIN_STATE']) && $_SESSION['USER_LOGIN_STATE'] === 0) {
		return 0;
	} else {
		return 1;
	}
}

// http://stackoverflow.com/a/768472
function header_redirect($pageName, $statusCode = 303) {
	header('Location: ' . FULLY_QUALIFIED_URL . $pageName, true, $statusCode);
	die();
}

function create_view($viewName) {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/html/header.html';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/html/' . $viewName . '.html';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/html/footer.html';
}
?>
