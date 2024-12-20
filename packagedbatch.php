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
        <h1> Grading, Packaging, Transport - Simplified, Sustained, and Streamlined</h1>
    </section>

    <section class="tables-section">
        <div class="container" id="packaged-batch">
            <h2>Packaged Batch</h2>
            <?php
            $sql = "SELECT * FROM packaged_batch";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "<div class='alert alert-danger'>Error in SQL query: " . $conn->error . "</div>";
            } elseif ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Barcode</th><th>Batch Name</th><th>Quality</th><th>Grade Category</th><th>Grade Score</th><th>Grade Criteria</th><th>Production Date</th><th>Expiry Date</th><th>Warehouse ID</th><th>Product ID</th><th>Officer ID</th><th>Actions</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["Barcode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["BatchName"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Quality"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["GradeCategory"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["GradeScore"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["GradeCriteria"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ProductionDate"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ExpiryDate"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["WarehouseID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ProductID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["OfficerID"]) . "</td>";
                    echo "<td>";
                    echo "<a href='view_batch.php?Barcode=" . urlencode($row["Barcode"]) . "' class='btn btn-primary btn-sm'>View</a> ";
                    echo "<a href='delete_batch.php?Barcode=" . urlencode($row["Barcode"]) . "' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-warning'>No batches found.</div>";
            }
            ?>
            <a href="create_batch.php" class="btn btn-success">Add Packaged Batch</a>
        </div>
    </section>

    <script>
        function toggleTable(sectionId) {
            const allTables = document.querySelectorAll('.tables-section .container');
            allTables.forEach(table => table.classList.add('hidden'));

            const selectedTable = document.getElementById(sectionId);
            selectedTable.classList.remove('hidden');
        }
    </script>
</body>

</html>