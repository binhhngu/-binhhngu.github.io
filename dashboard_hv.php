<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
    exit();
} else {
    $id = $_SESSION['id'];
    
    // Lấy thông tin học viên từ cơ sở dữ liệu
    $sql = "SELECT * FROM hocvien WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    // Lấy số lượng khóa học đang học của học viên
    $sql2 = "SELECT COUNT(*) AS total FROM dkthi WHERE idhv=:id";
    $query2 = $dbh->prepare($sql2);
    $query2->bindParam(':id', $id, PDO::PARAM_INT);
    $query2->execute();
    $row = $query2->fetch(PDO::FETCH_ASSOC);
    $totalCourses = $row['total'];

    // Lấy số lượng chứng chỉ đã đạt của học viên
    $sql3 = "SELECT COUNT(*) AS total FROM chungchi WHERE idhv=:id AND idkq = 1";
    $query3 = $dbh->prepare($sql3);
    $query3->bindParam(':id', $id, PDO::PARAM_INT);
    $query3->execute();
    $row2 = $query3->fetch(PDO::FETCH_ASSOC);
    $totalCertificates = $row2['total'];
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Hệ thống quản lý học viên | Trang Chủ</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<?php include('includes/header.php');?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">TRANG CHỦ</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="alert alert-info back-widget-set text-center">
                    <i class="fa fa-graduation-cap fa-5x"></i>
                    <h3><?php echo htmlentities($totalCourses);?></h3>
                    Số Khóa Học Đã Đăng Kí
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="alert alert-warning back-widget-set text-center">
                    <i class="fa fa-certificate fa-5x"></i>
                    <h3><?php echo htmlentities($totalCertificates);?></h3>
                    Số Chứng Chỉ Đã Đạt
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
