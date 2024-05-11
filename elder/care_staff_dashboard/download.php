<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "aged_care_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if document_id is set
if(isset($_GET['document_id'])) {
    $document_id = $_GET['document_id'];
    
    // Retrieve document details from the database
    $sql = "SELECT * FROM patient_documents WHERE document_id = $document_id";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Define the path to the file
        $file_path = $row['document_path'];
        $file_name = $row['document_name'];
        
        // Set headers for file download
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$file_name");
        
        // Read the file and output its content
        readfile($file_path);
        exit;
    } else {
        echo "Document not found";
    }
}

// Close connection
mysqli_close($conn);
?>