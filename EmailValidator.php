<?php
require_once("DatabaseSettings.php");

class EmailValidator extends DatabaseSettings {

    // Connection link
    var $conn;
    // Table name we use
    var $table;
 
    // Open database connection
   private function connect() {
        // Load settings from parent class
        $settings = DatabaseSettings::get_settings();

        // Get the main settings from the array
        $host = $settings["dbhost"];
        $name = $settings["dbname"];
        $user = $settings["dbusername"];
        $pass = $settings["dbpassword"];
        $this->table = $settings["dbtable"];

		// Connect to the database
        $this->conn = new mysqli($host, $user, $pass, $name);

        // Set right charset for security
        $this->conn->set_charset("utf8mb4");
        
        // Something went wrong
        if ($this->conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
 
    // Email validation
    public function valid_email($email) {
        // Sanitize email address
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Filter email address
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Connect to the database
            $this->connect();

            // Check if we have table for emails and create one if not
            $this->check_table();

            // Prevent SQL injections
            //$email = $this->conn->real_escape_string($email);

            // Just checking that we don't have already email in database
            if($this->check_doubles($email)) {
                $this->conn->close();
                return "Email already saved!";
            }
            // Saving email to the database
            else if($this->save_email($email)) {
                $this->conn->close();
                return "New email saved successfully!";
            }
            // If there is problem, I need to fix it
            else {
                $this->conn->close();
                return "There was a problem, please contact administrator!";
            }
        }
        // Email is not valid
        else {
            return "Email is not valid, try again!";
        }
    }
    
    // Save email to the database
    private function save_email($email) {
        $stmt = $this->conn->prepare("INSERT INTO ".$this->table." (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        return $stmt->execute() === TRUE;
    }
    
    // Check that table exists and create if not
    private function check_table() {
        $query = "SELECT * FROM ".$this->table;
        $result = $this->conn->query($query);
        if(empty($result)) {
            $query =    "CREATE TABLE ".$this->table." (
                        id int(11) AUTO_INCREMENT,
                        email varchar(255) NOT NULL,
                        PRIMARY KEY  (id)
                        )";
            $result = $this->conn->query($query);
        }
    }

    // Return true if email is already in database
    private function check_doubles($email) {
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
 }
 ?>