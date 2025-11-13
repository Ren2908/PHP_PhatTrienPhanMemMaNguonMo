<?php
$conn = mysqli_connect("localhost", "root", "", "shopdb");

// 1. Xử lý AJAX autocomplete
if (isset($_GET['ajax'])) {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $term = isset($_GET['term']) ? $_GET['term'] : '';
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
    exit; // ngăn HTML lẫn vào JSON
}

// 2. Xử lý form submit
if (isset($_POST['submit'])) {
    $product = isset($_POST['product']) ? mysqli_real_escape_string($conn, $_POST['product']) : '';
    $customer = isset($_POST['customer']) ? mysqli_real_escape_string($conn, $_POST['customer']) : '';

    if ($product && $customer) {
        $sql_insert = "INSERT INTO orders (product_name, customer_name) VALUES ('$product', '$customer')";
        mysqli_query($conn, $sql_insert);
        $message = "Đã lưu thông tin vào database!";
    } else {
        $message = "Vui lòng chọn cả sản phẩm và khách hàng!";
    }
}

// 3. Lấy danh sách order để hiển thị
$order_list = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Auto-complete + Submit + List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>

<body>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <form method="post">
        <h3>Search Product:</h3>
        <input type="text" name="product" id="product_search" placeholder="Type product name..." required>

        <h3>Search Customer:</h3>
        <input type="text" name="customer" id="customer_search" placeholder="Type customer name..." required>

        <br><br>
        <button type="submit" name="submit">Submit</button>
    </form>

    <h3>Danh sách Orders:</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Customer</th>
            <th>Thời gian</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($order_list)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['customer_name']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <script>
        $(function() {
            $("#product_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "",
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
                        url: "",
                        dataType: "json",
                        data: { //Các biến bên trong data đặt gì cũng được
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

</body>

</html>