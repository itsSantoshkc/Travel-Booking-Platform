

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $uploadedFiles = [];
    $uploadDirectory = "../uploads/activities/";

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }
    var_dump($_POST);
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    if (empty($name)) $errors[] = "Activity name is required.";
    if (!is_numeric($price)) $errors[] = "Price must be a number.";

    $totalFilesFound = 0;
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    for ($i = 1; $i <= 4; $i++) {
        $fileKey = "image_$i";

        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
            $fileName = $_FILES[$fileKey]['name'];
            $fileType = $_FILES[$fileKey]['type'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Image $i must be JPG, PNG, or WebP.";
                continue;
            }

            $newFileName = uniqid() . '_' . basename($fileName);
            $destPath = $uploadDirectory . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $uploadedFiles[] = $destPath; 
                $totalFilesFound++;
            } else {
                $errors[] = "Error moving Image $i to the upload folder.";
            }
        }
    }

    if ($totalFilesFound < 3) {
        $errors[] = "You must successfully upload at least 3 images (You uploaded: $totalFilesFound).";


        foreach ($uploadedFiles as $file) {
            if (file_exists($file)) unlink($file);
        }
    }

    if (empty($errors)) {
        $activityData = [
            "name"        => trim($_POST['name']),
            "price"       => (float)$_POST['price'],     
            "description" => trim($_POST['description'] ?? ''),
            "slots"       => (int)($_POST['slots'] ?? 0),  
            "location"    => trim($_POST['location'] ?? ''),
            "eventType"   => $_POST['eventType'] ?? 'one-time',
            "mainDate"    => $_POST['mainDate'] ?? date('Y-m-d'),
            "slots_list"  => $_POST['slots_list'] ?? [],   
            "images"      => $uploadedFiles,               
        ];

        
        if ($activityData['eventType'] === 'recurring' && isset($_POST['days'])) {
            $activityData['days'] = $_POST['days'];
        }

        require_once '../model/Activity.php';
        $activityModel = new Activity($conn);
        $result = $activityModel->newActivity($activityData);

        echo "<h1>Success!</h1>";
        echo "<p>Activity '$name' created with $totalFilesFound images.</p>";
    } else {
        echo "<div style='color:red;'><strong>Errors:</strong><ul>";
         foreach ($uploadedFiles as $file) {
            if (file_exists($file)) unlink($file);
        }
        foreach ($errors as $error) echo "<li>$error</li>";
        echo "</ul></div>";
    }
}
?>