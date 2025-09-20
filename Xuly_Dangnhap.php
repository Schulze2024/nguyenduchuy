<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$database = "qlbh";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$taikhoan = isset($_POST['taikhoan']) ? $_POST['taikhoan'] : null;
$matkhau = isset($_POST['matkhau']) ? $_POST['matkhau'] : null;
if ($taikhoan && $matkhau) {
    $sql = "SELECT * FROM Taikhoan_Khachhang WHERE TaiKhoan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $taikhoan);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($matkhau, $row['MatKhau'])) {
            echo "<div style='text-align:center; margin-top:50px; font-size:24px; color:green;'>Đăng nhập thành công!</div>";
        } else {
            echo "<div style='text-align:center; margin-top:50px; font-size:24px; color:red;'>Sai mật khẩu!</div>";
            echo "<img src='anh/buon.png' alt='1 su that bai' width='200' style='display: block; margin: auto;'>";
        }
    } else {
        echo "<div style='text-align:center; margin-top:50px; font-size:24px; color:red;'>Tài khoản không tồn tại!</div>";
    }
    $stmt->close();
} else {
    echo "<div style='text-align:center; margin-top:50px; font-size:24px; color:red;'>Điền thiếu rồi đấy.</div>";
}
$conn->close();
?>
