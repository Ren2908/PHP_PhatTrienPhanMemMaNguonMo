<?php
$conn = mysqli_connect("localhost", "root", "", "shopdb");
if (!$conn) {
    echo json_encode([]);
    exit;
}

$term = isset($_GET['term']) ? $_GET['term'] : '';
$term = mysqli_real_escape_string($conn, $term);

$sql = "SELECT name FROM products WHERE name LIKE '%$term%' LIMIT 10";
$result = mysqli_query($conn, $sql);

// $data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row['name'];
}

echo json_encode($data);

mysqli_close($conn);
