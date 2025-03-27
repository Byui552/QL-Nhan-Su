<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Chuyển ID thành số nguyên

    $sql = "DELETE FROM nhanvien WHERE Ma_NV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi khi xóa nhân viên!";
    }
} else {
    echo "Không tìm thấy nhân viên cần xóa!";
}
?>
