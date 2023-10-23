<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
if (isset($_POST['submit'])) {
    $_SESSION['success_message'] = "You are registered successfully.";
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
<body style="background-image:url(src/bglogin.jpg)">
    
    <div class="col">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success text-center' style='position: fixed; top: 0; left: 0; width: 100%; z-index: 999;'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']);
        }
        ?>


        <div class="row mb-5">
            <img src="src/percilok.png" style="width: 180px;">
        </div>
        <div class="container row">
            <?php
            if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if ($user) {
                    if (password_verify($password, $user["password"])) {
                        session_start();
                        $_SESSION["user"] = $user["id"];
                        header("Location: index.php");
                        die();
                    }else{
                        echo "<div class='alert alert-danger'>Password does not match</div>";
                    }
                }else{
                    echo "<div class='alert alert-danger'>Email does not match</div>";
                }
            }
            ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div class="mt-3"><p>Not registered yet? <a href="registration.php">Register Here</a></p></div>
        </div>
    </div>
</body>
</html>