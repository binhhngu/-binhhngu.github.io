<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
} else { 
    $idhv=$_SESSION['id']; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Khóa thi đã đăng ký</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                    <h4 class="header-line">Khóa thi đã đăng ký</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách khóa thi đã đăng ký
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã Khóa Thi</th>
                                            <th>Tên Chứng Chỉ/Chứng Nhận</th>
                                            <th>Hội Đồng Thi</th>
                                            <th>Địa Điểm Thi</th>
                                            <th>Ngày Thi</th>
                                            <th>Trạng Thái Đăng Ký</th>
                                            <th>Kết Quả</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = "SELECT dkthi.*, thi.*, kq.ketqua FROM dkthi 
                                                    INNER JOIN thi ON dkthi.makhoathi = thi.makhoathi 
                                                    LEFT JOIN ketquathi kq ON dkthi.idhv = kq.idhv AND dkthi.makhoathi = kq.makhoathi 
                                                    WHERE dkthi.idhv = :idhv";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center"><?php echo htmlentities($result->makhoathi);?></td>
                                            <td class="center"><?php echo htmlentities($result->chungchi);?></td>
                                            <td class="center"><?php echo htmlentities($result->hoidongthi);?></td>
                                            <td class="center"><?php echo htmlentities($result->diadiem);?></td>
                                            <td class="center"><?php echo htmlentities($result->ngaythi);?></td>
                                            <td class="center">
                                                <?php if($result->trangthaiduyet==1) {?>
                                                    <a href="#" class="btn btn-success btn-xs">Đã duyệt</a>
                                                <?php } else {?>
                                                    <a href="#" class="btn btn-danger btn-xs">Chưa duyệt</a>
                                                <?php } ?>
                                            </td>
                                            <td class="center">
                                                <?php if($result->ketqua != null) {?>
                                                    <a href="xem_ketqua.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>" class="btn btn-info btn-xs">Xem kết quả</a>
                                                <?php } else {?>
                                                    <span class="label label-default">Chưa có kết quả</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php $cnt++; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>
