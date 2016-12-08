<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('busboy') === 1) {
	header_redirect('index');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_RESTAURANT);

if ($conn->connect_errno) {
	die('couldnt connect to db');
}

if (check_form_post('updateTableStatus') === 0) {
	$stmt = $conn->prepare('UPDATE ' . TABLE_STATUS_TABLES . ' SET busboy_status=? WHERE id=?');
	$stmt->bind_param('si',$newBusboyStatus , $tableNum);

	$curBusboyStatus = $_POST['curBusboyStatus'];
	if ($curBusboyStatus == "available") {
		$newBusboyStatus = "occupied";
	} else if ($curBusboyStatus == "occupied") {
		$newBusboyStatus = "dirty";
	} else if ($curBusboyStatus == "dirty") {
		$newBusboyStatus = "available";
	}
	
	$tableNum = $_POST['tableNum'];
	$stmt->execute();

	$stmt->close();
}

create_header();
create_view('busboy1');

$sql = 'SELECT id, seating_size, busboy_status, waiter_status FROM ' . TABLE_STATUS_TABLES;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>' . $row['id'] . '</td>';

		if ($row['busboy_status'] == "available") {
			echo '
			<td class="success"">
			  <form name="updateTableStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['busboy_status'] . '" name="curBusboyStatus"></input>
				<button id="updateTableStatus" name="updateTableStatus" type="submit" class="btn btn-success btn-block">' . $row['busboy_status']  . '</button>
			  </form>
			</td>';
		} else if ($row['busboy_status'] == "occupied") {
			echo '
			<td class="danger">
			  <form name="updateTableStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['busboy_status'] . '" name="curBusboyStatus"></input>
				<button id="updateTableStatus" name="updateTableStatus" type="submit" class="btn btn-danger btn-block">' . $row['busboy_status']  . '</button>
			  </form>
			</td>';
		} else if ($row['busboy_status'] == "dirty") {
			echo '
			<td class="warning">
			  <form name="updateTableStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['busboy_status'] . '" name="curBusboyStatus"></input>
				<button id="updateTableStatus" name="updateTableStatus" type="submit" class="btn btn-warning btn-block">' . $row['busboy_status']  . '</button>
			  </form>
			</td>';
		}

		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="2">No results</td></tr>';
}

$conn->close();

create_view('busboy2');
create_footer();
?>
