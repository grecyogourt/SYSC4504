<?php
session_start();

if (!isset($_SESSION['student_ID']) || $_SESSION['role'] != 'admin') {
    echo "Permission denied. <br>";
    echo "<a href='index.php'>Go to Home</a>";
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

$sql = "SELECT u.student_ID, u.first_name, u.last_name, u.student_email, p.Program
        FROM users_info u
        INNER JOIN users_program p ON u.student_ID = p.student_ID";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User List - SYSCBOOK</title>
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
            <h2>User List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Program</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['student_ID'] ?></td>
                            <td><?= $row['first_name'] ?></td>
                            <td><?= $row['last_name'] ?></td>
                            <td><?= $row['student_email'] ?></td>
                            <td><?= $row['Program'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

