<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('host') === 1) {
	header_redirect('index');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_RESTAURANT);

if ($conn->connect_errno) {
	die('couldnt connect to db');
}

if (check_form_post('addPartySubmit') === 0) {
	$stmt = $conn->prepare('INSERT INTO ' . TABLE_HOST_WAITLIST  . '(party_name, party_size, reservation) VALUES (?, ?, ?)');
	$stmt->bind_param('sis', $addCustomerPartyName, $addCustomerPartySize, $addCustomerReservation);

	$addCustomerPartyName = $_POST['addCustomerPartyName'];
	$addCustomerPartySize = $_POST['addCustomerPartySize'];
	$addCustomerReservation = $_POST['addCustomerReservation'];
	$stmt->execute();

	$stmt->close();
}

if (check_form_post('deleteEntry') === 0) {
	$stmt = $conn->prepare('DELETE FROM ' . TABLE_HOST_WAITLIST . ' WHERE id=?');
	$stmt->bind_param('i', $delEntryNum);

	$delEntryNum = $_POST['delEntryNum'];
	$stmt->execute();

	$stmt->close();
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

	if ($curBusboyStatus == "available") {
		$stmt = $conn->prepare('UPDATE ' . TABLE_STATUS_TABLES . ' SET waiter_status=? WHERE id=?');
		$stmt->bind_param('si',$waiterStatus , $tableNum);

		$waiterStatus = "seated";
		$tableNum = $_POST['tableNum'];
		$stmt->execute();
		$stmt->close();
	}
}

create_header();
create_view('host1');

$sql = 'SELECT id, party_name, party_size, reservation, add_time FROM ' . TABLE_HOST_WAITLIST;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '
		<td>
		  <form name="deleteEntryForm" method="post">
		    <input type="hidden" value="' . $row['id'] . '" name="delEntryNum"></input>
		    <button id="deleteEntry" name="deleteEntry" type="submit" class="btn btn-danger btn-block">' . $row['id']  . '</button>
		  </form>
		</td>';
		echo '<td>' . $row['party_name']  . '</td>';
		echo '<td>' . $row['party_size']  . '</td>';
		echo '<td>' . $row['reservation']  . '</td>';
		echo '<td>' . $row['add_time']  . '</td>';
		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No results</td></tr>';
}


create_view('host2');

$sql = 'SELECT id, seating_size, busboy_status, waiter_status FROM ' . TABLE_STATUS_TABLES;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>' . $row['id'] . '</td>';
		echo '<td>' . $row['seating_size']  . '</td>';

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
		
		echo '<td>' . $row['waiter_status']  . '</td>';
		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">No results</td></tr>';
}

$conn->close();

create_view('host3');
create_footer();
?>
