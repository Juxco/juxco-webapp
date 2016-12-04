<?php
// Returns 0 if logged in, 1 if otherwise
function check_login_state() {
	if (isset($_SESSION['USER_LOGIN_STATE']) && $_SESSION['USER_LOGIN_STATE'] === 0) {
		return 0;
	} else {
		return 1;
	}
}

// Returns 0 if on the correct page, 1 if otherwise
function check_correct_page($pageName) {
	if ($pageName === get_role_type()) {
		return 0;
	} else {
		return 1;
	}
}

// Returns role type of the user for the current session, null if not set
function get_role_type() {
	if (isset($_SESSION['ROLE_TYPE'])) {
		return $_SESSION['ROLE_TYPE'];
	} else {
		return null;
	}
}

// If the login state is 0, changes to 1, and vice versa. If unset, sets the state to 0
function invert_login_state() {
	if (isset($_SESSION['USER_LOGIN_STATE'])) {
		if ($_SESSION['USER_LOGIN_STATE'] === 0) {
			$_SESSION['USER_LOGIN_STATE'] = 1;
		} else {
			$_SESSION['USER_LOGIN_STATE'] = 0;
		}
	} else {
		$_SESSION['USER_LOGIN_STATE'] = 0;
	}
}

// http://stackoverflow.com/a/768472
function header_redirect($pageName, $statusCode = 303) {
	header('Location: ' . FULLY_QUALIFIED_URL . $pageName . '.php', true, $statusCode);
	die();
}

// Instantiates a new view onto the screen as specified by the passed argument
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
