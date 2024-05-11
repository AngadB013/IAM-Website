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

if (isset($_POST['id']))  {
    $patient_id = $_POST['id']; // Assuming the form submits the patient_id

    // Delete from database
    $sql = "DELETE FROM patient_documents WHERE patient_id = $patient_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Retrieve document filename from database
        $documents_query = "SELECT * FROM patient_documents";
        $documents_result = mysqli_query($conn, $documents_query);

        if ($documents_result) {
            if (mysqli_num_rows($documents_result) > 0) {
                $row = mysqli_fetch_assoc($documents_result);
                $document_filename = $row['filename'];

                // Construct the complete file path
                $filepath = "uploads/" . $document_filename;

                // Delete from folder
                if (file_exists($filepath)) {
                    unlink($filepath);
                }

                $success_message = "Document deleted successfully!";
            } else {
                $error_message = "Error fetching filename for deletion.";
            }
        } else {
            $error_message = "Error fetching filename: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Error deleting document: " . mysqli_error($conn);
    }

    // Close connection
    
    mysqli_close($conn);
}
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

.container {
            margin-left: 250px; /* Adjust left margin */
        }

        .document-table {
            margin: 20px; /* Add margin */
            border-collapse: collapse; /* Collapse table borders */
            width: calc(100% - 40px); /* Adjust width to fit the empty space */
            font-family: 'Roboto', sans-serif; /* Use Roboto font */
            background-color: #ffffff; /* Background color */
        }

        .document-table th,
        .document-table td {
            border: 1px solid #ddd; /* Add border */
            padding: 12px; /* Add padding */
            text-align: left; /* Align text left */
            font-size: 14px; /* Font size */
            color: #666666; /* Text color */
        }

        .document-table th {
            background-color: #f2f2f2; /* Add background color to header */
            font-weight: bold; /* Font weight */
        }

        .document-table tbody tr:hover {
            background-color: #f2f2f2; /* Background color on hover */
        }

        .delete-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
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
                        <li><a href="delete_document.php">Delete Patient Medical Records</a></li>
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

    <!-- Container for document data -->
    <div class="container">
        <h2>Delete Document</h2>
        <table class="document-table">
            <thead>
                <tr>
                    <th>No.</th> <!-- Number column -->
                    <th>Document Name</th>
                    <th>Uploaded By</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                                                                // Retrieve documents for deletion
                                    $documents_query = "SELECT * FROM patient_documents";
                                    $documents_result = mysqli_query($conn, $documents_query);

                                    if ($documents_result) {
                                        // Query executed successfully
                                        if (mysqli_num_rows($documents_result) > 0) {
                                            $counter = 1; // Initialize counter
                                            while ($row = mysqli_fetch_assoc($documents_result)) {
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>"; // Number column
                                    echo "<td>" . $row['document_name'] . "</td>";
                                    echo "<td>" . $row['uploaded_by'] . "</td>";
                                    echo "<td>" . $row['uploaded_at'] . "</td>";
                                
                                    // Add a delete button with confirmation link and styled button
                                    echo "<td><form action='delete_document.php' method='post' onsubmit='return confirmDelete()'>
                                        <input type='hidden' name='id' value='" . $row['patient_id'] . "'>
                                        <button type='submit' class='delete-button'>Delete</button>
                                        </form></td>";
                                    echo "</tr>";
                                    $counter++; // Increment counter
                                }
                            } else {
                                echo "<tr><td colspan='5'>No documents found</td></tr>";
                            } 
                            }   else {
                                die("Query failed: " . mysqli_error($conn)); // Display error if query fails
                            }
                      ?>
                      
                      <script>
                        function confirmDelete() {
                            return confirm("Are you sure you want to delete this document?");
                            }
                      </script>
                      
                </tbody>
        </table>
    </div>

</body>
</html>