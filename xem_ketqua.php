<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
} else { 
    $idhv = $_SESSION['id']; 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Xem Kết Quả</title>
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
                    <h4 class="header-line">Xem Kết Quả</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Chi Tiết Kết Quả</h4>
                        </div>
                        <div class="panel-body">
                            <?php 
                                $makhoathi = $_GET['makhoathi'];
                                $sql = "SELECT * FROM ketquathi WHERE idhv=:idhv AND makhoathi=:makhoathi";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                                $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) {
                            ?>
                            <div class="form-group">
                                <label>Điểm Lý Thuyết:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result->diemlt); ?>"
                                    readonly />
                            </div>
                            <div class="form-group">
                                <label>Điểm Thực Hành:</label>
                                <input class="form-control" type="text" value="<?php echo htmlentities($result->diemth); ?>"
                                    readonly />
                            </div>
                            <div class="form-group">
                                <label>Kết Quả:</label>
                                <input class="form-control" type="text"
                                    value="<?php echo ($result->ketqua == 1) ? 'Đạt' : 'Chưa đạt'; ?>" readonly />
                            </div>
                            <?php if ($result->ketqua == 1): ?>
                            <div class="form-group">
                                <a href="xem_chungchi.php?makhoathi=<?php echo htmlentities($result->makhoathi); ?>" class="btn btn-success">Xem Chứng Chỉ</a>

                            </div>
                            <?php endif; ?>
                            <?php } } else { ?>
                            <div class="alert alert-danger">Không tìm thấy kết quả.</div>
                            <?php } ?>
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
