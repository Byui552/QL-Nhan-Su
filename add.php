<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
$sql_phongban = "SELECT Ma_Phong, Ten_Phong FROM phongban";
$result_phongban = $conn->query($sql_phongban);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_nv   = $_POST['Ten_NV'];
    $phai     = $_POST['Phai'];
    $noi_sinh = $_POST['Noi_Sinh'];
    $ma_phong = $_POST['Ma_Phong'];
    $luong    = $_POST['Luong'];

    $sql = "INSERT INTO nhanvien (Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssd", $ten_nv, $phai, $noi_sinh, $ma_phong, $luong);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi khi thêm nhân viên: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm nhân viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Thêm nhân viên</h2>
    <form action="" method="post" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label class="form-label">Tên NV:</label>
            <input type="text" name="Ten_NV" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phái:</label>
            <select name="Phai" class="form-select">
                <option value="NAM">Nam</option>
                <option value="NU">Nữ</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nơi sinh:</label>
            <input type="text" name="Noi_Sinh" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phòng:</label>
            <select name="Ma_Phong" class="form-select">
                <?php while ($row_pb = $result_phongban->fetch_assoc()) { ?>
                    <option value="<?php echo $row_pb['Ma_Phong']; ?>">
                        <?php echo $row_pb['Ma_Phong'] . ' - ' . $row_pb['Ten_Phong']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Lương:</label>
            <input type="number" name="Luong" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Thêm</button>
        <a href="index.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
