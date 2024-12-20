<?php
include "connection.php";
session_start();


if (isset($_POST['add_officer'])) {
    $region = $_POST['region'];
    $expertise = $_POST['expertise'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $office_id = $_POST['office_id'];

    $query = "INSERT INTO agricultural_officer (Region, Expertise, City, Street, OfficeID) VALUES ('$region', '$expertise', '$city', '$street', '$office_id')";
    mysqli_query($conn, $query);
}


if (isset($_POST['update_officer'])) {
    $officer_id = $_POST['officer_id'];
    $region = $_POST['region'];
    $expertise = $_POST['expertise'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $office_id = $_POST['office_id'];

    $query = "UPDATE agricultural_officer SET Region = '$region', Expertise = '$expertise', City = '$city', Street = '$street', OfficeID = '$office_id' WHERE OfficerID = '$officer_id'";
    mysqli_query($conn, $query);
}


if (isset($_GET['delete'])) {
    $officer_id = $_GET['delete'];
    $query = "DELETE FROM agricultural_officer WHERE OfficerID = '$officer_id'";
    mysqli_query($conn, $query);
}


$query = "SELECT * FROM agricultural_officer";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Officer Management</title>
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

    <section class="container my-5">
        <h2 class="text-center">Agricultural Officers</h2>

       
        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        
        <h3>Add New Officer</h3>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="region" class="form-control" placeholder="Region" required>
                <input type="text" name="expertise" class="form-control" placeholder="Expertise" required>
                <input type="text" name="city" class="form-control" placeholder="City" required>
                <input type="text" name="street" class="form-control" placeholder="Street" required>
                <input type="number" name="office_id" class="form-control" placeholder="Office ID" required>
                <button type="submit" name="add_officer" class="btn btn-custom">Add Officer</button>
            </div>
        </form>

        
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Region</th>
                    <th>Expertise</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Office ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['OfficerID']; ?></td>
                        <td><?php echo $row['Region']; ?></td>
                        <td><?php echo $row['Expertise']; ?></td>
                        <td><?php echo $row['City']; ?></td>
                        <td><?php echo $row['Street']; ?></td>
                        <td><?php echo $row['OfficeID']; ?></td>
                        <td>
                           
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['OfficerID']; ?>">Edit</button>
                            
                            <a href="?delete=<?php echo $row['OfficerID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this officer?')">Delete</a>
                        </td>
                    </tr>

                  
                    <div class="modal fade" id="editModal<?php echo $row['OfficerID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Officer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="officer_id" value="<?php echo $row['OfficerID']; ?>">
                                        <input type="text" name="region" class="form-control" value="<?php echo $row['Region']; ?>" required>
                                        <input type="text" name="expertise" class="form-control" value="<?php echo $row['Expertise']; ?>" required>
                                        <input type="text" name="city" class="form-control" value="<?php echo $row['City']; ?>" required>
                                        <input type="text" name="street" class="form-control" value="<?php echo $row['Street']; ?>" required>
                                        <input type="number" name="office_id" class="form-control" value="<?php echo $row['OfficeID']; ?>" required>
                                        <button type="submit" name="update_officer" class="btn btn-primary mt-3">Update Officer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
