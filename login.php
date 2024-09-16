  <?php
  // login.php
 
  header('Content-Type: text/plain');
 
  // Load JSON data from file
  $jsonFile = 'reg.json';
  $jsonData = file_get_contents($jsonFile);
  $users = json_decode($jsonData, true);

 // Get input data
 $username = isset($_POST['username']) ? $_POST['username'] : '';
 $password = isset($_POST['password']) ? $_POST['password'] : '';

 // Initialize response
 $response = 'No username found';

 // Check if username and password are provided
 if (!empty($username) && !empty($password)) {
     foreach ($users as $user) {
         if ($user['username'] === $username) {
            // Check if password matches
             if (password_verify($password, $user['password'])) {
                 $response = 'Login successful';
                 break;
             } else {
                 $response = 'Wrong password!';
             }
         }
     }
 }

echo $response;
?>
