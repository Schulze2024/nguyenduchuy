<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$database = "qlbh";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Vẫn chưa được: " . $conn->connect_error);
}
$taikhoan = isset($_POST['taikhoan']) ? $_POST['taikhoan'] : null;
$tennguoidung = isset($_POST['tennguoidung']) ? $_POST['tennguoidung'] : null;
$matkhau = isset($_POST['matkhau']) ? password_hash($_POST['matkhau'], PASSWORD_BCRYPT) : null;
$sdt = isset($_POST['sdt']) ? $_POST['sdt'] : null;
if ($taikhoan && $tennguoidung && $matkhau && $sdt) {
    $kiemtra_sql = "SELECT * FROM Taikhoan_Khachhang WHERE TaiKhoan = ? OR Sdt = ?";
    $kiemtra_stmt = $conn->prepare($kiemtra_sql);
    $kiemtra_stmt->bind_param("ss", $taikhoan, $sdt);
    $kiemtra_stmt->execute();
    $kiemtra_ketqua = $kiemtra_stmt->get_result();
    if ($kiemtra_ketqua->num_rows > 0) {
        echo "Tài khoản hoặc sdt bị trùng!";
        $kiemtra_stmt->close();
        $conn->close();
        exit();
    }
    $kiemtra_stmt->close();
    $dua_vao_sql = "INSERT INTO Taikhoan_Khachhang (TaiKhoan, TenDangNhap, MatKhau, Sdt) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($dua_vao_sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $taikhoan, $tennguoidung, $matkhau, $sdt);
        if ($stmt->execute()) {
            echo "<div style='color: green; font-weight: bold; text-align: center;'>Đăng ký thành công!</div>";
        } else {
            echo "Lỗi đăng ký: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi prepare(truyền dữ liệu vào sql): " . $conn->error;
    }
} else {
    echo "Điền thiếu thông tin rồi đấy.";
}
$conn->close();
?>
