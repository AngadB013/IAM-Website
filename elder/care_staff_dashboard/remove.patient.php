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

    // Retrieve list of patients
    $sql = "SELECT * FROM patient";
    $result = mysqli_query($conn, $sql);

    // Check if the delete button is clicked
    if (isset($_POST['delete'])) {
        // Get the patient ID from the form
        $patient_id = $_POST['patient_id'];

        // SQL query to delete the patient
        $sql = "DELETE FROM patient WHERE patient_id = '$patient_id'";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            $success_message = "Patient deleted successfully.";
        } else {
            $error_message = "Error deleting patient: " . mysqli_error($conn);
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

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this patient?");
        }
    </script>

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

    /* Styles for the heading */
    .heading {
        font-size: 24px;
        margin-bottom: 10px;
        margin-top: 50px;
        display:flex;
        justify-content: space-between; /* Align items to the left and right */
        align-items: center; /* Align items vertically */
    }

    /* Styles for the total users */
    .total-users {
        font-size: 14px;
        color: #888;
        margin-bottom: 10px;
    }

    /* Styles for the search bar */
    .search-bar1 {
        margin-left: auto; /* Push to the furthest right */
    }

    .search-bar1 input[type="text"] {
        padding: 8px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Styles for the line */
    .line {
        width: calc(100% - 100px); /* Adjust width */
        margin-top: 20px;
        border-bottom: 4px solid #ddd;
        margin-left: 50px; /* Adjust left margin */
    }

    .sub-text{
        color:grey;
        font-size: 12px;
    }

    /* Styles for the table */
    .user-table {
        margin: 20px 50px; /* Add some margin and move the table right */
        border-collapse: collapse; /* Collapse table borders */
        width: calc(100% - 450px); /* Adjust width to fit the empty space */
        font-family: 'Roboto', sans-serif; /* Use Roboto font */
        background-color: #ffffff; /* Background color */
    }

    /* Styles for table header */
    .user-table th {
        background-color: #f2f2f2; /* Add background color to header */
        border: 1px solid #ddd; /* Add border */
        padding: 12px; /* Add padding */
        text-align: left; /* Align text left */
        font-size: 16px; /* Font size */
        font-weight: bold; /* Font weight */
        color: #333333; /* Text color */
    }

    /* Styles for table data */
    .user-table td {
        border: 1px solid #ddd; /* Add border */
        padding: 12px; /* Add padding */
        text-align: left; /* Align text left */
        font-size: 14px; /* Font size */
        color: #666666; /* Text color */
    }

    /* Styles for table row on hover */
    .user-table tbody tr:hover {
        background-color: #f2f2f2; /* Background color */
    }

    /* Styles for no data message */
    .user-table td[colspan='6'] {
        text-align: center; /* Align text center */
        padding: 20px; /* Add padding */
        font-size: 16px; /* Font size */
        color: #666666; /* Text color */
    }

    /* Styles for delete button */
.delete-btn {
    background-color: #ff4d4d; /* Red background color */
    color: #fff; /* White text color */
    border: none; /* Remove border */
    padding: 8px 16px; /* Add padding */
    border-radius: 4px; /* Add border radius */
    cursor: pointer; /* Add cursor pointer */
    transition: background-color 0.3s ease; /* Add transition effect */
}

.delete-btn:hover {
    background-color: #e60000; /* Darker red color on hover */
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

    <!-- Container for user data -->
    <div class="container">
        <!-- Heading with total users -->
        <h2 class="heading">Patients</h2>
        <p class="sub-text">Once deleted, patient information cannot be retrieved!!</p>
        <!-- Search bar -->
        <div class="search-bar1">
            <input type="text" placeholder="Search by name">
        </div>
        <!-- Line -->
        <div class="line"></div>

        <!-- User data table -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>No.</th> <!-- Number column -->
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone No.</th>
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Action</th> <!-- New column for delete button -->
                </tr>
            </thead>
            <tbody>
                <?php

                    // Check if query was successful
                    if ($result && mysqli_num_rows($result) > 0) {
                        $counter = 1; // Initialize counter
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>"; // Number column
                            echo "<td>" . $row["first_name"] . "</td>";
                            echo "<td>" . $row["last_name"] . "</td>";
                            echo "<td>" . $row["phone"] . "</td>";
                            echo "<td>" . $row["suburb"] . "</td>";
                            echo "<td>" . $row["state"] . "</td>";
                            // Add delete button
                            echo "<td>";
                            echo "<form method='post' action='remove.patient.php' onsubmit='return confirmDelete()'>";
                            echo "<input type='hidden' name='patient_id' value='" . $row['patient_id'] . "'>";
                            echo "<button type='submit' name='delete' class='delete-btn'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            $counter++; // Increment counter
                        }
                    } else {
                        echo "<tr><td colspan='7'>No patient found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <?php
        // Display success or error message if set
        if (isset($success_message)) {
            echo "<script>alert('$success_message');</script>";
        } elseif (isset($error_message)) {
            echo "<script>alert('$error_message');</script>";
        }
    ?>

</body>
</html>