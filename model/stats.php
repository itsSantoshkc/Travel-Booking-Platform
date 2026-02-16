<?php
require_once('../conn.php');

class Stats
{
    private $conn;


    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    private function uuidv4()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

public function getDashboardStats() {
    try {

        $sql = "SELECT 
                    (SELECT COUNT(*) FROM Booking) AS total_bookings,
                    (SELECT COALESCE(ROUND(AVG(rating), 1), 0) FROM Reviews) AS avg_rating,
                    (SELECT COUNT(*) FROM User) AS total_users";
        
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Query failed: " . $this->conn->error);
        }

        return [
            "success" => true,
            "data" => $result->fetch_assoc()
        ];

    } catch (Throwable $th) {
        error_log("Dashboard Stats Error: " . $th->getMessage());
        
        return [
            "success" => false, 
            "error" => "Could not retrieve dashboard statistics at this time."
        ];
    }
}
}
