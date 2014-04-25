<p>
	<textarea name="description" id="formDescription" class="full-width"><?php echo isset($_POST['description']) ? $_POST['description'] : (isset($row['description']) ? $row['description'] : ""); ?></textarea>
</p>