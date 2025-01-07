<?php
include("db_connect.php");

// Memeriksa apakah ada ID customer di URL
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;

// Mengambil daftar pizza dari tabel pizzas
$sql = "SELECT id, name, ingredients, price, image_url FROM pizzas ORDER BY id"; 
$result = mysqli_query($conn, $sql);
$pizzas = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

// Menutup koneksi database
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include("header.php"); ?>
    <title>Pilih Menu Pizza</title>
    <style>
        /* CSS style tetap sama */
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h4 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .fun-card {
            background-color: #ffeb3b;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            margin: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            width: 300px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .fun-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .card-content {
            padding: 20px;
            text-align: center;
            flex: 1;
        }

        img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
        }

        li {
            font-weight: bold;
            color: #d32f2f;
        }

        .quantity-control {
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .quantity-display {
            font-size: 1.5em;
            font-weight: bold;
            color: #ff5722;
            margin: 0 10px;
        }

        .btn-small {
            background-color: #ff5722;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-small:hover {
            background-color: #e64a19;
        }

        .new-order-btn {
            background-color: #ff5722;
            font-size: 1.2em;
            border-radius: 5px; 
            color: white;
            width: 100%;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .new-order-btn:hover {
            background-color: #e64a19;
        }

        .order-summary {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h4>SILAHKAN PILIH MENU</h4>

    <div class="container">
        <?php foreach($pizzas as $pizza): ?>
            <div class="fun-card">
                <div class="card-content">
                    <img src="<?php echo htmlspecialchars($pizza['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($pizza["name"]); ?>">
                    <h6 class="orange-text"><?php echo htmlspecialchars($pizza["name"]); ?></h6>
                    <ul>
                        <?php 
                            $ingredients = explode(",", $pizza["ingredients"]);
                            foreach($ingredients as $ingredient): ?>
                                <li><?php echo htmlspecialchars($ingredient); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="green-text">Harga Rp: Rp <?php echo htmlspecialchars(number_format($pizza["price"])); ?></p>

                    <div class="quantity-control">
                        <button class="btn-small" onclick="decreaseQuantity('<?php echo $pizza['id']; ?>')">-</button>
                        <span id="quantity-<?php echo $pizza['id']; ?>" class="quantity-display">0</span>
                        <button class="btn-small" onclick="increaseQuantity('<?php echo $pizza['id']; ?>')">+</button>
                        <input type="hidden" name="quantity" id="hidden-quantity-<?php echo $pizza['id']; ?>" value="0">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <h4>Ringkasan Pesanan</h4>
        <ul id="order-list"></ul>
    </div>

    <div class="card-action">
        <form action="result.php" method="GET">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <input type="submit" name="submit" value="Pesan Sekarang!" class="btn red new-order-btn">
        </form>
    </div>

    <script>
        function increaseQuantity(pizzaId) {
            const quantityDisplay = document.getElementById(`quantity-${pizzaId}`);
            let currentQuantity = parseInt(quantityDisplay.innerText);
            currentQuantity++;
            quantityDisplay.innerText = currentQuantity;
            updateHiddenQuantity(pizzaId);
            updateOrderSummary();
        }

        function decreaseQuantity(pizzaId) {
            const quantityDisplay = document.getElementById(`quantity-${pizzaId}`);
            let currentQuantity = parseInt(quantityDisplay.innerText);
            if (currentQuantity > 0) { // Ubah dari 1 menjadi 0
                currentQuantity--;
                quantityDisplay.innerText = currentQuantity;
                updateHiddenQuantity(pizzaId);
                updateOrderSummary();
            }
        }

        function updateHiddenQuantity(pizzaId) {
            const quantityDisplay = document.getElementById(`quantity-${pizzaId}`);
            const hiddenQuantity = document.getElementById(`hidden-quantity-${pizzaId}`);
            hiddenQuantity.value = quantityDisplay.innerText; // Update nilai input tersembunyi
        }

        function updateOrderSummary() {
            const orderList = document.getElementById('order-list');
            orderList.innerHTML = ''; // Kosongkan daftar sebelum memperbarui
            <?php foreach($pizzas as $pizza): ?>
                const quantity = parseInt(document.getElementById(`quantity-<?php echo $pizza['id']; ?>`).innerText);
                if (quantity > 0) {
                    const listItem = document.createElement('li');
                    listItem.textContent = '<?php echo htmlspecialchars($pizza["name"]); ?>: ' + quantity;
                    orderList.appendChild(listItem);
                }
            <?php endforeach; ?>
        }
            <?php include("footer.pho");?>
        </script>
</body>
</html>