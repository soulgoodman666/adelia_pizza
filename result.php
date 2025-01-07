<?php
include("db_connect.php");

// Memeriksa apakah ada ID customer di URL
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;

// Jika formulir telah dikirim, tampilkan hasil
if (isset($_GET['submit'])) {
    // Ambil data dari URL
    $pizza_id = isset($_GET['pizza_id']) ? intval($_GET['pizza_id']) : 0;
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 0;

    // Ambil detail pizza dari database
    $sql = "SELECT name, price FROM pizzas WHERE id = $pizza_id";
    $result = mysqli_query($conn, $sql);
    $pizza = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    if ($pizza) {
        $total_price = $pizza['price'] * $quantity;
        echo "<h4 class='center'>Detail Pesanan</h4>";
        echo "<div class='container center'>";
        echo "<p>Nama Pizza: <strong>" . htmlspecialchars($pizza['name']) . "</strong></p>";
        echo "<p>Jumlah: <strong>" . htmlspecialchars($quantity) . "</strong></p>";
        echo "<p>Total Harga: <strong>Rp " . number_format($total_price) . "</strong></p>";
        echo "</div>";
    } else {
        echo "<p class='red-text center'>Pizza tidak ditemukan.</p>";
    }
} else {
    // Ambil daftar pizza dari tabel pizzas
    $sql = "SELECT id, name, ingredients, price FROM pizzas ORDER BY id";
    $result = mysqli_query($conn, $sql);
    $pizzas = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pizza Order</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
        <style>
            .brand {
                background: #cbb09c !important;
            }
            .brand-text {
                color: #cbb09c !important;
            }
        </style>
    </head>
    <body class="grey lighten-4">
        <?php include("header.php"); ?>

        <h4 class="center">SILAHKAN PILIH MENU</h4>

        <div class="container">
            <div class="row">
                <?php if (!empty($pizzas)) { // Pastikan $pizzas tidak kosong ?>
                    <?php foreach ($pizzas as $pizza): ?>
                        <div class="col s4">
                            <div class="card z-depth-3">
                                <div class="card-content center">
                                    <img src="https://png.pngtree.com/png-clipart/20240315/original/pngtree-cartoon-pizza-slice-free-illustration-png-image_14595974.png" 
                                         alt="pizza" 
                                         width="100" 
                                         height="100">
                                    <h6 class="orange-text"><?php echo htmlspecialchars($pizza["name"]); ?></h6>
                                    <ul>
                                        <?php 
                                            $ingredients = explode(",", $pizza["ingredients"]);
                                            foreach ($ingredients as $ingredient): ?>
                                                <li><?php echo htmlspecialchars($ingredient); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <p class="green-text">Harga: Rp <?php echo htmlspecialchars(number_format($pizza["price"])); ?></p>

                                    <form action="" method="GET">
                                        <input type="hidden" name="pizza_id" value="<?php echo $pizza['id']; ?>">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

                                        <label for="quantity">Jumlah:</label>
                                        <input type="number" name="quantity" min="1" value="1" required>

                                        <div class="card-action center-align">
                                            <input type="submit" name="submit" value="Pesan" class="btn red">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <p class="center">Tidak ada pizza yang tersedia.</p>
                <?php } ?>
            </div>
        </div>

        <?php include("footer.php"); ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
    </html>

    <?php
}
?>
