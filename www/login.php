<?php
// Cấu hình kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ máy chủ (localhost)
$db_username = "root"; // Tên người dùng cơ sở dữ liệu (thay đổi cho phù hợp)
$db_password = ""; // Mật khẩu cơ sở dữ liệu (thay đổi cho phù hợp)
$dbname = "secure_login"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi người dùng gửi yêu cầu đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form và kiểm tra đầu vào
    $user_input_username = $_POST['username'];
    $user_input_password = $_POST['password'];

    // Kiểm tra tên đăng nhập có hợp lệ không
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $user_input_username)) {
        die("Tên đăng nhập không hợp lệ.");
    }

    // Sử dụng prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_input_username); // "s" là kiểu dữ liệu của tham số (string)
    $stmt->execute();
    $stmt->store_result(); // Lưu trữ kết quả truy vấn

    // Kiểm tra xem tên đăng nhập có tồn tại trong cơ sở dữ liệu hay không
    if ($stmt->num_rows > 0) {
        // Liên kết kết quả truy vấn với biến
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Xác thực mật khẩu
        if (password_verify($user_input_password, $hashed_password)) {
            echo "Đăng nhập thành công!";
        } else {
            echo "Tên đăng nhập hoặc mật khẩu không chính xác.";
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không chính xác.";
    }

    // Đóng câu lệnh
    $stmt->close();
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>