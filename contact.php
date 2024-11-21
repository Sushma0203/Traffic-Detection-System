<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Update if necessary
$database = "contact_db";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data with null coalescing operator
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$message = $_POST['message'] ?? null;

// Check for empty fields
if (!$name || !$email || !$message) {
    die("Please fill out all fields.");
}

// Prepare and execute SQL statement
$stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    // Display a "Thank you" message and redirect after 3 seconds
    echo "<p>Thank you! We will contact you soon.</p>";
    echo "<script>
            setTimeout(function() {
                window.location.href = 'main.html';
            }, 3000); // 3 seconds delay
          </script>";
    exit(); // Stop further script execution after redirection
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
