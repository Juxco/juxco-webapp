<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 1) {
	header_redirect('login');
}

if (check_correct_page('waiter') === 1) {
	header_redirect('index');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_RESTAURANT);

if ($conn->connect_errno) {
	die('couldnt connect to db');
}

if (check_form_post('orderFoodSubmit') === 0) {
	$stmt = $conn->prepare('INSERT INTO ' . TABLE_FOOD_ORDERS . '(table_ordered, order_item) VALUES (?, ?)');
	$stmt->bind_param('is', $tableOrdered, $foodOrderItems);

	$tableOrdered = $_POST['tableOrdered'];

	if(!empty($_POST['foodOrderItems'])) {
		foreach($_POST['foodOrderItems'] as $item) {
			if ($item == "water") {
				for ($i = 0; $i < $_POST['waterQuantity']; $i++) {
					$foodOrderItems = $item;
					$stmt->execute();
				}
			} else if ($item == "soda") {
				for ($i = 0; $i < $_POST['sodaQuantity']; $i++) {
					$foodOrderItems = $item;
					$stmt->execute();
				}
			} else if ($item == "hotdog") {
				for ($i = 0; $i < $_POST['hotdogQuantity']; $i++) {
					$foodOrderItems = $item;
					$stmt->execute();
				}
			} else if ($item == "hamburger") {
				for ($i = 0; $i < $_POST['hamburgerQuantity']; $i++) {
					$foodOrderItems = $item;
					$stmt->execute();
				}
			} else if ($item == "fries") {
				for ($i = 0; $i < $_POST['friesQuantity']; $i++) {
					$foodOrderItems = $item;
					$stmt->execute();
				}
			}
		}
	}

	$stmt = $conn->prepare('UPDATE ' . TABLE_STATUS_TABLES . ' SET waiter_status=? WHERE id=?');
	$stmt->bind_param('si',$newCustomerStatus , $tableOrdered);

	$newCustomerStatus = 'ordered';
	$tableOrdered = $_POST['tableOrdered'];
	$stmt->execute();

	$stmt->close();
}

if (check_form_post('updateCustomerStatusSubmit') === 0) {
	$stmt = $conn->prepare('UPDATE ' . TABLE_STATUS_TABLES . ' SET waiter_status=? WHERE id=?');
	$stmt->bind_param('si',$newCustomerStatus , $customerStatusNum);

	$newCustomerStatus = $_POST['newCustomerStatus'];
	$customerStatusNum = $_POST['customerStatusNum'];
	$stmt->execute();

	$stmt->close();
}

create_header();
create_view('waiter1');

$sql = 'SELECT id, waiter_status FROM ' . TABLE_STATUS_TABLES;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		echo '<tr>';
		echo '<td>' . $row['id'] . '</td>';

		if ($row['waiter_status'] == "none") {
			echo '
			<td class="success">
			  <form name="updateCustomerStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="customerStatusNum" id="customerStatusNum"></input>
					<div class="form-group">
					  <label class="sr-only" for="newCustomerStatus">Update status: </label>
					  <select class="form-control" id="newCustomerStatus" name="newCustomerStatus">
						<option value="">' . ucwords($row['waiter_status']) . '</option>
						<option value="none">None</option>
						<option value="seated">Seated</option>
						<option value="ordered">Ordered</option>
						<option value="check">Check</option>
					  </select>
					</div>
				<button id="updateCustomerStatusSubmit" name="updateCustomerStatusSubmit" type="submit" class="btn btn-success">Update customer status</button>
			  </form>
			</td>';
		} else if ($row['waiter_status'] == "seated") {
			echo '
			<td class="info">
			  <form name="updateCustomerStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="customerStatusNum" id="customerStatusNum"></input>
					<div class="form-group">
					  <label class="sr-only" for="newCustomerStatus">Update status: </label>
					  <select class="form-control" id="newCustomerStatus" name="newCustomerStatus">
						<option value="">' . ucwords($row['waiter_status']) . '</option>
						<option value="none">None</option>
						<option value="seated">Seated</option>
						<option value="ordered">Ordered</option>
						<option value="check">Check</option>
					  </select>
					</div>
				<button id="updateCustomerStatusSubmit" name="updateCustomerStatusSubmit" type="submit" class="btn btn-info">Update customer status</button>
			  </form>
			</td>';
		} else if ($row['waiter_status'] == "ordered") {
			echo '
			<td class="warning">
			  <form name="updateCustomerStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="customerStatusNum" id="customerStatusNum"></input>
					<div class="form-group">
					  <label class="sr-only" for="newCustomerStatus">Update status: </label>
					  <select class="form-control" id="newCustomerStatus" name="newCustomerStatus">
						<option value="">' . ucwords($row['waiter_status']) . '</option>
						<option value="none">None</option>
						<option value="seated">Seated</option>
						<option value="ordered">Ordered</option>
						<option value="check">Check</option>
					  </select>
					</div>
				<button id="updateCustomerStatusSubmit" name="updateCustomerStatusSubmit" type="submit" class="btn btn-warning">Update customer status</button>
			  </form>
			</td>';
		} else if ($row['waiter_status'] == "check") {
			echo '
			<td class="danger">
			  <form name="updateCustomerStatusForm" method="post">
				<input type="hidden" value="' . $row['id'] . '" name="customerStatusNum" id="customerStatusNum"></input>
					<div class="form-group">
					  <label class="sr-only" for="newCustomerStatus">Update status: </label>
					  <select class="form-control" id="newCustomerStatus" name="newCustomerStatus">
						<option value="">' . ucwords($row['waiter_status']) . '</option>
						<option value="none">None</option>
						<option value="seated">Seated</option>
						<option value="ordered">Ordered</option>
						<option value="check">Check</option>
					  </select>
					</div>
				<button id="updateCustomerStatusSubmit" name="updateCustomerStatusSubmit" type="submit" class="btn btn-danger">Update customer status</button>
			  </form>
			</td>';
		}

		echo '</tr>';
    }
} else {
    echo '<tr><td colspan="2">No results</td></tr>';
}

$conn->close();

create_view('waiter2');
create_footer();
?>
