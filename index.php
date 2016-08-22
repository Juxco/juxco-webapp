<?php
require_once __DIR__ . '/includes/php/init.php';

$isLoggedIn = check_login_state();
if ($isLoggedIn !== 0) {
	header_redirect('login.php');
}

require_once __DIR__ . '/includes/html/header.html';
require_once __DIR__ . '/includes/html/index.html';
require_once __DIR__ . '/includes/html/footer.html';
?>
