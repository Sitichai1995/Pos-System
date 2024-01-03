<?php
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-md">
        <div class="card-header">
            <h4 class="mb-0">Add Order
                <a href="orders.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="order-code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select production</label>
                        <select name="productId" class="form-select mySelect2">
                            <option value=""> -- Select Product --</option>
                            <?php
                            $products = getAll('products');
                            if ($products) {
                                if (mysqli_num_rows($products) > 0) {
                                    foreach ($products as $item) {
                            ?>
                                        <option value="<?= $item['id']; ?>"><?= $item['name']; ?></option>
                            <?php
                                    }
                                } else {
                                    echo '<h5> No product found. </h5>';
                                }
                            } else {
                                echo '<h5> Something went wrong. </h5>';
                            }

                            ?>
                        </select>

                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" class="form-control" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <br />
                        <button type="submit" name="addItem" class="btn btn-success">Add item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h4 class="mb-0">Products</h4>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['productItems'])) {
                $sessionProducts = $_SESSION['productItems'];
            ?>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total price</th>
                                <th>Remove</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php foreach ($sessionProducts as $key => $item) : ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['price'] ?></td>
                                    <td>
                                        <div class="input-group">
                                            <button class="input-group-text">-</button>
                                            <input type="text" value="<?= $item['quantity'] ?>" class="qty qauantityInput">
                                            <button class="input-group-text">+</button>
                                        </div>
                                    </td>
                                    <td><?= $item['price'] * $item['quantity'], 0 ?></td>
                                    <td>
                                        <a href="order-item-delete.php?index=<?= $key ?>" class="btn btn-danger">Remove</a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
                echo '<h5>No item added </h5>';
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>