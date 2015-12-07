<?php
	global $settings;

	/**
	 * get data from database
	 * the true flag parameter in the json_decode function indicates it should return an array
	 */
	$data = json_decode(self::get_dashboard_widget_option($settings['id'], 'data'), true);

	/**
	 * Check if there is any data and if so, render the fields
	 */
	if(isset($data) && sizeof($data) > 0) {
		$length = sizeof($data) / 2;

		echo "<table>";
		for($i=1; $i<=$length; $i++) {
			echo "<tr>";
			echo "<td>" . formatString(stripslashes($data['key_'.$i])) . "</td>";
			echo "<td>" . formatString(stripslashes($data['value_'.$i])) . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	/**
	 * Format the data (e.g. render anchor links)
	 * @param  String $string the string to format
	 * @return String         formatted string
	 */
	function formatString($string) {
		if(stripos($string, "href=") > -1 && !stripos($string, "target")) {
			$string = preg_replace('/href="(.+)"/', 'href="$1" target="_blank"', $string);
		} else if(stripos($string, "http://") > -1) {
			$string = preg_replace('/(.+)/', '<a href="$1" target="_blank">$1</a>', $string);
		}

		return $string;
	}
?>