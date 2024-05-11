<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login/carestafflogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "aged_care_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user details
$email = $_SESSION['email'];
$sql = "SELECT department, position FROM carestaff WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

// Check if query was successful
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $department = $row['department'];
    $position = $row['position'];
} else {
    $department = $position = "Unknown"; // Default values if not found
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST["patient_id"];
    $document_name = $_FILES["document"]["name"];
    $document_tmp = $_FILES["document"]["tmp_name"];
    $document_type = $_FILES["document"]["type"];
    $document_size = $_FILES["document"]["size"];
    $uploaded_by = $_SESSION["email"];

    // Move the uploaded file to the desired location
    $uploads_dir = "uploads/";
    $document_path = $uploads_dir . basename($document_name);
    if (move_uploaded_file($document_tmp, $document_path)) {
        // Insert the document details into the database
        $sql = "INSERT INTO patient_documents (patient_id, document_name, document_path, document_type, document_size, uploaded_by, uploaded_at) VALUES ('$patient_id', '$document_name', '$document_path', '$document_type', '$document_size', '$uploaded_by', NOW())";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Document uploaded successfully.";
        } else {
            $error_message = "Error uploading document: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Error moving the uploaded file.";
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css"/>
    <link rel="stylesheet" href="leftbar.css"/>
</head>
<style>
.dashboard {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.card {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 800px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.card:hover {
    background-color: #f2f2f2;
}

.card a {
    text-decoration: none; /* Remove underline */
    color: inherit; /* Inherit text color */
}

    /* Styles for the container */
    .container {
        margin-left: 250px; /* Adjust left margin */
    }
</style>
<body>

    <header>
        <div class="logo-container">
            <img src="../admin_dashboard/logo1.png" alt="Logo" class="logo">
            <h1 style="font-family: Luckiest Guy, cursive;">IAM System</h1>
        </div>
        <div class="search-container">
             <!-- Notification icon -->
             <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
            </div>
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search">
            </div>
            <div class="user-info">
                <div class="user-details" id="userDropdown">
                    <span class="user-circle"><?php echo getUserInitials($_SESSION['email']); ?></span>
                    <div class="user-email"><?php echo $_SESSION['email']; ?></div>
                </div>
                <div class="user-position"><?php echo $position; ?></div> <!-- Display position here -->
                <!-- Dropdown menu -->
                <div class="dropdown" id="dropdownMenu">
                    <a href="index.html">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <?php
    function getUserInitials($email) {
        $name_parts = explode('@', $email);
        $initials = '';
        foreach (explode('.', $name_parts[0]) as $part) {
            $initials .= strtoupper($part[0]);
        }
        return $initials;
    }
    ?>

    <script>
        document.getElementById("userDropdown").addEventListener("click", function() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });
    </script>

    <!-- Left navbar -->
    <div class="navbar">
    <nav>
        <ul>
            <?php if ($department === "Medical"): ?>
                <li><a href="medical_dashboard.php">Dashboard</a></li>
                <li><a href="all_patient.php">All Patients</a></li>
                <li><a href="add_patient.php">Add Patients</a></li>
                <li><a href="remove.patient.php">Remove Patients</a></li>
                <li><a href="patient_records.php">Patient Records</a>
                    <ul class="sub-menu">
                        <li><a href="patient_documents.php">Download Patient Medical Records</a></li>
                        <li><a href="upload_patient_documents.php">Upload Patient Medical Records</a></li>
                    </ul>
                </li>
                <li><a href="#">Settings</a></li>
                <!-- Add more medical-specific links here -->
            <?php elseif ($department === "Finance"): ?>
                <li><a href="finance_dashboard.php">Finance Dashboard</a></li>
                <li><a href="financial_reports.php">Financial Reports</a></li>
                <!-- Add more finance-specific links here -->
            <?php else: ?>
                <!-- Default links for other departments -->
                <li><a href="#">General Dashboard</a></li>
                <!-- Add more default links here -->
            <?php endif; ?>
        </ul>
    </nav>
    </div>

    <!-- Container for user data -->
    <div class="container">
        <h2>Upload Patient Document</h2>
        <?php
        if (isset($success_message)) {
            echo "<p style='color: green;'>" . $success_message . "</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>" . $error_message . "</p>";
        }
        ?>
        <form method="post" enctype="multipart/form-data" id="uploadForm">
            <div>
                <label for="patient_id">Patient ID:</label>
                <input type="text" id="patient_id" name="patient_id" required oninput="checkInputs()">
            </div>
            <div>
                <label for="document">Document:</label>
                <input type="file" id="document" name="document" required oninput="checkInputs()">
            </div>
            <button type="submit" id="uploadBtn" disabled>Upload Document</button>
        </form>
    </div>

<script>
    function checkInputs() {
        var patientId = document.getElementById("patient_id").value;
        var documentFile = document.getElementById("document").value;

        // Enable button if both fields have values, otherwise disable it
        var uploadBtn = document.getElementById("uploadBtn");
        if (patientId.trim() !== "" && documentFile.trim() !== "") {
            uploadBtn.disabled = false;
        } else {
            uploadBtn.disabled = true;
        }
    }
</script>

</body>
</html>