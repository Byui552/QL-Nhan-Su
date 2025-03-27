<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin nhân viên
$sql = "SELECT * FROM nhanvien WHERE Ma_NV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$nhanvien = $result->fetch_assoc();

if (!$nhanvien) {
    echo "Không tìm thấy nhân viên!";
    exit();
}

// Lấy danh sách phòng ban
$phongban_sql = "SELECT * FROM phongban";
$phongban_result = $conn->query($phongban_sql);

// Cập nhật thông tin nhân viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_nv = $_POST['Ten_NV'];
    $phai = $_POST['Phai'];
    $noi_sinh = $_POST['Noi_Sinh'];
    $ma_phong = $_POST['Ma_Phong'];
    $luong = $_POST['Luong'];

    $sql = "UPDATE nhanvien SET Ten_NV=?, Phai=?, Noi_Sinh=?, Ma_Phong=?, Luong=? WHERE Ma_NV=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdi", $ten_nv, $phai, $noi_sinh, $ma_phong, $luong, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật nhân viên!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Nhân Viên</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-center mb-0">Chỉnh sửa Nhân Viên</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên:</label>
                                <input type="text" class="form-control" name="Ten_NV" value="<?php echo $nhanvien['Ten_NV']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giới tính:</label>
                                <select class="form-select" name="Phai">
                                    <option value="NAM" <?php if ($nhanvien['Phai'] == 'NAM') echo 'selected'; ?>>Nam</option>
                                    <option value="NU" <?php if ($nhanvien['Phai'] == 'NU') echo 'selected'; ?>>Nữ</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nơi sinh:</label>
                                <input type="text" class="form-control" name="Noi_Sinh" value="<?php echo $nhanvien['Noi_Sinh']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phòng ban:</label>
                                <select class="form-select" name="Ma_Phong">
                                    <?php while ($row = $phongban_result->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['Ma_Phong']; ?>" <?php if ($row['Ma_Phong'] == $nhanvien['Ma_Phong']) echo 'selected'; ?>>
                                            <?php echo $row['Ten_Phong']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lương:</label>
                                <input type="number" class="form-control" name="Luong" value="<?php echo $nhanvien['Luong']; ?>" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Cập nhật</button>
                                <a href="index.php" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

