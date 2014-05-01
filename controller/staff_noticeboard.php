<?php
if (isset($_POST['add_notice']) || isset($_POST['edit_notice'])) {

	$errorMessage  = validate_form($_POST['notice_content'], "req", "Content");
	$errorMessage .= validate_form($_POST['notice_title'], "req", "Title");

	if (empty($errorMessage)) {
		$post = escape_post_data();

		if (isset($_POST['add_notice'])) {
			$sql = "INSERT INTO noticeboard SET
					business_id = '{$_SESSION['staff_business_id']}',
					content     = '{$post['notice_content']}',
					title       = '{$post['notice_title']}',
					created     = NOW(),
					updated     = NOW(),
					created_by  = '{$_SESSION['staff_id']}',
					updated_by  = '{$_SESSION['staff_id']}'";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Notice has been created.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
		else {
			$notice = $mysqli->real_escape_string($_GET['notice']);

			$sql = "UPDATE noticeboard SET
					content     = '{$post['notice_content']}',
					title       = '{$post['notice_title']}',
					updated     = NOW(),
					updated_by  = '{$_SESSION['staff_id']}'
					WHERE noticeboard_id = '{$notice}'";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Notice has been updated.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
	}
}
elseif (isset($_GET['remove_notice'])) {
	$sql = "DELETE FROM noticeboard
			WHERE noticeboard_id = {$mysqli->real_escape_string($_GET['remove_notice'])}";
	$result = $mysqli->query($sql);

	if ($result) {
		$message = "Notice has been removed.";
	}
	else {
		$errorMessage = "There has been an unexpected error, please try again.";
	}
}
?>