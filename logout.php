<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 0) {
	invert_login_state();
}

header_redirect('index');
?>
