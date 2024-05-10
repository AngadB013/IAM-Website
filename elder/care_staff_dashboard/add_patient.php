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

// Initialize variables to store form data
$first_name = $last_name = $dob = $phone = $address = $suburb = $state = $postcode = $medical_record = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $suburb = mysqli_real_escape_string($conn, $_POST['suburb']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $postcode = mysqli_real_escape_string($conn, $_POST['postcode']);
    $medical_issue = mysqli_real_escape_string($conn, $_POST['medical_issue']);
    $medical_details = mysqli_real_escape_string($conn, $_POST['medical_details']);
    $medical_record = $medical_issue . ": " . $medical_details;

    // Insert patient data into the database
    $sql = "INSERT INTO patient (first_name, last_name, dob, phone, address, suburb, state, postcode, medical_record)
            VALUES ('$first_name', '$last_name', '$dob', '$phone', '$address', '$suburb', '$state', '$postcode', '$medical_record')";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Patient added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
    .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
/* Form container */
.form-group {
    width: 100%;
    max-width: 800px; /* Adjust width as needed */
    margin: 0 auto; /* Center the form */
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-top: 10px;
}

/* Form input fields */
.form-group input[type="text"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Form select dropdown */
.form-group select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23333333' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1024 1024'><path d='M64 224h896l-448 448-448-448z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

/* Form textarea */
.form-group textarea {
    resize: vertical;
}

/* Submit button */
button[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #45a049;
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
                <li><a href="medical_dashboard.php">Remove Patients</a></li>
                <li><a href="patient_records.php">Patient Records</a>
                    <ul class="sub-menu">
                        <li><a href=".php">Upload Patient Medical Record</a></li>
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

    <div class="container">
        <h2>Add Patient</h2>
            <!-- Success message -->
            <?php if (isset($success_message)) : ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
    <!-- Add Patient Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth (YYYY-MM-DD):</label>
            <input type="text" id="dob" name="dob" pattern="\d{4}-\d{2}-\d{2}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="suburb">Suburb:</label>
            <input type="text" id="suburb" name="suburb" required>
        </div>
        <div class="form-group">
            <label for="state">State:</label>
            <select id="state" name="state" required>
                <option value="" selected disabled>Select State</option>
                <option value="ACT">Australian Capital Territory</option>
                <option value="NSW">New South Wales</option>
                <option value="NT">Northern Territory</option>
                <option value="QLD">Queensland</option>
                <option value="SA">South Australia</option>
                <option value="TAS">Tasmania</option>
                <option value="VIC">Victoria</option>
                <option value="WA">Western Australia</option>
            </select>
        </div>
        <div class="form-group">
            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" required>
        </div>
        <div class="form-group">
            <label for="medical_issue">Medical Issue:</label>
            <input type="text" id="medical_issue" name="medical_issue">
        </div>
        <div class="form-group">
            <label for="medical_details">Medical Details:</label>
            <input type="text" id="medical_details" name="medical_details">
        </div>
        <button type="submit">Submit</button>
    </form>
    </div>
    
    <script>
    function validateForm() {
        var medicalIssue = document.getElementById("medical_issue").value.trim();
        var medicalDetails = document.getElementById("medical_details").value.trim();

        if (medicalIssue === "" || medicalDetails === "") {
            alert("Please fill in both Medical Issue and Medical Details fields.");
            return false;
        }

        return true;
    }
    </script>
</body>
</html>