<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('busboy') === 1) {
	header_redirect('index');
}

create_view('busboy');
?>
