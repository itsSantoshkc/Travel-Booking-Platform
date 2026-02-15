<?php

class Review
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

 

function newReview($reviewData)
{
    if (empty($reviewData)) {
        return ["success" => false, "error" => "No data provided"];
    }

  
    $this->conn->begin_transaction();

    try {
        $sql = "INSERT INTO reviews ( userId ,package_id ,rating ,review) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", 
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
        $sql = "SELECT r.*, u.firstName 
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


}
