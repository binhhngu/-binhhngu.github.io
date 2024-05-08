<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
} else { 
    $idhv = $_SESSION['id']; 
    if(isset($_GET['makhoathi'])) {
        $makhoathi = $_GET['makhoathi'];
        $sql = "SELECT hv.hoten, hv.ngaysinh, hv.noisinh, thi.hoidongthi, kq.diemlt, kq.diemth, cc.sohieu, cc.sovaoso
                FROM hocvien hv
                INNER JOIN ketquathi kq ON hv.id = kq.idhv
                INNER JOIN thi ON kq.makhoathi = thi.makhoathi
                LEFT JOIN chungchi cc ON hv.id = cc.idhv AND kq.id = cc.idkq
                WHERE hv.id = :idhv AND kq.makhoathi = :makhoathi";
        $query = $dbh->prepare($sql);
        $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
        $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Xem Chứng Chỉ</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Xem Chứng Chỉ</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Chi Tiết Chứng Chỉ</h4>
                        </div>
                        <div class="panel-body">
                            <?php if($result): ?>
                            <div class="form-group">
                                <label>Họ và Tên:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['hoten']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Ngày Sinh:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['ngaysinh']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Nơi Sinh:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['noisinh']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Hội Đồng Thi:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['hoidongthi']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Điểm Lý Thuyết:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['diemlt']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Điểm Thực Hành:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['diemth']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Số Hiệu:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['sohieu']); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Số vào Sổ:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result['sovaoso']); ?>" readonly />
                            </div>
                            <?php else: ?>
                            <div class="alert alert-danger">Không tìm thấy thông tin chứng chỉ.</div>
                            <?php endif; ?>
                        </div>
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
<?php } ?>
