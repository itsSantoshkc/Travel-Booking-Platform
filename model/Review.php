<?php

class Review
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
function newReview($reviewData)
{
    if (empty($reviewData)) {
        return ["success" => false, "error" => "No data provided"];
    }

   $uuid = $this->uuidv4();
    $this->conn->begin_transaction();

    try {
        $sql = "INSERT INTO reviews (review_id,user_id,package_id ,rating ,review) 
                VALUES (?, ?, ?, ?,?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", 
            $uuid,
            $reviewData['userID'],
            $reviewData['package_id'],
            $reviewData['rating'],
            $reviewData['review'],
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to add a new review: " . $stmt->error);
        }

 

        $this->conn->commit();
        return ["success" => true,"message" => "Review added successfully"];

    } catch (Exception $e) {
        
        $this->conn->rollback();
        error_log("Review Creation Error: " . $e->getMessage()); // Log it for the dev
        return ["success" => false, "error" => $e->getMessage()];
    }
}

public function getReviewByPackageID($id)
    {
        // We join with the users table to get the name of the person who wrote the review
        $sql = "SELECT r.*, u.firstName,u.lastName 
                FROM reviews r 
                JOIN user u ON r.user_Id = u.userID 
                WHERE r.package_id = ? 
                ORDER BY r.createdAt DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

    public function getAllReview()
    {
        // We join with the users table to get the name of the person who wrote the review
        $sql = "SELECT r.*, u.firstName,u.lastName ,t.name
                FROM reviews r 
                JOIN user u ON r.user_Id = u.userID 
                JOIN travelpackages t ON r.package_id =  t.package_id
                
                ORDER BY r.createdAt DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

    


}
