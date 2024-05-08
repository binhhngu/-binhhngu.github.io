<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
} else {
    try {
        // Lấy danh sách các mã khóa thi từ bảng "thi"
        $sqlKhoaThi = "SELECT makhoathi FROM thi";
        $queryKhoaThi = $dbh->prepare($sqlKhoaThi);
        $queryKhoaThi->execute();
        $khoaThis = $queryKhoaThi->fetchAll(PDO::FETCH_OBJ);

        if(isset($_POST['submit'])) {
            $selectedMakhoathi = $_POST['makhoathi'];

            // Lấy danh sách học viên đã đạt với makhoathi đã chọn
            $sql = "SELECT hv.id AS idhv, hv.mshv, hv.hoten, hv.malop, kq.id AS kqid, kq.makhoathi, thi.chungchi, cc.sohieu, cc.sovaoso
            FROM hocvien hv
            INNER JOIN ketquathi kq ON hv.id = kq.idhv
            INNER JOIN thi ON kq.makhoathi = thi.makhoathi
            LEFT JOIN chungchi cc ON hv.id = cc.idhv AND kq.id = cc.idkq
            WHERE kq.ketqua = 1 AND kq.makhoathi = :makhoathi";
            $query = $dbh->prepare($sql);
            $query->bindParam(':makhoathi', $selectedMakhoathi, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi khi truy vấn cơ sở dữ liệu: " . $e->getMessage();
        header('location:them_chung_chi.php');
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Thêm Chứng Chỉ Cho Học Viên</title>
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
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Danh Sách Học Viên Đã Đạt</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Chọn Mã Khóa Thi</label>
                                    <select class="form-control" id="makhoathi" name="makhoathi">
                                        <option value="">Chọn mã khóa thi</option>
                                        <?php
                                        // Hiển thị danh sách các mã khóa thi
                                        foreach ($khoaThis as $khoaThi) {
                                            echo '<option value="' . $khoaThi->makhoathi . '">' . $khoaThi->makhoathi . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="submit" class="btn btn-default">Hiển Thị</button>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã Học Viên</th>
                                            <th>Họ và Tên</th>
                                            <th>Mã Lớp</th>
                                            <th>Mã Khóa Thi</th>
                                            <th>Chứng Chỉ</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($results)) {
                                            $cnt = 1;
                                            foreach ($results as $result) {
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->mshv); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->hoten); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->malop); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->makhoathi); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->chungchi); ?></td>
                                                    <td class="center">
                                                        <?php if(!empty($result->sohieu) && !empty($result->sovaoso)): ?>
                                                            <a href="thongtin_chungchi.php?idhv=<?php echo htmlentities($result->idhv); ?>&idkq=<?php echo htmlentities($result->kqid); ?>&makhoathi=<?php echo htmlentities($result->makhoathi); ?>" class="btn btn-xs">Xem Chứng Chỉ</a>
                                                        <?php else: ?>
                                                            <a href="thongtin_chungchi.php?idhv=<?php echo htmlentities($result->idhv); ?>&idkq=<?php echo htmlentities($result->kqid); ?>&makhoathi=<?php echo htmlentities($result->makhoathi); ?>" class="btn btn-info btn-xs">Thêm Chứng Chỉ</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>
