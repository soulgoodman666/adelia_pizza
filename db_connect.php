<?php
$conn = mysqli_connect('localhost', 'shaun', 'shaunthesheep', 'shanum_pizza');

if ($conn) {
   
} else {
    die("Connection failed: " . mysqli_connect_error());
}
?>
