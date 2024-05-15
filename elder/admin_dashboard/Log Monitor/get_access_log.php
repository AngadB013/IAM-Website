<?php
// Define the path to the error log file
$errorLogPath = '/XAMPP/apache/logs/access.log'; // Adjust the path as per your setup

// Check if the file exists
if (file_exists($errorLogPath)) {
    // Read the contents of the error log file
    $errorLogContents = file_get_contents($errorLogPath);
    
    // Output the contents of the error log file
    echo $errorLogContents;
} else {
    echo "Error log file not found.";
}
?>
