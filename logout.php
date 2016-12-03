<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

invert_login_state();

header_redirect('index.php');
?>
