<?php

class Booking
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    private function uuidv4()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function newBooking($bookingData)
    {
        if (empty($bookingData)) {
            return ["success" => false, "error" => "No data provided"];
        }

        $uuid = $this->uuidv4();
        $this->conn->begin_transaction();

        try {
            
            $sql = "INSERT INTO booking (booking_id, user_id, activity_id, no_of_slots, time, booked_for) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssiss", 
                $uuid, 
                $bookingData['userId'], 
                $bookingData['activityId'], 
                $bookingData['slots'], 
                $bookingData['time'], 
                $bookingData['bookedFor']
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to book an activity: " . $stmt->error);
            }

            $this->conn->commit();
            return ["success" => true, "bookingId" => $uuid];

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Booking Error: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    /**
     * For Admin: See everyone who booked a specific activity
     */
    public function getBookingsByActivityId($activityId)
    {
        $sql = "SELECT b.*, u.username, u.email 
                FROM booking b 
                JOIN users u ON b.user_id = u.user_id 
                WHERE b.activity_id = ? 
                ORDER BY b.booked_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $activityId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * For User: See all my personal bookings with activity details
     */
 public function getBookingsByUserId($userId)
{
    
    $sql = "SELECT 
                b.booking_id, 
                b.no_of_slots, 
                b.time, 
                b.booked_for, 
                a.name as activity_name, 
                a.location, 
                a.price
            FROM booking b
            INNER JOIN activity a ON b.activity_id = a.activity_id
            WHERE b.user_id = ?
            ORDER BY b.booked_for ASC, b.time ASC";

    $stmt = $this->conn->prepare($sql);
    
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $this->conn->error);
        return [];
    }

    
    $type = is_numeric($userId) ? "i" : "s";
    $stmt->bind_param($type, $userId);
    
    $stmt->execute();
    $result = $stmt->get_result();

    
    return $result->fetch_all(MYSQLI_ASSOC);
}

    /**
     * Get details of a single booking
     */
    public function getBookingDetails($bookingId)
    {
        $sql = "SELECT * FROM booking WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $bookingId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

        /**
     * Get details of a single booking
     */
    public function getAllBooking()
    {
        $sql = "SELECT b.*, a.name, u.firstName FROM booking b INNER JOIN activity a ON b.activity_id = a.activity_id INNER JOIN user u ON b.user_id = u.userID";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSlotsOccupied($activityId, $date, $time)
{

    $sql = "SELECT COALESCE(SUM(no_of_slots), 0) as total_occupied 
            FROM booking 
            WHERE activity_id = ? 
            AND booked_for = ? 
            AND time = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sss", $activityId, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return (int)$row['total_occupied'];
}

public function getSlotsPerTimeByDate($activityId, $date)
{
    
    $sql = "SELECT time, SUM(no_of_slots) as total_booked 
            FROM booking 
            WHERE activity_id = ? 
            AND booked_for = ? 
            GROUP BY time";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $activityId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $occupancy = [];
    while ($row = $result->fetch_assoc()) {
        
        $occupancy[$row['time']] = (int)$row['total_booked'];
    }

    return $occupancy;
}

public function getBookingMap($activityId, $datesArray, $timesArray) {
    // 1. Setup Placeholders for the IN clauses
    $datePlaceholders = implode(',', array_fill(0, count($datesArray), '?'));
    $timePlaceholders = implode(',', array_fill(0, count($timesArray), '?'));

    $sql = "SELECT booked_for, time, SUM(no_of_slots) as total_booked 
            FROM booking 
            WHERE activity_id = ? 
            AND booked_for IN ($datePlaceholders) 
            AND time IN ($timePlaceholders)
            GROUP BY booked_for, time";

    $stmt = $this->conn->prepare($sql);

    // 2. Bind parameters dynamically
    $types = "s" . str_repeat("s", count($datesArray)) . str_repeat("s", count($timesArray));
    $params = array_merge([$activityId], $datesArray, $timesArray);
    $stmt->bind_param($types, ...$params);
    
    $stmt->execute();
    $dbResult = $stmt->get_result();

    // 3. Initialize the full associative array with 0s
    // This ensures even dates/times with NO bookings exist in your array
    $bookingMap = [];
    foreach ($datesArray as $date) {
        foreach ($timesArray as $time) {
            $bookingMap[$date][$time] = 0;
        }
    }

    // 4. Fill in the actual data from the database
    while ($row = $dbResult->fetch_assoc()) {
        $bookingMap[$row['booked_for']][$row['time']] = (int)$row['total_booked'];
    }

    return $bookingMap;
}

    
}