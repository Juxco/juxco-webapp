<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('host') === 1) {
	header_redirect('index');
}

create_header();
create_view('host1');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_RESTAURANT);
if ($conn->connect_errno) {
	die('couldnt connect to db');
}
$sql = 'SELECT id, party_name, party_size, reservation, add_time FROM ' . TABLE_HOST_WAITLIST;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>' . $row['id']  . '</td>';
		echo '<td>' . $row['party_name']  . '</td>';
		echo '<td>' . $row['party_size']  . '</td>';
		echo '<td>' . $row['reservation']  . '</td>';
		echo '<td>' . $row['add_time']  . '</td>';
		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No results</td></tr>';
}
$conn->close();

create_view('host2');
create_footer();
?>
