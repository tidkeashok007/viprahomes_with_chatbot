<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($password_confirmation)) {
        echo "All fields are required.";
    } elseif ($password !== $password_confirmation) {
        echo "Passwords do not match.";
    } else {
        // Database connection
        $host = "localhost";
        $port = 3307;
        $dbname = "vipra";
        $username = "root";
        $db_password = "";

        $con = mysqli_connect($host, $username, $db_password, $dbname, $port);

        if (!$con) {
            die("Connection failed - " . mysqli_connect_error());
        }

        // Use prepared statement for insert
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = "user";
        
        $stmt = $con->prepare("INSERT INTO register (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "
                <script>
                    alert('Registration successful! Now you can log in.');
                    window.location.href = 'login.html'; // Replace with your actual login page URL
                </script>
            ";
        } else {
            echo "Error occurred during registration: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($con);
    }
}
?>
