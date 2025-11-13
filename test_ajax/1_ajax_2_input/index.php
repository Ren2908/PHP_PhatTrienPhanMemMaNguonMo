<?php
$conn = mysqli_connect("localhost", "root", "", "shopdb");

// Kiểm tra AJAX request
if (isset($_GET['ajax'])) {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $term = isset($_GET['term']) ? mysqli_real_escape_string($conn, $_GET['term']) : '';

    $data = array();

    if ($type === 'product') {
        $sql = "SELECT name FROM products WHERE name LIKE '%$term%' LIMIT 10";
    } elseif ($type === 'customer') {
        $sql = "SELECT name FROM customers WHERE name LIKE '%$term%' LIMIT 10";
    } else {
        $sql = '';
    }

    if ($sql) {
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row['name'];
        }
    }

    echo json_encode($data);
    exit; // dừng PHP, không in HTML
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>2 Auto-complete Fields One File</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>

<body>

    <h3>Search Product:</h3>
    <input type="text" id="product_search" placeholder="Type product name...">

    <h3>Search Customer:</h3>
    <input type="text" id="customer_search" placeholder="Type customer name...">


</body>

</html>

<script>
    $(function() {
        $("#product_search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "", // gửi về chính file này
                    dataType: "json",
                    data: {
                        ajax: 1,
                        type: 'product',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1
        });

        $("#customer_search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "", // gửi về chính file này
                    dataType: "json",
                    data: {
                        ajax: 1,
                        type: 'customer',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1
        });
    });
</script>