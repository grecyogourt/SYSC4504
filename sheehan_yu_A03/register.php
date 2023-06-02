<?php
session_start();
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

// Check if the form is submitted
if (isset($_POST['submit'])) {
   $first_name = $_POST['first_name'];
   $last_name = $_POST['last_name'];
   $DOB = $_POST['DOB'];
   $student_email = $_POST['student_email'];
   $Program = $_POST['Program'];
   $password = $_POST['password'];
   $hashed_password = password_hash($password, PASSWORD_BCRYPT);

   // Check if the email address exists in the database
   $stmt = $conn->prepare("SELECT student_email FROM users_info WHERE student_email = ?");
   $stmt->bind_param("s", $student_email);
   $stmt->execute();
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
       echo "Error: Email address already exists. Please enter a new email address.";
       $stmt->close();
   } else {
       $stmt->close();

       // Insert data into users_info and users_program tables
       $stmt1 = $conn->prepare("INSERT INTO users_info (student_email, first_name, last_name, DOB) VALUES (?, ?, ?, ?)");
       $stmt1->bind_param("ssss", $student_email, $first_name, $last_name, $DOB);
       $stmt1->execute();

       $student_ID = $stmt1->insert_id;
       $_SESSION['student_ID'] = $student_ID;

       $stmt2 = $conn->prepare("INSERT INTO users_program (student_ID, Program) VALUES (?, ?)");
       $stmt2->bind_param("is", $student_ID, $Program);
       $stmt2->execute();

       $stmt3 = $conn->prepare("INSERT INTO users_avatar (student_ID, avatar) VALUES (?, ?)");
       $stmt3->bind_param("ii", $student_ID, $default_avatar);
       $stmt3->execute();

       $stmt4 = $conn->prepare("INSERT INTO users_address (student_ID, street_number, street_name, city, province, postal_code) VALUES (?, ?, ?, ?, ?, ?)");
       $stmt4->bind_param("iissss", $student_ID, $default_street_number, $default_street_name, $default_city, $default_province, $default_postal_code);
       $stmt4->execute();

       $stmt5 = $conn->prepare("INSERT INTO users_passwords (student_ID, password) VALUES (?, ?)");
       $stmt5->bind_param("is", $student_ID, $hashed_password);
       $stmt5->execute();

       // Insert data into users_permissions table
       $account_type = 1; // Regular user
       $stmt6 = $conn->prepare("INSERT INTO users_permissions (student_ID, account_type) VALUES (?, ?)");
       $stmt6->bind_param("ii", $student_ID, $account_type);
       $stmt6->execute();       

       if ($stmt1 && $stmt2 && $stmt3 && $stmt4 && $stmt5 && $stmt6) {
           header("Location: profile.php?student_id=$student_ID");
       } else {
           echo "Error: Unable to register the user.";
       }

       // Close the prepared statements
       $stmt1->close();
       $stmt2->close();
       $stmt3->close();
       $stmt4->close();
       $stmt5->close();
   }
}
// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head >
   <meta charset="utf-8">
   <title>Register on SYSCBOOK</title>
   <link rel="stylesheet" href="assets/css/reset.css" />
   <link rel="stylesheet" href="assets/css/style.css" />
   <script>
    function checkPasswordsMatch() {
        var password = document.getElementById("password");
        var confirmPassword = document.getElementById("confirm_password");

        if (password.value != confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match.");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }
   </script>
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
         <h2>Register a new profile</h2>
         <form method="post" action="" onsubmit="checkPasswordsMatch();">
            <fieldset>
               <legend><p>Personal information</p></legend>
               <table>
                  <tr>
                     <td>
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name"></td>
                     <td>
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name">
                     </td>
                     <td>
                        <label for="DOB">DOB:</label>
                        <input type="date" id="DOB" name="DOB">
                     </td>
                  </tr>
               </table>
            </fieldset>
            <fieldset>
               <legend><p>Profile Information</p></legend>
               <table>
                  <tr>
                     <td>
                        <label for="student_email">Email Address:</label>
				            <input type="email" id="student_email" name="student_email" required>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <label for="Program">Program:</label>
				            <select id="Program" name="Program">
				            <option value="Choose Program">Choose Program</option>
				            <option value="Computer System Engineering">Computer System Engineering</option>
				            <option value="Software Engineering">Software Engineering</option>
				            <option value="Communication Engineering">Communication Engineering</option>
				            <option value="Biomedical and Electrical">Biomedical and Electrical</option>
				            <option value="Electrical Engineering">Electrical Engineering</option>
				            <option value="Special">Special</option>
				            </select>
                     </td>
                  </tr>
                  </table>
               </fieldset>
               <fieldset>
               <legend><p>Account Information</p></legend>
               <table>
                  <tr>
                     <td>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                     </td>
                     <td>
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required oninput="checkPasswordsMatch();">
                     </td>
                  </tr>
               </table>
            </fieldset>
               <input type="submit" name="submit" value="Register">
               <input type="reset" value="Reset">
         </form>
         <p>Already have an account? <a href="login.php">Login here</a></p>
      </section>
   </main>
</body>
</html>
