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
    <!-- Navigation Bar -->
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

    <section class="container my-5">
        <h2 class="text-center">Government Offices</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Office ID</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT OfficeID, Name, State, City, Street, Phone, Email FROM government_office";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['OfficeID']}</td>
                                <td>{$row['Name']}</td>
                                <td>{$row['State']}</td>
                                <td>{$row['City']}</td>
                                <td>{$row['Street']}</td>
                                <td>{$row['Phone']}</td>
                                <td>{$row['Email']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>