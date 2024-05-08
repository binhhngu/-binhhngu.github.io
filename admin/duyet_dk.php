<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
} else {
    // Xử lý duyệt đăng ký
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $count_my_page = ("mahv.txt");
        $hits = file($count_my_page);
        $hits[0] ++;
        $fp = fopen($count_my_page , "w");
        fputs($fp , "$hits[0]");
        fclose($fp); 
        $mshv= $hits[0];
        // Truy vấn lấy dữ liệu từ bảng dangki
        $sql_dangki = "SELECT * FROM dangki WHERE id = :id";
        $query_dangki = $dbh->prepare($sql_dangki);
        $query_dangki->bindParam(':id', $id, PDO::PARAM_INT);
        $query_dangki->execute();
        $result_dangki = $query_dangki->fetch(PDO::FETCH_ASSOC);

        // Chèn dữ liệu vào bảng hocvien
        $sql_insert = "INSERT INTO hocvien (mshv,hoten, malop, ngaysinh, noisinh, gioitinh, email, matkhau, sdt, trangthai) 
                       VALUES (:mshv, :hoten, :malop, :ngaysinh, :noisinh, :gioitinh, :email, :matkhau, :sdt, 1)";
        $query_insert = $dbh->prepare($sql_insert);
        $query_insert->bindParam(':mshv', $mshv, PDO::PARAM_STR);
        $query_insert->bindParam(':hoten', $result_dangki['hoten'], PDO::PARAM_STR);
        $query_insert->bindParam(':malop', $result_dangki['malop'], PDO::PARAM_STR);
        $query_insert->bindParam(':ngaysinh', $result_dangki['ngaysinh'], PDO::PARAM_STR);
        $query_insert->bindParam(':noisinh', $result_dangki['noisinh'], PDO::PARAM_STR);
        $query_insert->bindParam(':gioitinh', $result_dangki['gioitinh'], PDO::PARAM_INT);
        $query_insert->bindParam(':email', $result_dangki['email'], PDO::PARAM_STR);
        $query_insert->bindParam(':matkhau', md5(123), PDO::PARAM_STR);
        $query_insert->bindParam(':sdt', $result_dangki['sdt'], PDO::PARAM_STR);
        $query_insert->execute();

        // Xóa dữ liệu đã duyệt khỏi bảng dangki
        $sql_delete = "DELETE FROM dangki WHERE id = :id";
        $query_delete = $dbh->prepare($sql_delete);
        $query_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $query_delete->execute();

        // Cập nhật số học viên hiện tại và trạng thái của lớp
        $sql_update_sisohientai = "UPDATE lop SET sisohientai = sisohientai + 1 WHERE malop = :malop";
        $query_update_sisohientai = $dbh->prepare($sql_update_sisohientai);
        $query_update_sisohientai->bindParam(':malop', $result_dangki['malop'], PDO::PARAM_STR);
        $query_update_sisohientai->execute();

        $sql_check_siso = "SELECT sisohientai, siso FROM lop WHERE malop = :malop";
        $query_check_siso = $dbh->prepare($sql_check_siso);
        $query_check_siso->bindParam(':malop', $result_dangki['malop'], PDO::PARAM_STR);
        $query_check_siso->execute();
        $result_check_siso = $query_check_siso->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra nếu số học viên hiện tại = số học viên tối đa, chuyển trạng thái lớp từ "đang mở" thành "đã đóng"
        if ($result_check_siso['sisohientai'] == $result_check_siso['siso']) {
            $sql_update_trangthai = "UPDATE lop SET trangthai = 0 WHERE malop = :malop";
            $query_update_trangthai = $dbh->prepare($sql_update_trangthai);
            $query_update_trangthai->bindParam(':malop', $result_dangki['malop'], PDO::PARAM_STR);
            $query_update_trangthai->execute();
        }

        $_SESSION['msg'] = "Duyệt đăng ký thành công";
        header('location:quanly_lop.php');
        exit();
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
    <title>Quản Lý Học Viên | Duyệt Đăng Ký</title>
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
                    <h4 class="header-line">Duyệt Đăng Ký</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class=" panel panel-info">
                        <div class="panel-heading">
                            Duyệt Đăng Ký
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ Tên</th>
                                        <th>Mã Lớp</th>
                                        <th>Ngày Sinh</th>
                                        <th>Nơi Sinh</th>
                                        <th>Giới Tính</th>
                                        <th>Email</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Truy vấn dữ liệu từ bảng dangki
                                    $sql = "SELECT * from dangki";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                    {
                                        foreach($results as $result)
                                        { ?>  
                                            <tr>
                                                <td><?php echo htmlentities($cnt);?></td>
                                                <td><?php echo htmlentities($result->hoten);?></td>
                                                <td><?php echo htmlentities($result->malop);?></td>
                                                <td><?php echo htmlentities($result->ngaysinh);?></td>
                                                <td><?php echo htmlentities($result->noisinh);?></td>
                                                <td><?php if($result->gioitinh == 1) { echo "Nam"; } else { echo "Nữ"; }?></td>
                                                <td><?php echo htmlentities($result->email);?></td>
                                                <td><?php echo htmlentities($result->sdt);?></td>
                                                <td>
                                                    <a href="duyet_dk.php?id=<?php echo htmlentities($result->id);?>">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Duyệt</button> 
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $cnt++;
                                        }
                                    }?>
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
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
