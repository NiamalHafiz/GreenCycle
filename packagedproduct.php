<?php
include "connection.php";
session_start();

// Handle Create operation
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $packaging_method = $_POST['packaging_method'];
    $weight_per_unit = $_POST['weight_per_unit'];
    $carbohydrates = $_POST['carbohydrates'];
    $fat = $_POST['fat'];
    $vitamins = $_POST['vitamins'];

    $query = "INSERT INTO packaged_product (Name, PackagingMethod, WeightPerUnit, Carbohydrates, Fat, Vitamins) 
              VALUES ('$name', '$packaging_method', '$weight_per_unit', '$carbohydrates', '$fat', '$vitamins')";
    mysqli_query($conn, $query);
}

// Handle Update operation
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $packaging_method = $_POST['packaging_method'];
    $weight_per_unit = $_POST['weight_per_unit'];
    $carbohydrates = $_POST['carbohydrates'];
    $fat = $_POST['fat'];
    $vitamins = $_POST['vitamins'];

    $query = "UPDATE packaged_product SET Name = '$name', PackagingMethod = '$packaging_method', 
              WeightPerUnit = '$weight_per_unit', Carbohydrates = '$carbohydrates', 
              Fat = '$fat', Vitamins = '$vitamins' WHERE ProductID = '$product_id'";
    mysqli_query($conn, $query);
}

// Handle Delete operation
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $query = "DELETE FROM packaged_product WHERE ProductID = '$product_id'";
    mysqli_query($conn, $query);
}

// Fetch data to display in table
$query = "SELECT * FROM packaged_product";
$result = mysqli_query($conn, $query);

// Prepare data for chart (Nutritional values for each product)
$product_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $product_data[] = [
        'name' => $row['Name'],
        'carbohydrates' => $row['Carbohydrates'],
        'fat' => $row['Fat'],
        'vitamins' => $row['Vitamins'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packaged Product Management</title>
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

        .chart-container {
            margin-top: 30px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            margin-top: 20px;
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
        <h2 class="text-center">Packaged Product Management</h2>

        <!-- Add New Product Form -->
        <h3>Add New Product</h3>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                <input type="text" name="packaging_method" class="form-control" placeholder="Packaging Method" required>
                <input type="number" name="weight_per_unit" class="form-control" placeholder="Weight per Unit" required>
                <input type="number" name="carbohydrates" class="form-control" placeholder="Carbohydrates (g)" required>
                <input type="number" name="fat" class="form-control" placeholder="Fat (g)" required>
                <input type="text" name="vitamins" class="form-control" placeholder="Vitamins" required>
                <button type="submit" name="add_product" class="btn btn-custom">Add Product</button>
            </div>
        </form>

        <!-- Displaying Product Data -->
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Packaging Method</th>
                    <th>Weight per Unit</th>
                    <th>Carbohydrates (g)</th>
                    <th>Fat (g)</th>
                    <th>Vitamins</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset result pointer to fetch data again for displaying
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['ProductID']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td><?php echo $row['PackagingMethod']; ?></td>
                    <td><?php echo $row['WeightPerUnit']; ?> g</td>
                    <td><?php echo $row['Carbohydrates']; ?> g</td>
                    <td><?php echo $row['Fat']; ?> g</td>
                    <td><?php echo $row['Vitamins']; ?></td>
                    <td>
                        <!-- Edit button -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['ProductID']; ?>">Edit</button>
                        <!-- Delete button -->
                        <a href="?delete=<?php echo $row['ProductID']; ?>" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $row['ProductID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $row['ProductID']; ?>">
                                    <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" required>
                                    <input type="text" name="packaging_method" class="form-control" value="<?php echo $row['PackagingMethod']; ?>" required>
                                    <input type="number" name="weight_per_unit" class="form-control" value="<?php echo $row['WeightPerUnit']; ?>" required>
                                    <input type="number" name="carbohydrates" class="form-control" value="<?php echo $row['Carbohydrates']; ?>" required>
                                    <input type="number" name="fat" class="form-control" value="<?php echo $row['Fat']; ?>" required>
                                    <input type="text" name="vitamins" class="form-control" value="<?php echo $row['Vitamins']; ?>" required>
                                    <button type="submit" name="update_product" class="btn btn-primary mt-3">Update Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Chart Section -->
        <div class="chart-container">
            <h3>Product Nutritional Profile</h3>
            <canvas id="nutritionalChart"></canvas>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Radar Chart for Product Nutritional Profile
        var ctx = document.getElementById('nutritionalChart').getContext('2d');
        var nutritionalChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Carbohydrates', 'Fat', 'Vitamins'],
                datasets: <?php echo json_encode(array_map(function ($product) {
                    return [
                        'label' => $product['name'],
                        'data' => [$product['carbohydrates'], $product['fat'], 100], // Fixed value for vitamins as placeholder
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'borderWidth' => 1
                    ];
                }, $product_data)); ?>
            },
            options: {
                scale: {
                    ticks: { beginAtZero: true, max: 100 }
                }
            }
        });
    </script>
</body>

</html>