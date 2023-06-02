<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['student_ID'])) {
    header("Location: index.php");
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sheehan_yu_syscbook";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_email = $_POST['student_email'];
    $password = $_POST['password'];

    $sql = "SELECT up.student_ID, up.password, uper.account_type
    FROM users_passwords up
    INNER JOIN users_permissions uper ON up.student_ID = uper.student_ID
    INNER JOIN users_info ui ON up.student_ID = ui.student_ID
    WHERE ui.student_email = ?";

    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $student_email);
    // $stmt->execute();
    // $result = $stmt->get_result();
    $stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Print the prepared SQL statement with the actual email value
$debug_sql = str_replace("?", "'" . $student_email . "'", $sql);
echo "DEBUG - SQL Query: " . $debug_sql . "<br>";


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<pre>";
        print_r($row);
        echo "</pre>";

        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['student_ID'] = $row['student_ID'];
            $_SESSION['role'] = $row['account_type'] == 0 ? 'admin' : 'user';
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Invalid email or password";
        }
    } else {
        $error_message = "No matching record found for the email address";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - SYSCBOOK</title>
    <link rel="stylesheet" href="assets/css/reset.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <header>
        <h1>SYSCBOOK</h1>
        <p>Social media for SYSC students in Carleton University</p>
    </header>
    <main>
        <section>
            <h2>Login</h2>
            <form method="post" action="">
                <fieldset>
                    <legend><p>Login Information</p></legend>
                    <label for="student_email">Email Address:</label>
                    <input type="email" id="student_email" name="student_email" required><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br>
                    <?php if (isset($error_message)) : ?>
                        <span class="error"><?= $error_message ?></span><br>
                    <?php endif; ?>
                    <input type="submit" value="Login">
                </fieldset>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </section>
    </main>
</body>
</html>


