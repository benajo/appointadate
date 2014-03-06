<div id="front-contact">
	<h1>Contact AppointADate</h1>

	<form action="contact.php" method="post">
		<p>
			<label for="formName">Name</label>
			<input type="text" name="formName" id="formName" value="<?php echo isset($_POST['formName']) ? $_POST['formName'] : ""; ?>">
		</p>
		<p>
			<label for="formEmail">Email</label>
			<input type="text" name="formEmail" id="formEmail" value="<?php echo isset($_POST['formEmail']) ? $_POST['formEmail'] : ""; ?>">
		</p>
		<p>
			<label for="formSubject">Subject</label>
			<input type="text" name="formSubject" id="formSubject" value="<?php echo isset($_POST['formSubject']) ? $_POST['formSubject'] : ""; ?>">
		</p>
		<p>
			<label for="formQuery">Query</label>
			<textarea type="text" name="formQuery" id="formQuery"><?php echo isset($_POST['formQuery']) ? $_POST['formQuery'] : ""; ?></textarea>
		</p>
		<p>
			<input type="submit" name="contact" value="Submit">
		</p>
	</form>
</div>