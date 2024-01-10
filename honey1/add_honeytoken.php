<?php
session_start(); 

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$database = "major";

$conn = new mysqli($servername, $username, $password, $database);

$No_of_honeytoken=1;
$MailToDelete="";
$error="";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(!empty($_POST["emailToDelete"]))
    {
        $emailDelete = $_POST["emailToDelete"];
        $query = "SELECT COUNT(*) AS total FROM test_user WHERE email = '$emailDelete'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $totalCount = $row['total'];
        if ($totalCount>0)
        {   
            $MailToDelete = $_POST["emailToDelete"];
            $output = exec("python delete.py $MailToDelete ");
        }
        else
        {
            $error = "Please enter a valid email id.";
        }
    }
    else if($_POST["No_of_honeytoken"]!=0)
    {
        $No_of_honeytoken = $_POST["No_of_honeytoken"];
        $output = exec("python GAN.py $No_of_honeytoken ");
    }
    else
    {
        $error = "Please enter a valid email id.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    
    <div class="wrapper" style = " margin : auto ">

        <?php 
        if(!empty($error)){
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Number of Honeytokens</label>
                <input type="number" name="No_of_honeytoken" >
            </div>    
            <div class="form-group">
            <label> Delete a Honeytoken</label>
            <br>
            <input type="email" id="email" name="emailToDelete"  placeholder="Enter email address">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Back to <a href="welcome.php">Home</a>.</p>
    </form>
    </div>
</body>
</html>