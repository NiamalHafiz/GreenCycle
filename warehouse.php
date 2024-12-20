<?php
include "connection.php";
session_start();

// Handle Create operation
if (isset($_POST['add_warehouse'])) {
    $city = $_POST['city'];
    $street = $_POST['street'];
    $capacity = $_POST['capacity'];
    $type = $_POST['type'];

    $query = "INSERT INTO warehouse (City, Street, Capacity, Type) VALUES ('$city', '$street', '$capacity', '$type')";
    mysqli_query($conn, $query);
}

// Handle Update operation
if (isset($_POST['update_warehouse'])) {
    $warehouse_id = $_POST['warehouse_id'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $capacity = $_POST['capacity'];
    $type = $_POST['type'];

    $query = "UPDATE warehouse SET City = '$city', Street = '$street', Capacity = '$capacity', Type = '$type' WHERE WarehouseID = '$warehouse_id'";
    mysqli_query($conn, $query);
}

// Handle Delete operation
if (isset($_GET['delete'])) {
    $warehouse_id = $_GET['delete'];
    $query = "DELETE FROM warehouse WHERE WarehouseID = '$warehouse_id'";
    mysqli_query($conn, $query);
}

// Fetch data to display in table
$query = "SELECT * FROM warehouse";
$result = mysqli_query($conn, $query);

// Prepare data for chart (Capacity per Type of Warehouse)
$capacity_data = [];
$cities = [];
$types = [];

while ($row = mysqli_fetch_assoc($result)) {
    $cities[] = $row['City'];
    $types[] = $row['Type'];
    $capacity_data[] = $row['Capacity'];
}

// Get capacity sums for each warehouse type
$type_capacity = [];
foreach ($types as $index => $type) {
    if (!isset($type_capacity[$type])) {
        $type_capacity[$type] = 0;
    }
    $type_capacity[$type] += $capacity_data[$index];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        table th {
            background-color: #4CAF50;
            color: rgb(7, 7, 7);
        }

        table td {
            text-align: center;
        }

        .btn-custom {
            background-color: #4CAF50;
            color: white;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .alert {
            margin-top: 10px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">GreenCycle</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <section class="container my-5">
        <h2 class="text-center">Warehouse Management</h2>

        <!-- Alert for success or failure -->
        <?php if (isset($message)): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Add New Warehouse Form -->
        <h3>Add New Warehouse</h3>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="city" class="form-control" placeholder="City" required>
                <input type="text" name="street" class="form-control" placeholder="Street" required>
                <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
                <input type="text" name="type" class="form-control" placeholder="Warehouse Type" required>
                <button type="submit" name="add_warehouse" class="btn btn-custom">Add Warehouse</button>
            </div>
        </form>

        <!-- Displaying Warehouse Data -->
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Warehouse ID</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Capacity</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset result pointer to fetch data again for displaying
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['WarehouseID']; ?></td>
                    <td><?php echo $row['City']; ?></td>
                    <td><?php echo $row['Street']; ?></td>
                    <td><?php echo $row['Capacity']; ?></td>
                    <td><?php echo $row['Type']; ?></td>
                    <td>
                        <!-- Edit button -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['WarehouseID']; ?>">Edit</button>
                        <!-- Delete button -->
                        <a href="?delete=<?php echo $row['WarehouseID']; ?>" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this warehouse?')">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $row['WarehouseID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Warehouse</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="warehouse_id" value="<?php echo $row['WarehouseID']; ?>">
                                    <input type="text" name="city" class="form-control" value="<?php echo $row['City']; ?>" required>
                                    <input type="text" name="street" class="form-control" value="<?php echo $row['Street']; ?>" required>
                                    <input type="number" name="capacity" class="form-control" value="<?php echo $row['Capacity']; ?>" required>
                                    <input type="text" name="type" class="form-control" value="<?php echo $row['Type']; ?>" required>
                                    <button type="submit" name="update_warehouse" class="btn btn-primary mt-3">Update Warehouse</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Chart Section -->
        <div class="mt-5">
            <h3>Warehouse Capacity by Type</h3>
            <canvas id="capacityChart"></canvas>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Line Chart for Warehouse Capacity by Type
        var ctx = document.getElementById('capacityChart').getContext('2d');
        var capacityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(<?php echo json_encode($type_capacity); ?>),
                datasets: [{
                    label: 'Warehouse Capacity (in units)',
                    data: Object.values(<?php echo json_encode($type_capacity); ?>),
                    fill: false,
                    borderColor: '#4CAF50',
                    tension: 0.1
                }]
            },
        });
    </script>
</body>

</html>