<?php
	/**
	 * Check if the form is posted. If so, save posted data to the database.
	 * Also filter out some default WordPress form fields
	 */
	if(sizeof( $_POST ) > 0 ) {
		$data = $_POST;

		unset($data['dashboard-widget-nonce']);
		unset($data['_wp_http_referer']);
		unset($data['widget_id']);
		unset($data['submit']);

		self::update_dashboard_widget_options(
			self::settings['id'],
			array(
				'data' => json_encode($data)
			)
		);
	}

	/**
	 * get data from database
	 * the true flag parameter in the json_decode function indicates it should return an array
	 */
	$data = json_decode(self::get_dashboard_widget_option(self::settings['id'], 'data'), true);

	echo "<table id=\"form-table\">";
	echo "<thead><tr><th>" . self::translations['key'] . "</th><th colspan=\"2\">" . self::translations['value'] . "</th></tr></thead>";
	echo "<tbody>";

	/**
	 * Check if there is any data and if so, render the correct input fields
	 */
	if(isset($data) && sizeof($data) > 0) {
		$length = sizeof($data) / 2;

		for($i=1; $i<=$length; $i++) {
			echo "<tr>";
			echo "<td><input name=\"key_" . $i . "\" type=\"text\" value=\"" . stripslashes(htmlspecialchars($data["key_".$i])) . "\"></td>";
			echo "<td><input name=\"value_" . $i . "\" type=\"text\" value=\"" . stripslashes(htmlspecialchars($data["value_".$i])) . "\"></td>";
			echo "<td class=\"remove text--center\"><a href=\"#\" class=\"btn btn--remove-row\" data-row-index=\"".$i."\"><i class=\"fa fa-minus-square fa-1x\"></i></a></td>";
			echo "</tr>";
		}
	}
	else {
		/**
		 * Render default empty input fields
		 */
		echo "<tr>";
		echo "<td><input name=\"key_1\" type=\"text\"></td>";
		echo "<td><input name=\"value_1\" type=\"text\"></td>";
		echo "<td class=\"remove text--center\"><a href=\"#\" class=\"btn btn--remove-row\" data-row-index=\"1\"><i class=\"fa fa-minus-square fa-1x\"></i></a></td>";
		echo "</tr>";
	}

	echo "</tbody>";
	echo "</table>";

	/**
	 * Render button to add a new table row with input fields
	 */
	echo "<div class=\"text-right\"><a href=\"#\" class=\"btn btn--add-row\"><i class=\"fa fa-plus-square fa-1x\"></i>" . self::translations['add_another_row'] . "</a></div>";

?>

<hr />
