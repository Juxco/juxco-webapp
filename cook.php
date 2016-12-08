<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('cook') === 1) {
	header_redirect('index');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_RESTAURANT);

if ($conn->connect_errno) {
	die('couldnt connect to db');
}

if (check_form_post('updateCookStatusSubmit') === 0) {
	$stmt = $conn->prepare('UPDATE ' . TABLE_FOOD_ORDERS . ' SET cook_status=? WHERE id=?');
	$stmt->bind_param('si', $newCookStatus , $tableNum);

	$curCookStatus = $_POST['curCookStatus'];
	if ($curCookStatus == "none") {
		$newCookStatus = "ordered";
	} else if ($curCookStatus == "ordered") {
		$newCookStatus = "cooking";
	} else if ($curCookStatus == "cooking") {
		$newCookStatus = "delivered";
	} else if ($curCookStatus == "delivered") {
		$newCookStatus = "none";
	}
	
	$tableNum = $_POST['tableNum'];
	$stmt->execute();

	if ($curCookStatus == "cooking") {
		$stmt = $conn->prepare('UPDATE ' . TABLE_STATUS_TABLES . ' SET waiter_status=? WHERE id=?');
		$stmt->bind_param('si', $newCustomerStatus, $tableOrdered);

		$newCustomerStatus = 'check';
		$tableOrdered = $_POST['tableOrdered'];
		$stmt->execute();
	}

	$stmt->close();
}

create_header();
create_view('cook1');

$sql = 'SELECT id, table_ordered, order_item, order_time, cook_status FROM ' . TABLE_FOOD_ORDERS;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>' . $row['table_ordered'] . '</td>';
		echo '<td>' . $row['order_item'] . '</td>';
		echo '<td>' . $row['order_time'] . '</td>';

		if ($row['cook_status'] == "none") {
			echo '
			<td class="success"">
			  <form name="updateCookStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['table_ordered'] . '" name="tableOrdered"></input>
				<input type="hidden" value="' . $row['cook_status'] . '" name="curCookStatus"></input>
				<button id="updateCookStatusSubmit" name="updateCookStatusSubmit" type="submit" class="btn btn-success btn-block">' . ucwords($row['cook_status']) . '</button>
			  </form>
			</td>';
		} else if ($row['cook_status'] == "ordered") {
			echo '
			<td class="info"">
			  <form name="updateCookStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['table_ordered'] . '" name="tableOrdered"></input>
				<input type="hidden" value="' . $row['cook_status'] . '" name="curCookStatus"></input>
				<button id="updateCookStatusSubmit" name="updateCookStatusSubmit" type="submit" class="btn btn-info btn-block">' . ucwords($row['cook_status']) . '</button>
			  </form>
			</td>';
		} else if ($row['cook_status'] == "cooking") {
			echo '
			<td class="warning"">
			  <form name="updateCookStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['table_ordered'] . '" name="tableOrdered"></input>
				<input type="hidden" value="' . $row['cook_status'] . '" name="curCookStatus"></input>
				<button id="updateCookStatusSubmit" name="updateCookStatusSubmit" type="submit" class="btn btn-warning btn-block">' . ucwords($row['cook_status']) . '</button>
			  </form>
			</td>';
		} else if ($row['cook_status'] == "delivered") {
			echo '
			<td class="danger"">
			  <form name="updateCookStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="tableNum"></input>
				<input type="hidden" value="' . $row['table_ordered'] . '" name="tableOrdered"></input>
				<input type="hidden" value="' . $row['cook_status'] . '" name="curCookStatus"></input>
				<button id="updateCookStatusSubmit" name="updateCookStatusSubmit" type="submit" class="btn btn-danger btn-block">' . ucwords($row['cook_status']) . '</button>
			  </form>
			</td>';
		}

		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">No results</td></tr>';
}

$conn->close();

create_view('cook2');
create_footer();
?>
