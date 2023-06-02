<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['student_ID'])) {
   header("Location: login.php");
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>SYSCBOOK - Main</title>
   <link rel="stylesheet" href="assets/css/reset.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
   <header>
      <h1>SYSCBOOK</h1>
      <p>Social media for SYSC students in Carleton University</p>
   </header>
   <nav id="navBarLeft">
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (!isset($_SESSION['student_ID'])): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php else: ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="user_list.php">User List</a></li>
            <?php endif; ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Log out</a></li>
        <?php endif; ?>
    </ul>
</nav>

   <main>
      <section>
         <h2>New Post</h2>
         <form method="post" action="">
            <fieldset>
               <legend>New Post</legend>
               <p>
                  <textarea name="new_post" rows="5" required></textarea>
               </p>
               <p>
               <input type="submit" name="submit_post" value="Post">
                  <input type="reset" value="Reset">
               </p>
            </fieldset>
         </form>
      </section>
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sheehan_yu_syscbook";

$conn = new mysqli($servername, $username, $password, $dbname);



// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Insert new post
if (isset($_POST['submit_post'])) {
    $new_post = $_POST['new_post'];
    $student_ID = $_SESSION['student_ID'];
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users_posts (student_ID, new_post) VALUES (?, ?)");
    $stmt->bind_param("is", $student_ID, $new_post);
    
    // Execute the prepared statement
    $stmt->execute();
    
    // Refresh the page to display the updated list of posts
    header("Location: index.php");
    exit();
}

// Display last 5 posts using prepared statements
$stmt = $conn->prepare("SELECT new_post FROM users_posts ORDER BY post_date DESC LIMIT 10");
$stmt->execute();
$stmt->bind_result($new_post);

while ($stmt->fetch()) {
    echo "<div><details open><summary>POST</summary><p>{$new_post}</p></details></div>";
}

// Close the prepared statement and the connection
$stmt->close();
$conn->close();
?>
   </main>
</body>
</html>



