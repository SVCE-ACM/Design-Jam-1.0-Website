<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "svcehost_DJ2.0";
$flag=true;
$id = "\"".$_POST['gh_id']."\"";
$name = "\"".$_POST['gh_fname']."\"";
$status = "\"".$_POST['status']."\"";
$email = "\"".$_POST['email']."\"";
//$id, $name, $status, $email
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$check_sql = "select count(1) as \"check\" from registration where email=".$email.";";
//$check_stmt = $conn->prepare($check_sql);
//$check_stmt->execute();
$result = $conn->query($check_sql);
foreach ($result as $row) {
    if ($row["check"]==0) {
        $flag=false;
    }
}
if ($flag) {
    $submit_sql = "INSERT INTO `submission` (`gh_id`, `gh_name`, `status`, `email`) VALUES (".$id.",".$name.",".$status.",".$email.");";
    $submit_stmt = $conn->prepare($submit_sql);
    $submit_stmt->execute();
    $conn->close();
    //header('Location: confirmation.html');
}
else {
    // If user not registered
    $conn->close();
    //header('Location: noreg.html');
}
?>
