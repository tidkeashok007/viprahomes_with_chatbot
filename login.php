<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $host = "localhost";
    $port = 3307;
    $dbname = "vipra";
    $username = "root";
    $db_password = "";

    $con = mysqli_connect($host, $username, $db_password, $dbname, $port);

    if (!$con) {
        die("Connection failed - " . mysqli_connect_error());
    }

    // Use prepared statement for select
    $stmt = $con->prepare("SELECT * FROM register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user_data = $result->fetch_assoc();

        if (password_verify($password, $user_data["password"])) {
            $_SESSION["role"] = $user_data["role"];
            if ($_SESSION["role"] === "user") {
                header("Location: userdashboard.html");
                exit();
            } elseif ($_SESSION["role"] === "admin") {
                header("Location: admindashboard.html");
                exit();
            }
        } else {
            echo '<script>alert("Email or password is incorrect"); window.location.href = "login.html";</script>';
        }
    } else {
        echo '<script>alert("Email or password is incorrect"); window.location.href = "login.html";</script>';
    }

    $stmt->close();
    mysqli_close($con);
}
?>
