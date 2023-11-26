<?php
require_once 'config.php';

$username = $email = $password = $confirmPassword = '';
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $username = cleanInput($_POST["username"]);
    $email = cleanInput($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = 'Username can only contain letters, numbers, and underscores';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters long';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $companyName = $username;

        // Insert company data into the Users table
        $sql = "INSERT INTO Users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $companyName, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            // Get the user_id of the newly registered company
            $companyId = $stmt->insert_id;
            $stmt->close();

            // Insert company profile data into the CompanyProfiles table
            $sql = "INSERT INTO CompanyProfiles (user_id, company_name) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("is", $companyId, $companyName);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errors['registration'] = 'Registration failed. Please try again later.';
            }
        } else {
            $errors['registration'] = 'Registration failed. Please try again later.';
        }

        $stmt->close();
    }
}

function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
    
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
                        <a href="./registration.php" class="ui active button">Register</a>
                        <div class="or"></div>
                        <a href="./login.php" class="ui primary button">login</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="ui container">
        <!-- <h1>User Registration</h1> -->

        <form method="POST" class="ui form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="role">Role:</label>
                <select name="role" id="role">
                    <option value="job_seeker">Job Seeker</option>
                    <option value="company">Company</option>
                </select>
            </div>

            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $errors['username']; ?></span>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $errors['email']; ?></span>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
                <span class="error"><?php echo $errors['password']; ?></span>
            </div>

            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <span class="error"><?php echo $errors['confirm_password']; ?></span>
            </div><br>

            <input type="submit" class="ui primary right floated button" value="Register">
        </form>

        <p class="error"><?php echo $errors['registration']; ?></p>
    </div>
</body>

</html>