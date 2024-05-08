<?php
session_start();
include('includes/config.php');
if(isset($_SESSION['login'])) {
    // Thực hiện các hành động liên quan đến phần tử 'login' ở đây
} else {
    // Xử lý trường hợp phần tử 'login' không tồn tại

if(isset($_POST['create'])) {
    $malop = $_GET['malop'];
    $hoten = $_POST['hoten'];
    $ngaysinh = $_POST['ngaysinh'];
    $noisinh = $_POST['noisinh'];
    $gioitinh = $_POST['gioitinh'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];

        $sql = "INSERT INTO dangki (hoten, malop, ngaysinh, noisinh, gioitinh, email, sdt) 
                VALUES (:hoten, :malop, :ngaysinh, :noisinh, :gioitinh, :email, :sdt)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $query->bindParam(':malop', $malop, PDO::PARAM_STR);
        $query->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $query->bindParam(':noisinh', $noisinh, PDO::PARAM_STR);
        $query->bindParam(':gioitinh', $gioitinh, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        
        if($lastInsertId !== false) {
            $_SESSION['msg'] = "Đăng kí thành công";
            header('location:dangki.php');
            exit();
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại !!!";
            header('location:dangki.php');
            exit();
        }
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
    <title>Quản Lý Học Viên | Thông Tin Học Viên</title>
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
    <div class="content-wra
    <div class=" content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Đăng kí</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
                    <div class=" panel panel-info">
                        <div class="panel-heading">
                            Thông Tin Học Viên
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Tên Học Viên</label>
                                    <input class="form-control" type="text" name="hoten" autocomplete="off" required="required" />
                                </div>
                                <div class="form-group">
                                    <label>Ngày Sinh</label>
                                    <input class="form-control" type="date" name="ngaysinh" autocomplete="off" required="required" />
                                </div>
                                <div class="form-group">
                                    <label>Nơi Sinh</label>
                                    <input class="form-control" type="text" name="noisinh" autocomplete="off" required="required" />
                                </div>
                                <div class="form-group">
                                    <label>Giới Tính</label>
                                    <select class="form-control" name="gioitinh" required="required">
                                        <option value=""> Chọn giới tính</option>
                                        <option value="1">Nam</option>
                                        <option value="0">Nữ</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="email" autocomplete="off" required="required" />
                                </div>
                                <div class="form-group">
                                    <label>Số Điện Thoại</label>
                                    <input class="form-control" type="text" name="sdt" autocomplete="off" required="required" />
                                </div>
                                <button type="submit" name="create" class="btn btn-info">Thêm </button>
                            </form>
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
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
