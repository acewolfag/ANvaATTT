<?php
$servername = "localhost";
$username = "root";  // Thay thế bằng user của bạn
$password = "";      // Thay thế bằng mật khẩu của bạn
$dbname = "sql_injection_demo";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ form
$user = $_POST['username'];
$pass = $_POST['password'];

// Câu truy vấn SQL có lỗ hổng SQL Injection
$sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Login successful!<br>";
    while($row = $result->fetch_assoc()) {
        echo "Welcome " . $row['username'] . "!";
    }
} else {
    echo "Invalid login credentials!";
}

$conn->close();
?>
