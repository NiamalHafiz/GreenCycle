<?php
include "connection.php";
session_start();

  
if (isset($_POST['add_transport'])) {
    $route = $_POST['route'];
    $vehicle_type = $_POST['vehicle_type'];
    $status = $_POST['status'];
    $cmo_id = $_POST['cmo_id'];

    $query = "INSERT INTO transport (Route, VehicleType, Status, CmoID) VALUES ('$route', '$vehicle_type', '$status', '$cmo_id')";
    mysqli_query($conn, $query);
}

 
if (isset($_POST['update_transport'])) {
    $transport_id = $_POST['transport_id'];
    $route = $_POST['route'];
    $vehicle_type = $_POST['vehicle_type'];
    $status = $_POST['status'];
    $cmo_id = $_POST['cmo_id'];

    $query = "UPDATE transport SET Route = '$route', VehicleType = '$vehicle_type', Status = '$status', CmoID = '$cmo_id' WHERE TransportID = '$transport_id'";
    mysqli_query($conn, $query);
}

 
if (isset($_GET['delete'])) {
    $transport_id = $_GET['delete'];
    $query = "DELETE FROM transport WHERE TransportID = '$transport_id'";
    mysqli_query($conn, $query);
}

 
$query = "SELECT * FROM transport";
$result = mysqli_query($conn, $query);

 
$active_count = 0;
$inactive_count = 0;
$vehicle_type_count = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Status'] == 'Active') {
        $active_count++;
    } else {
        $inactive_count++;
    }
    
    $vehicle_type = $row['VehicleType'];
    if (isset($vehicle_type_count[$vehicle_type])) {
        $vehicle_type_count[$vehicle_type]++;
    } else {
        $vehicle_type_count[$vehicle_type] = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Management</title>
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
        <h2 class="text-center">Transport Management</h2>

       
        <?php if (isset($message)): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

 
        <h3>Add New Transport</h3>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="route" class="form-control" placeholder="Route" required>
                <input type="text" name="vehicle_type" class="form-control" placeholder="Vehicle Type" required>
                <input type="text" name="status" class="form-control" placeholder="Status" required>
                <input type="number" name="cmo_id" class="form-control" placeholder="CMO ID" required>
                <button type="submit" name="add_transport" class="btn btn-custom">Add Transport</button>
            </div>
        </form>

         
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Transport ID</th>
                    <th>Route</th>
                    <th>Vehicle Type</th>
                    <th>Status</th>
                    <th>CMO ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['TransportID']; ?></td>
                    <td><?php echo $row['Route']; ?></td>
                    <td><?php echo $row['VehicleType']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['CmoID']; ?></td>
                    <td>
                         
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['TransportID']; ?>">Edit</button>
                        
                        <a href="?delete=<?php echo $row['TransportID']; ?>" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this transport?')">Delete</a>
                    </td>
                </tr>

                 
                <div class="modal fade" id="editModal<?php echo $row['TransportID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Transport</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="transport_id" value="<?php echo $row['TransportID']; ?>">
                                    <input type="text" name="route" class="form-control" value="<?php echo $row['Route']; ?>" required>
                                    <input type="text" name="vehicle_type" class="form-control" value="<?php echo $row['VehicleType']; ?>" required>
                                    <input type="text" name="status" class="form-control" value="<?php echo $row['Status']; ?>" required>
                                    <input type="number" name="cmo_id" class="form-control" value="<?php echo $row['CmoID']; ?>" required>
                                    <button type="submit" name="update_transport" class="btn btn-primary mt-3">Update Transport</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>

         
        <div class="mt-5">
            <h3>Status Distribution (Active vs Inactive)</h3>
            <canvas id="statusChart"></canvas>

            <h3 class="mt-5">Vehicle Type Distribution</h3>
            <canvas id="vehicleTypeChart"></canvas>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        var ctx2 = document.getElementById('vehicleTypeChart').getContext('2d');
        var vehicleTypeChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: Object.keys(<?php echo json_encode($vehicle_type_count); ?>),
                datasets: [{
                    label: 'Vehicle Type Count',
                    data: Object.values(<?php echo json_encode($vehicle_type_count); ?>),
                    backgroundColor: '#4CAF50',
                }]
            },
        });
    </script>
</body>

</html>
