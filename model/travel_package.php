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

    function newPackage($packageData)
    {
        if (empty($packageData)) {
            return ["success" => false, "error" => "No data provided"];
        }

        $uuid = $this->uuidv4();


        $this->conn->begin_transaction();

        try {
            $sql = "INSERT INTO travelPackages(package_id,name,arrivalTime,duration,starting_date,location,price,description,totalSlots)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $packageId   = $uuid;
            $name        = $packageData['name'];
            $arrivalTime = $packageData['arrival_time'];
            $duration    = (int)$packageData['duration'];
            $startDate   = $packageData['starting_date'];
            $location    = $packageData['location'];
            $price       = (float)$packageData['price'];
            $description = $packageData['description'];
            $slots       = (int)$packageData['slots'];

            $stmt->bind_param(
                "sssissdsi",
                $packageId,
                $name,
                $arrivalTime,
                $duration,
                $startDate,
                $location,
                $price,
                $description,
                $slots,
            );





            if (!$stmt->execute()) {
                throw new Exception("Failed to create Package: " . $stmt->error);
            }


            if (!empty($packageData['images'])) {

                $imgStmt = $this->conn->prepare("INSERT INTO package_images (image_id,image_path, package_id) VALUES (?, ?,?)");
                foreach ($packageData['images'] as  $index => $image) {
                    $imgStmt->bind_param("iss", $index, $image, $uuid);
                    $imgStmt->execute();
                }
            }

            $this->conn->commit();
            return ["success" => true, "package_id" => $uuid];
        } catch (Exception $e) {

            $this->conn->rollback();

            echo ($e->getMessage());
            error_log("Package Creation Error: " . $e->getMessage()); // Log it for the dev
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function getAllAvailableTravelPackages()
    {

        $sql = "SELECT p.*, 
    GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') AS image_list, 
    COALESCE(b.booked_slots, 0) AS booked_slots,
    ROUND(COALESCE(AVG(r.rating), 0), 1) AS avg_rating,
    COUNT(DISTINCT r.review_id) AS total_reviews
FROM travelPackages p 
LEFT JOIN package_images i ON p.package_id = i.package_id 
LEFT JOIN (
    SELECT package_id, SUM(no_of_slots) AS booked_slots 
    FROM booking 
    GROUP BY package_id
) b ON p.package_id = b.package_id
LEFT JOIN reviews r ON p.package_id = r.package_id
WHERE p.starting_date > CURDATE() 
GROUP BY p.package_id 
ORDER BY p.created_at DESC";

        $result = $this->conn->query($sql);
        $activities = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $row['images'] = $row['image_list'] ? explode('|', $row['image_list']) : [];

                unset($row['image_list']);

                $activities[] = $row;
            }
        }

        return $activities;
    }

public function searchPackage($location, $date)
{
    $sql = "SELECT t.*, 
            GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') AS image_list,
            COALESCE(b.booked_slots, 0) AS booked_slots,
            ROUND(COALESCE(AVG(r.rating), 0), 1) AS avg_rating,
            COUNT(DISTINCT r.review_id) AS total_reviews
        FROM travelPackages t
        LEFT JOIN package_images i ON t.package_id = i.package_id
        LEFT JOIN (
            SELECT package_id, SUM(no_of_slots) AS booked_slots 
            FROM booking 
            GROUP BY package_id
        ) b ON t.package_id = b.package_id
        LEFT JOIN reviews r ON t.package_id = r.package_id
        WHERE 1=1";

    $params = [];
    $types = "";

    if (!empty($location)) {
        $sql .= " AND t.location LIKE ?";
        $params[] = "%$location%";
        $types .= "s";
    }

    if (!empty($date)) {
        $sql .= " AND t.starting_date = ?";
        $params[] = $date;
        $types .= "s";
    }

    $sql .= " GROUP BY t.package_id ORDER BY t.created_at DESC";

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
        $sql = "SELECT t.*, GROUP_CONCAT(DISTINCT i.image_path SEPARATOR '|') AS image_list, stats.booked_slots, ROUND(AVG(r.rating), 1) AS avg_rating, COUNT(DISTINCT r.review_id) AS total_reviews FROM travelpackages t LEFT JOIN package_images i ON t.package_id = i.package_id LEFT JOIN ( SELECT package_id, SUM(no_of_slots) AS booked_slots FROM booking GROUP BY package_id ) AS stats ON t.package_id = stats.package_id LEFT JOIN reviews r ON t.package_id = r.package_id WHERE t.package_id = ? GROUP BY t.package_id;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $package = $result->fetch_assoc();

        if (!$package)
            return null;

        $package['images'] = $package['image_list'] ? explode('|', $package['image_list']) : [];
        unset($package['image_list']);

        return $package;
    }


    public function updateFullActivity($id, $name, $description, $duration,$location,$price,$slots_count,$starting_date,$arrivalTime)
    {
        // Start a transaction to ensure data integrity
        $this->conn->begin_transaction();
        try {
            
            // 1. Update the main Activity table
            $stmt = $this->conn->prepare("UPDATE travelpackages SET name = ?, description = ?, duration = ?, starting_date = ?,arrivalTime = ?, price = ?, location = ?, totalSlots = ? WHERE package_id = ?");

            $stmt->bind_param("ssissdsis", $name, $description, $duration, $starting_date,$arrivalTime, $price, $location, $slots_count, $id);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            // Rollback if anything fails
            $this->conn->rollback();
            return false;
        }
    }

    public function deleteTravelPackage($id){
 $this->conn->begin_transaction();
        try {
            
            // 1. Update the main Activity table
            $stmt = $this->conn->prepare("DELETE FROM travelpackages WHERE package_id = ?");

            $stmt->bind_param("s",  $id);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            // Rollback if anything fails
            $this->conn->rollback();
            return false;
        }

    }
}
