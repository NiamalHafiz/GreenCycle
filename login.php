<?php
include "connection.php";
session_start();

if (isset($_POST['id']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['id']); 
    $password = validate($_POST['password']);

    
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

         
        if (password_verify($password, $row['password'])) {
            $_SESSION['name'] = $row['firstname'] . " " . $row['lastname'];
            $_SESSION['username'] = $row['username']; 
            $_SESSION['user_type'] = $row['user_type']; 

          
            switch ($row['user_type']) {
                case 'Warehouse Staff':
                    header("Location: warehouse_dashboard.php");
                    exit();
                case 'Agricultural Officer':
                    header("Location: officer_dashboard.php");
                    exit();
                case 'Admin':
                    header("Location: admin_dashboard.php");
                    exit();
                default:
                    echo "User type not recognized.";
                    break;
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Grading, Packaging & Transport Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Sign In to Grading, Packaging & Transport Management System</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" name="id" required>
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <div class="row align-items-center remember">
                        <input type="checkbox" name="remember_me">Remember Me
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn float-right login_btn">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Don't have an account? <a href="signup.php">Sign Up</a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="forgot_password.php">Forgot your password?</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
