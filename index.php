<!DOCTYPE html>
<html>
<head>
    <title>Inventory System</title>
    <link rel="stylesheet" type="text/css" href="indexcss.css">
</head>
<body style="background-color: #fff8e7; overflow-x: hidden">
    <div class="sidenav">
        <img src="cof2.png" style="width: 197px">
        <img src="cof2.png" style="width: 197px">
    </div>
    <div class="imgcontainer">
        <a class="logoutbtn" href="logout.php" style="float: right; position: absolute; right: 5%; top: 3%;">Log out</a>
        <img src="logo.png" alt="Logo" class="logo" style="width: 300px; margin-top: 2px;">
        <h2 style="margin-top: 0px; margin-bottom: 4%; position: relative; align-content: left; left: 2%;"><font color="#47251e">Inventory System</font></h2>
    </div>
    <?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit();
    }

    $conn = mysqli_connect("localhost", "root", "", "inventory");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Create function
    function createProduct($name, $quantity, $price, $category)
    {
        global $conn;
        $sql = "INSERT INTO products (name, quantity, price, category) VALUES ('$name', $quantity, $price, '$category')";
        mysqli_query($conn, $sql);
    }

    // Read function
    function getProducts($category = null)
    {
        global $conn;
        $sql = "SELECT * FROM products";
        if ($category) {
            $sql .= " WHERE category='$category'";
        }
        $result = mysqli_query($conn, $sql);
        $products = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    // Update function
    function updateProduct($id, $name, $quantity, $price, $category)
    {
        global $conn;
        $sql = "UPDATE products SET name='$name', quantity=$quantity, price=$price, category='$category' WHERE id=$id";
        mysqli_query($conn, $sql);
    }

    // Delete function
    function deleteProduct($id)
    {
        global $conn;
        $sql = "DELETE FROM products WHERE id=$id";
        mysqli_query($conn, $sql);
    }

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        createProduct($name, $quantity, $price, $category);
    }

    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        deleteProduct($id);
    }

    if (isset($_POST['edit-submit'])) {
        $id = $_POST['edit-id'];
        $name = $_POST['edit-name'];
        $quantity = $_POST['edit-quantity'];
        $price = $_POST['edit-price'];
        $category = $_POST['edit-category'];
        updateProduct($id, $name, $quantity, $price, $category);
    }

    $sql = "SELECT DISTINCT category FROM products";
    $result = mysqli_query($conn, $sql);
    $categories = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category'];
    }

    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : null;
    $products = getProducts($categoryFilter);
    ?>

    <div class="fullcontainer container" style="float: left;">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label><font color="#47251e"><b><br><center>Add Product</center></b></label></font><br><br>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="quantity" placeholder="Stocks" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="category" placeholder="Category" required>
            <button type="submit" name="submit">Add Product</button><br><br>
        </form>
    </div>

    <div class="fullcontainer fullcontainer2 container" style="float: right; position: absolute; left: 28%;">
        <br>
        <div class="category-filter">
            <a class="view-all" href="<?php echo $_SERVER['PHP_SELF']; ?>">View All</a>
            <div class="dropdown-container">
                <input type="text" name="category" placeholder="Select Category">
                <div class="dropdown-content">
                    <?php foreach ($categories as $category): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?category=<?php echo urlencode($category); ?>"><?php echo $category; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="table-container">
            <br>
            <table>
                <tr>
                    <th class="center">Name</th>
                    <th class="center">Stocks</th>
                    <th class="center">Price</th>
                    <th class="center">Category</th>
                    <th class="center">Actions</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><?php echo $product['category']; ?></td>
                        <td>
                            <span>
                                <a class="edit-button" onclick="openModal(<?php echo $product['id']; ?>)">Edit</a>
                            </span>
                            <span>
                                <a class="delete-button" href="?action=delete&id=<?php echo $product['id']; ?>">Delete</a>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
        </div>
    </div>

    <!-- The modal pop-up window -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content" id="modalContent">
            <h2 class="center">Edit Product</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" id="editId" name="edit-id" value="<?php echo $product['id']; ?>">
                <input type="text" id="editName" name="edit-name" placeholder="Product" value="<?php echo $product['name']; ?>" required>
                <input type="text" id="editCategory" name="edit-category" placeholder="Category" value="<?php echo $product['category']; ?>" required>
                <input type="number" id="editQuantity" name="edit-quantity" placeholder="Quantity" value="<?php echo $product['quantity']; ?>" required>
                <input type="number" step="0.01" id="editPrice" name="edit-price" placeholder="Price" value="<?php echo $product['price']; ?>" required>
                <button type="submit" name="edit-submit">Save Changes</button>
            </form>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <script>
        function openModal(productId) {
            const modalOverlay = document.getElementById('modalOverlay');
            const modalContent = document.getElementById('modalContent');

            modalOverlay.style.display = 'block';
            modalContent.classList.add('open');

            getProductDetailsById(productId).then(product => {
                const editId = document.getElementById('editId');
                const editName = document.getElementById('editName');
                const editQuantity = document.getElementById('editQuantity');
                const editPrice = document.getElementById('editPrice');
                const editCategory = document.getElementById('editCategory');

                editId.value = product.id;
                editName.value = product.name;
                editQuantity.value = product.quantity;
                editPrice.value = product.price;
                editCategory.value = product.category;
            });
        }

        function closeModal() {
            const modalOverlay = document.getElementById('modalOverlay');
            const modalContent = document.getElementById('modalContent');
            modalOverlay.style.display = 'none';
            modalContent.classList.remove('open');
        }
    </script>
</body>
</html>
