<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
$errormsg = array();

if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();
    
    if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
    array_push($errors,"All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Email is not valid");
    }
    if (strlen($password)<8) {
    array_push($errors,"Password must be at least 8 charactes long");
    }
    if ($password!==$passwordRepeat) {
    array_push($errors,"Password does not match");
    }
    require_once "database.php";

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount>0) {
    array_push($errors,"Email already exists!");
    }
    if (count($errors) > 0) {
    foreach ($errors as  $error) {
        array_push($errormsg, $error);
    }
    
     }else{
    $sql = "INSERT INTO users (name, email, password) VALUES ( ?, ?, ? )";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            $_SESSION['success_message'] = "You are registered successfully.";
            header("Location: login.php");
            exit();
        }else{
            die("Something went wrong!");
        }
    }
    

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body, html {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .row {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 600px;
            background-color: #f0f0f0;
            padding: 20px;
        }
    </style>
</head>
<body style="background-image:url(src/bgregis.jpg)">
    <div class="col">
        <div class="row mb-5">
                <img src="src/percilok.png" style="width: 180px;">
        </div>
        <div class="container bg-gray-40 row">
        <?php if(count($errormsg) > 0):
             foreach($errormsg as  $error): ?>
            <div class='alert alert-danger'><?= $error ?></div>
            
            <?php endforeach;
            endif; ?>
            
            <form action="registration.php" method="post" >
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password">
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
            <div>
            <div class="mt-3"><p>Already Registered?  <a href="login.php">Login Here</a></p></div>
        </div>
        </div>
    </div>
</body>
</html>