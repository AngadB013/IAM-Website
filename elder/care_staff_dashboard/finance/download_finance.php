<?php
// Get the document ID from the URL
if(isset($_GET['document_id'])) {
    $document_id = $_GET['document_id'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "aged_care_db");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve file details from the database
    $sql = "SELECT * FROM finance_documents WHERE document_id = '$document_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the document details
        $row = mysqli_fetch_assoc($result);
        $file_path = "../upload/finance/" . $row['file_name'];
        $file_name = $row['document_name'];
        $file_type = $row['file_type'];
        $file_size = $row['file_size'];

        // Send the file to the browser for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
        readfile($file_path);
        exit;
    } else {
        echo "File not found.";
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>