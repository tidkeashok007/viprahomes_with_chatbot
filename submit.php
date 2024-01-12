<?php
if (isset($_POST['submit'])) {
    // Fetching data from the HTML form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $msg = $_POST["msg"];

    // Database details
    $host = "localhost";
    $port = 3307;  // Use your actual port number here
    $username = "root";
    $password = "";
    $dbname = "vipra";

    // Create connection
    $con = mysqli_connect($host, $username, $password, $dbname, $port);

    // Check connection
    if (!$con) {
        die("Connection failed - " . mysqli_connect_error());
    }

    // Inserting data using prepared statement
    $sql = "INSERT INTO contacts (name, email, msg) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $msg);

        if (mysqli_stmt_execute($stmt)) {
            // Display a pop-up message using JavaScript and redirect to the home page
            echo "<script>
                    alert('Your message has been sent!');
                    window.location.href = 'index.html'; // Replace 'home_page.php' with your actual home page URL
                  </script>";
        } else {
            echo "Error occurred during data insertion: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in prepared statement: " . mysqli_error($con);
    }

    // Close the connection
    mysqli_close($con);
}
?>
