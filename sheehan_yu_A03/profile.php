<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['student_ID'])) {
    header("Location: login.php");
    exit;
}

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

// Get user's existing profile data using prepared statements
$student_ID = $_SESSION['student_ID'];
$stmt = $conn->prepare("SELECT users_info.*, users_info.student_email, users_program.Program, users_avatar.avatar, users_address.*
        FROM users_info
        JOIN users_program ON users_info.student_ID = users_program.student_ID
        JOIN users_avatar ON users_info.student_ID = users_avatar.student_ID
        JOIN users_address ON users_info.student_ID = users_address.student_ID
        WHERE users_info.student_ID = ?");
$stmt->bind_param("i", $student_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $DOB = $row['DOB'];
    $Program = $row['Program'];
    $street_number = $row['street_number'];
    $street_name = $row['street_name'];
    $city = $row['city'];
    $province = $row['province'];
    $postal_code = $row['postal_code'];
    $avatar = $row['avatar'];
    $student_email = $row['student_email'];
} 
// Check if the form is submitted
if (isset($_POST['submit'])) {
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$DOB = $_POST['DOB'];
$student_email = $_POST['student_email'];
$Program = $_POST['Program'];
$street_number = $_POST['street_number'];
$street_name = $_POST['street_name'];
$city = $_POST['city'];
$province = $_POST['province'];
$postal_code = $_POST['postal_code'];
$avatar = $_POST['avatar'];

    // Update users_info, users_program, users_avatar, and users_address tables using prepared statements
    $stmt1 = $conn->prepare("UPDATE users_info SET first_name=?, last_name=?, DOB=? WHERE student_ID=?");
    $stmt1->bind_param("sssi", $first_name, $last_name, $DOB, $student_ID);

    $stmt2 = $conn->prepare("UPDATE users_program SET Program=? WHERE student_ID=?");
    $stmt2->bind_param("si", $Program, $student_ID);

    $stmt3 = $conn->prepare("UPDATE users_avatar SET avatar=? WHERE student_ID=?");
    $stmt3->bind_param("ii", $avatar, $student_ID);

    $stmt4 = $conn->prepare("UPDATE users_address SET street_number=?, street_name=?, city=?, province=?, postal_code=? WHERE student_ID=?");
    $stmt4->bind_param("sssssi", $street_number, $street_name, $city, $province, $postal_code, $student_ID);

    if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute() && $stmt4->execute()) {
        header("Location: profile.php");
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    // Close the prepared statements
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $stmt4->close();
}

// Close the prepared statement and the connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head >
   <meta charset="utf-8">
   <title>Update SYSCBOOK profile</title>
   <link rel="stylesheet" href="assets/css/reset.css" />
   <link rel="stylesheet" href="assets/css/style.css" />
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
         <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
         <li><a href="user_list.php">User List</a></li>
         <?php endif; ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Log out</a></li>
        <?php endif; ?>
    </ul>
</nav>

   <main>
      <section>
         <h2>Update Profile information</h2>
         <form method="post" action="">
            <fieldset>
               <legend><p>Personal information</p></legend>
               <table>
                  <tr>
                     <td>
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>">
                     </td>
                     <td>
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>">
                     </td>
                     <td>
                        <label for="DOB">DOB:</label>
                        <input type="date" id="DOB" name="DOB" value="<?php echo $DOB; ?>">
                     </td>
                  </tr>
               </table>
            </fieldset>
            <fieldset>
               <legend><p>Address</p></legend>
               <table>
                  <tr>
                     <td>
                        <label for="street_number">Street Number:</label>
                        <input type="number" id="street_number" min="1" max="9999" name="street_number" value="<?php echo $street_number; ?>">
                     </td>
                     <td>
                        <label for="street_name">Street Name:</label>
                        <input type="text" id="street_name" name="street_name" value="<?php echo $street_name; ?>">
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" value="<?php echo $city; ?>">
                     </td>
                     <td>
                        <label for="province">Province:</label>
                        <input type="text" id="province" name="province" value="<?php echo $province; ?>">
                     </td>
                     <td>
                        <label for="postal_code">Post Code:</label>
                        <input type="text" id="postal_code" name="postal_code" value="<?php echo $postal_code; ?>">
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
                        <input type="email" id="student_email" name="student_email" value="<?php echo $student_email; ?>" required>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <label for="Program">Program:</label>
                        <select id="Program" name="Program">
                        <option value="Choose Program" <?php echo ($Program == "Choose Program") ? "selected" : ""; ?>>Choose Program</option>
                        <option value="Computer System Engineering" <?php echo ($Program == "Computer System Engineering") ? "selected" : ""; ?>>Computer System Engineering</option>
                        <option value="Software Engineering" <?php echo ($Program == "Software Engineering") ? "selected" : ""; ?>>Software Engineering</option>
                        <option value="Communication Engineering" <?php echo ($Program == "Communication Engineering") ? "selected" : ""; ?>>Communication Engineering</option>
                        <option value="Biomedical and Electrical" <?php echo ($Program == "Biomedical and Electrical") ? "selected" : ""; ?>>Biomedical and Electrical</option>
                        <option value="Electrical Engineering" <?php echo ($Program == "Electrical Engineering") ? "selected" : ""; ?>>Electrical Engineering</option>
                        <option value="Special" <?php echo ($Program == "Special") ? "selected" : ""; ?>>Special</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <label for="avatar">Choose Your Avatar:</label>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <input type="radio" id="avatar1" name="avatar" value="1" <?php echo ($avatar == "1") ? "checked" : ""; ?>>
                        <label for="avatar1">
                           <img src="images/img_avatar1.png" alt="Avatar 1" >
                         </label>
                         <input type="radio" id="avatar2" name="avatar" value="2" <?php echo ($avatar == "2") ? "checked" : ""; ?>>
                         <label for="avatar2">
                            <img src="images/img_avatar2.png" alt="Avatar 2">
                          </label>
                          <input type="radio" id="avatar3" name="avatar" value="3" <?php echo ($avatar == "3") ? "checked" : ""; ?>>
                          <label for="avatar3">
                             <img src="images/img_avatar3.png" alt="Avatar 3">
                           </label>
                           <input type="radio" id="avatar4" name="avatar" value="4" <?php echo ($avatar == "4") ? "checked" : ""; ?>>
                           <label for="avatar4">
                              <img src="images/img_avatar4.png" alt="Avatar 4">
                            </label>
                            <input type="radio" id="avatar5" name="avatar" value="5" <?php echo ($avatar == "5") ? "checked" : ""; ?>>
                            <label for="avatar5">
                               <img src="images/img_avatar5.png" alt="Avatar 5">
                             </label>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <input type="submit" name="submit" value="Submit">
                        <input type="reset" value="Reset">
                     </td>
                  </tr>
               </table>
            </fieldset>
         </form>
      </section>
   </main>

</body>
</html>




