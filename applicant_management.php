<?php
// Include your database connection code here (e.g., config.php)
require_once 'config.php';

session_start();

// Check if the user is logged in and has the 'company' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php"); // Redirect to the login page if not logged in or not a company
    exit();
}

// Get the job ID from the query string or another source (replace with your implementation)
$job_id = $_GET['job_id']; // Replace with your code to retrieve the job ID

// Fetch job posting details (replace with your SQL query)
$sql = "SELECT * FROM jobs WHERE job_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

// Fetch applicants for the specific job posting (replace with your SQL query)
$sql = "SELECT * FROM applications WHERE job_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$applicants = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Management</title>
    <!-- Include your CSS stylesheets and JavaScript libraries here -->
</head>
<body>
    <h1>Applicant Management for Job: <?php echo $job['title']; ?></h1>

    <h2>Applicants</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($applicant = $applicants->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $applicant["name"] . '</td>';
                echo '<td>' . $applicant["email"] . '</td>';
                echo '<td>';
                echo '<a href="view_resume.php?application_id=' . $applicant["application_id"] . '">View Resume</a>';
                // Add more actions if needed, such as selecting applicants for interviews
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Include your HTML/CSS for styling here -->
</body>
</html>
