<?php
class DatabaseSettings {
	var $settings;

	function get_settings() {
        // Database variables
		// Host name
		$settings["dbhost"] = "";
		// Database name
		$settings['dbname'] = "";
		// Username
		$settings['dbusername'] = "";
		// Password
        $settings['dbpassword'] = "";
        // Table
        $settings['dbtable'] = "";

		return $settings;
	}
}
?>