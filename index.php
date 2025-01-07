<?php

   include("db_connect.php");

    // Query SQL untuk mengambil data pizza
    $sql = "SELECT name, ingredients, price FROM pizzas";

    // Menjalankan query
    $result = mysqli_query($conn, $sql);

    // Menyimpan hasil query dalam bentuk array asosiatif
    $pizzas = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Membebaskan result set
    mysqli_free_result($result);

    // Menutup koneksi
    mysqli_close($conn);
    
$name = $address = "";
$errors = array("name" => "", "address" => "");

if (isset($_POST['submit'])) {
    // Validasi nama
    if (empty($_POST['name'])) {
        $errors['name'] = "Nama harus diisi";
    } else {
        $name = $_POST['name'];
    }

    // Validasi alamat
    if (empty($_POST['address'])) {
        $errors['address'] = "Alamat harus diisi";
    } else {
        $address = $_POST['address'];
    }

    // Jika tidak ada error, simpan ke database
    if (!array_filter($errors)) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        $sql = "INSERT INTO customers (name, address) VALUES ('$name', '$address')";

        if (mysqli_query($conn, $sql)) {
            $customer_id = mysqli_insert_id($conn); // Dapatkan ID customer yang baru dibuat
            header("Location: order.php?customer_id=$customer_id");
            exit;
        } else {
            echo "Query error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html>
<?php include("header.php"); ?>

<section class="container gray-text">
    <h4 class="center">Isi Data Anda</h4>
    <form class="white" action="add.php" method="POST">
        <label>Nama Anda:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <div class="red-text"><?php echo $errors['name']; ?></div>

        <label>Alamat Anda:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
        <div class="red-text"><?php echo $errors['address']; ?></div>

        <div class="center">
            <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<?php include("footer.php"); ?>
</html>
