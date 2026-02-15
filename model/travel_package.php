<?php

class Travel
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

    function newActivity($activityData)
    {
        if (empty($activityData)) {
            return ["success" => false, "error" => "No data provided"];
        }

        $uuid = $this->uuidv4();
        $eventType = $activityData['eventType'];

        $dayMap = ["Sun" => 1, "Mon" => 2, "Tue" => 3, "Wed" => 4, "Thu" => 5, "Fri" => 6, "Sat" => 7];

        $this->conn->begin_transaction();

        try {
            $sql = "INSERT INTO activity (package_id, name, description, no_of_slots, price, event_type, location, starting_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "sssidsss",
                $uuid,
                $activityData['name'],
                $activityData['description'],
                $activityData['slots'],
                $activityData['price'],
                $eventType,
                $activityData['location'],
                $activityData['mainDate']
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to create main activity: " . $stmt->error);
            }

            if (!empty($activityData['slots_list'])) {
                $slotStmt = $this->conn->prepare("INSERT INTO activity_slots (time_slots, package_id) VALUES (?, ?)");
                foreach ($activityData['slots_list'] as $slot) {
                    $slotStmt->bind_param("ss", $slot, $uuid);
                    $slotStmt->execute();
                }
            }

            if (!empty($activityData['images'])) {
                $imgStmt = $this->conn->prepare("INSERT INTO activity_images (image_path, package_id) VALUES (?, ?)");
                foreach ($activityData['images'] as $image) {
                    $imgStmt->bind_param("ss", $image, $uuid);
                    $imgStmt->execute();
                }
            }

            if ($eventType === "recurring" && !empty($activityData['days'])) {
                $daysStmt = $this->conn->prepare("INSERT INTO activity_days (day_id, package_id) VALUES (?, ?)");
                foreach ($activityData['days'] as $dayName) {
                    $daysStmt->bind_param("is", $dayName, $uuid);
                    $daysStmt->execute();
                }
            }

            $this->conn->commit();
            return ["success" => true, "activityID" => $uuid];
        } catch (Exception $e) {

            $this->conn->rollback();
            error_log("Activity Creation Error: " . $e->getMessage()); // Log it for the dev
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function getAllAvailableTravelPackages()
    {
        // We use LEFT JOIN so activities show up even if they don't have images yet
        // DISTINCT inside GROUP_CONCAT prevents path duplication
        $sql = "SELECT  p.*,  GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') as image_list FROM travelPackages p
                LEFT JOIN package_images i ON p.package_id = i.package_id
                GROUP BY p.package_id ORDER BY p.created_at DESC;";

        $result = $this->conn->query($sql);
        $activities = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Convert the piped string of paths into a clean PHP array
                $row['images'] = $row['image_list'] ? explode('|', $row['image_list']) : [];

                // Remove the raw string to keep the array clean
                unset($row['image_list']);

                $activities[] = $row;
            }
        }

        return $activities;
    }

    public function searchActivities($location, $date, $people)
    {
        // Start with a base query
        $sql = "SELECT a.*, GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') as image_list
            FROM activity a
            LEFT JOIN activity_images i ON a.package_id = i.package_id
            WHERE 1=1"; // 'WHERE 1=1' allows us to easily append AND conditions

        $params = [];
        $types = "";

        // Dynamically add filters if they are provided
        if (!empty($location)) {
            $sql .= " AND a.location LIKE ?";
            $params[] = "%$location%";
            $types .= "s";
        }

        if (!empty($date)) {
            $sql .= " AND a.starting_date = ?";
            $params[] = $date;
            $types .= "s";
        }

        if ($people > 0) {
            $sql .= " AND a.no_of_slots >= ?";
            $params[] = $people;
            $types .= "i";
        }

        $sql .= " GROUP BY a.package_id ORDER BY a.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $activities = [];
        while ($row = $result->fetch_assoc()) {
            $row['images'] = $row['image_list'] ? explode('|', $row['image_list']) : [];
            $activities[] = $row;
        }
        return $activities;
    }

    public function getTravelPackageById($id)
    {
        // Fix: WHERE comes before GROUP BY. 
        // We fetch the piped strings for images, slots, and days in one go.
        $sql = "SELECT a.*, 
            GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') as image_list
            FROM travelpackages a 
            LEFT JOIN package_images i ON a.package_id = i.package_id 
            WHERE a.package_id = ?
            GROUP BY a.package_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $activity = $result->fetch_assoc();

        if (!$activity) return null;

        $activity['images'] = $activity['image_list'] ? explode('|', $activity['image_list']) : [];
        unset($activity['image_list']);

        return $activity;
    }


    public function updateFullActivity($id, $name, $description, $eventType, $startDate, $price, $location, $noOfSlots, $days, $times)
    {
        // Start a transaction to ensure data integrity
        $this->conn->begin_transaction();

        try {
            // 1. Update the main Activity table
            $stmt = $this->conn->prepare("UPDATE activity SET name = ?, description = ?, event_type = ?, starting_date = ?, price = ?, location = ?, no_of_slots = ? WHERE package_id = ?");
            $stmt->bind_param("ssisdsis", $name, $description, $eventType, $startDate, $price, $location, $noOfSlots, $id);
            $stmt->execute();

            // 2. Clear old Recurring Days and Time Slots
            $this->conn->query("DELETE FROM activity_days WHERE package_id = '$id'");
            $this->conn->query("DELETE FROM activity_slots WHERE package_id = '$id'");

            // 3. Only insert new days/slots if it is a recurring event (event_type = 0)
            if ($eventType == 0) {
                // Insert Days
                if (!empty($days)) {
                    $dayStmt = $this->conn->prepare("INSERT INTO activity_days (package_id, day_id) VALUES (?, ?)");
                    foreach ($days as $dayId) {
                        $dayStmt->bind_param("si", $id, $dayId);
                        $dayStmt->execute();
                    }
                }

                // Insert Time Slots
                if (!empty($times)) {
                    $timeStmt = $this->conn->prepare("INSERT INTO activity_slots (package_id, time_slots) VALUES (?, ?)");
                    foreach ($times as $slot) {
                        $timeStmt->bind_param("ss", $id, $slot);
                        $timeStmt->execute();
                    }
                }
            }

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback if anything fails
            $this->conn->rollback();
            return false;
        }
    }
}
