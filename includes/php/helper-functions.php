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
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/views/header.html';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/views/' . $viewName . '.html';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/views/footer.html';
}

// Returns 0 if a form successfully submitted using method post, 1 if otherwise
// The post method is validated further by the submit button name attribute
function check_form_post($submitButton) {
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$submitButton])) {
		return 0;
	} else {
		return 1;
	}
}
?>
