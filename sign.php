<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Set your MySQL password if any
$database = "sigindb";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling the form submission based on action (sign-up or sign-in)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_POST['name'])) {
        // Sign-Up logic
        $name = $_POST['name'];
        $hashedPassword = $_POST['password'];

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: main.html"); // Redirect to main page after successful registration
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Sign-In logic
        $sql = "SELECT name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $hashedPassword);
            $stmt->fetch();
            
            if ($password===$hashedPassword){

                $_SESSION['user_name'] = $name;
                header("Location: mainn.html"); // Redirect on successful login
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No account found with that email.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
