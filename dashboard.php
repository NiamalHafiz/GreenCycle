<?php
include "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading, Packaging & Transport Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
   
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">GreenCycle</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h1>Welcome to GreenCycle</h1>
        <h1>Grading, Packaging, Transport - Simplified, Sustained, and Streamlined</h1>
    </section>

    <section class="dashboard">
        <div class="container">
            <h2>Dashboard</h2>
            <div class="card-container">
                <a href="governmentoffices.php" class="card">
                    <h3>Government Offices</h3>
                    <p>Manage government office data</p>
                </a>
                <a href="agriculturalofficers.php" class="card">
                    <h3>Agricultural Officers</h3>
                    <p>View officer details</p>
                </a>
                <a href="packagedbatch.php" class="card">
                    <h3>Packaged Batch</h3>
                    <p>Manage batch information</p>
                </a>
                <a href="warehouse.php" class="card">
                    <h3>Warehouse</h3>
                    <p>View warehouse capacity and details</p>
                </a>
                <a href="transport.php" class="card">
                    <h3>Transport</h3>
                    <p>Transports packaged batch to market</p>
                </a>
                <a href="packagedproduct.php" class="card">
                    <h3>Packaged Product</h3>
                    <p>Packages produts</p>
                </a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>