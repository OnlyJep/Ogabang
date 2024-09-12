<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Retrieve the form data
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$email = trim($_POST['email']);

// Validate the input data
if (empty($username) || empty($password) || empty($email)) {
  echo json_encode(["success" => false, "error" => "empty_fields"]);
      exit;
  }

     // Sanitize and validate input
     $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
     $email = filter_var($email, FILTER_SANITIZE_EMAIL);

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         echo json_encode(["success" => false, "error" => "invalid_email"]);
         exit;
     }

     // Hash the password
     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

     // JSON file path
     $file = 'reg.json';

     // Read the existing data from the JSON file or initialize if not present
     if (file_exists($file)) {
         $currentData = json_decode(file_get_contents($file), true);

         // Check if the file is corrupted or contains invalid JSON
         if (json_last_error() !== JSON_ERROR_NONE) {
             echo json_encode(["success" => false, "error" => "json_decode_failed"]);
             exit;
         }
     } else {
         $currentData = array();
     }

     // Check if the username or email already exists
     foreach ($currentData as $user) {
         if ($user['username'] === $username) {
             echo json_encode(["success" => false, "error" => "username_taken"]);
             exit;
         }

        if ($user['email'] === $email) {
            echo json_encode(["success" => false, "error" => "email_taken"]);
            exit;
        }
     }

    // Add the new user to the array
    $userData = array(
        'username' => $username,
        'password' => $hashedPassword,
         'email' => $email
     );
     $currentData[] = $userData;

     // Write the updated data back to the JSON file
     if (file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
         echo json_encode(["success" => true]);66     } else {
         echo json_encode(["success" => false, "error" => "file_write_failed"]);
     }
 }
 ?>
