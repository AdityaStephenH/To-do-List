<?php
try {
    $hostname = "localhost"; 
    $dbusername = "root";      
    $dbpassword = "";          
    $database_name = "utslab"; 

    
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$hostname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database_name'";
    $result = $pdo->query($query);

    if ($result->fetchColumn() === false) {
        $user_table = "users";     
        $task_table = "tasks";

        $userid = "id";         
        $username = "name";   
        $email = "email";  
        $password = "password";

        $taskid = "id";
        $task = "task";
        $status = "status";
        $progress = "progress";
        // Database doesn't exist; create it
        $createDatabaseQuery = "CREATE DATABASE $database_name";
        $pdo->exec($createDatabaseQuery);
        echo "Database created successfully.";

        // Switch to the newly created database
        $pdo->exec("USE $database_name");

        // Create the table
        $createUserTable = "CREATE TABLE $user_table (
            $userid INT AUTO_INCREMENT PRIMARY KEY,
            $username VARCHAR(255) NOT NULL,
			$email VARCHAR(255) NOT NULL,
            $password VARCHAR(255) NOT NULL
        )";

        $pdo->exec($createUserTable);

        $createTaskTable = "CREATE TABLE $task_table (
            $taskid INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            $task VARCHAR(255) NOT NULL,
            $status VARCHAR(255) NOT NULL,
            $progress VARCHAR(255) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $pdo->exec($createTaskTable);

        $conn = mysqli_connect($hostname, $dbusername, $dbpassword, $database_name) or die ('Gagal terhubung ke database');
    } else {
        $conn = mysqli_connect($hostname, $dbusername, $dbpassword, $database_name) or die ('Gagal terhubung ke database');
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

?>
