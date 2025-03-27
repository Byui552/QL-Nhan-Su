index.php:

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user = $_SESSION['user'];
$is_admin = ($user['role'] == 'admin');

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM nhanvien LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) as total FROM nhanvien";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4">Danh sách nhân viên</h2>
    
    <?php if ($is_admin) { ?>
        <a href="add.php" class="btn btn-primary mb-3">Thêm Nhân Viên</a>
    <?php } ?>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Họ và tên</th>
                <th>Giới tính</th>
                <th>Nơi sinh</th>
                <th>Phòng</th>
                <th>Lương</th>
                <?php if ($is_admin) { ?>
                    <th>Hành động</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['Ma_NV']; ?></td>
                    <td><?php echo $row['Ten_NV']; ?></td>
                    <td>
                        <img src="<?php echo ($row['Phai'] == 'NU') ? 'woman.jpg' : 'man.jpg'; ?>" width="50">
                    </td>
                    <td><?php echo $row['Noi_Sinh']; ?></td>
                    <td><?php echo $row['Ma_Phong']; ?></td>
                    <td><?php echo number_format($row['Luong'], 0, ',', '.'); ?> VNĐ</td>
                    <?php if ($is_admin) { ?>
                        <td>
                            <a href="edit.php?id=<?php echo $row['Ma_NV']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="delete.php?id=<?php echo $row['Ma_NV']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <a href="logout.php" class="btn btn-secondary">Đăng xuất</a>
</body>
</html>