<?php
include('config.php');
$query = $_GET['query'];
$sql = "SELECT * FROM medicines WHERE name LIKE '%$query%'";
$result = mysqli_query($conn, $sql);
?>
<div class="search-results">
    <?php while($item = mysqli_fetch_assoc($result)): ?>
        <div class="item">
            <h4><?php echo $item['name']; ?></h4>
            <p><?php echo $item['stock_qty'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
        </div>
    <?php endwhile; ?>
</div>