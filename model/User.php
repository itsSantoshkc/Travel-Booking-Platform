<?php
include('../conn.php');

class User
{
    private $conn;
    private $userId;
    private $firstName;
    private $password;
    private $email;
    private $phone;
    private $dateOfBirth;
    private $role;

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

    function getUserWithId($userId) {}

    function searchUserWithEmail($email)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM user WHERE email = ?');
            $stmt->bind_param('s', $email);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_array(MYSQLI_NUM)) {
                    foreach ($row as $r) {
                        print "$r \n";
                    }
                }
                // echo $stmt->affected_rows;
                //   $stmt->bind_result(array($name));

                /* fetch values */
                // while ($stmt->fetch()) {
                //     echo($name);
                // }

            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function authenticateUser($email, $password)
    {
        try {
            $stmt = $this->conn->prepare('SELECT userID, email, password,role FROM user WHERE email = ?');
            $stmt->bind_param('s', $email);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $hashedPassword = "";
                    $stmt->bind_result($this->userId, $this->email, $hashedPassword, $this->role);
                    while ($stmt->fetch()) {
                        if (password_verify($password, $hashedPassword)) {
                            return [
                                'userId' => $this->userId,
                                'email'  => $this->email,
                                'role' => $this->role
                            ];
                        }
                    }
                }
            }
            return false;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function  updateUser($data) {}

    function newUser($userData)
    {
        if (isset($userData)) {


            $uuid = $this->uuidv4();
            $stmt = $this->conn->prepare("INSERT INTO user(userID,firstName,lastName,email,password,dateOfBirth,phone,role) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->bind_param(
                "ssssssss",
                $uuid,
                $userData['firstName'],
                $userData['lastName'],
                $userData['email'],
                $userData['password'],
                $userData['dateOfBirth'],
                $userData['phone'],
                $userData['role']
            );
            if ($stmt->execute()) {
                return ["success" => true, "userId" => $uuid];
            } else {
                return ["success" => false, "error" => $stmt->error];
            }
        } else {
            return ["success" => false, "error" => "Error"];
        }
    }

    function deleteUser($userId) {}
}
