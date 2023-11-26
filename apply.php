<?php
require_once 'config.php';

function applyForJob($db, $jobId, $name, $email, $resumePath) {
    $sql = "INSERT INTO Applications (job_id, Name, Email, resume_path) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    // Check for errors in the prepared statement
    if (!$stmt) {
        die("Error in statement preparation: " . $db->error);
    }

    $stmt->bind_param("isss", $jobId, $name, $email, $resumePath);

    // Check for errors in the binding process
    if (!$stmt) {
        die("Error in statement binding: " . $db->error);
    }

    // Execute the statement
    if ($stmt->execute()) {
        return true;
    } else {
        die("Error in statement execution: " . $stmt->error);
    }
}
$jobId = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = $_POST['jobId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $resumePath = '';

    if ($_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="ui negative message">Error uploading file. Error code: ' . $_FILES['resume']['error'] . '</div>';
        exit();
    }
    
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'resumes/';
        $allowedExtensions = ['pdf', 'doc', 'docx'];
    
        $resumeName = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    
        // Print debugging information
        // echo 'File Name: ' . $resumeName . '<br>';
        // echo 'Destination Path: ' . $uploadDir . '<br>';
    
        if (in_array($resumeName, $allowedExtensions)) {
            $resumePath = $uploadDir . uniqid() . '.' . $resumeName;
            echo 'Final Path: ' . $resumePath . '<br>';
    
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)) {
                echo 'File successfully moved.<br>';
            } else {
                echo 'Error moving file.<br>';
            }
        } else {
            echo '<div class="ui negative message">Error: Invalid file format.</div>';
            exit();
        }
    }

    if (applyForJob($db, $jobId, $name, $email, $resumePath)) {
        echo '<div class="ui positive message">Application submitted successfully.</div>';
    } else {
        echo '<div class="ui negative message">Error: Application could not be submitted.</div>';
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>JobHub</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js" integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css" integrity="sha512-KXol4x3sVoO+8ZsWPFI/r5KBVB/ssCGB5tsv2nVOKwLg33wTFP3fmnXa47FdSVIshVTgsYk/1734xSk9aFIa4A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #f4f4f4;
        }

        .ui.form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="ui custom-navbar menu">
        <div class="ui container">
            <a class="header item" href="./">JobHub</a>
            <a class="item" href="./">Home</a>
            <a class="item" href="#">Contact</a>

            <div class="right menu">
                <div class="item">
                    <div class="ui buttons">
                        <a href="./registration.php" class="ui positive button">Register</a>
                        <div class="or"></div>
                        <a href="./login.php" class="ui primary button">login</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="ui container">
        <form method="post" enctype="multipart/form-data" class="ui form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">
            <div class="field">
                <input type="text" name="name" placeholder="Your Name">
            </div>
            <div class="field">
                <input type="email" name="email" placeholder="Your Email"><br>
            </div>

            <div class="field">
                <label for="resume">Upload Resume (PDF only)</label>
                <input type="file" name="resume" id="resume" accept=".pdf">
            </div><br>

            <input type="submit" class="ui primary right floated button" value="Apply">
        </form>

    </div>
</body>

</html>