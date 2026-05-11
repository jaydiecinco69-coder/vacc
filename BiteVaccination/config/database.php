<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'bite_vaccination';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Check if database doesn't exist
            if (strpos($exception->getMessage(), '1049') !== false) {
                die('<div style="padding: 20px; background-color: #fee; border: 1px solid #f99; border-radius: 5px; margin: 20px; font-family: Arial;">
                    <h2 style="color: #c00;">Database Not Found</h2>
                    <p>The database has not been set up yet.</p>
                    <p><strong>Please run the setup first:</strong></p>
                    <p><a href="http://localhost/bitevaccination/setup.php" style="color: #0066cc; text-decoration: underline;">Click here to set up the database</a></p>
                    <p style="color: #666; font-size: 12px; margin-top: 20px;">Or visit: http://localhost/bitevaccination/setup.php</p>
                </div>');
            }
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
