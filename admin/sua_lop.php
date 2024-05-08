<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['submit'])) {
        $malop=$_GET['malop'];
        $makhoa=$_POST['makhoa'];
        $ngaykhaigiang=$_POST['ngaykhaigiang'];
        $maphong=$_POST['maphong'];
        $magv=$_POST['magv'];
        $buoihoc=$_POST['buoihoc'];
        $siso=$_POST['siso'];
        $trangthai=$_POST['trangthai'];
        $ghichu=$_POST['ghichu'];

        $sql = "update lop set makhoa=:makhoa, ngaykhaigiang=:ngaykhaigiang, maphong=:maphong, magv=:magv, buoihoc=:buoihoc, siso=:siso, trangthai=:trangthai, ghichu=:ghichu where malop=:malop";
        $query = $dbh->prepare($sql);
        $query->bindParam(':makhoa',$makhoa,PDO::PARAM_STR);
        $query->bindParam(':ngaykhaigiang',$ngaykhaigiang,PDO::PARAM_STR);
        $query->bindParam(':maphong',$maphong,PDO::PARAM_STR);
        $query->bindParam(':magv',$magv,PDO::PARAM_STR);
        $query->bindParam(':buoihoc',$buoihoc,PDO::PARAM_STR);
        $query->bindParam(':siso',$siso,PDO::PARAM_STR);
        $query->bindParam(':trangthai',$trangthai,PDO::PARAM_STR);
        $query->bindParam(':ghichu',$ghichu,PDO::PARAM_STR);
        $query->bindParam(':malop',$malop,PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg']="Thông tin lớp đã được cập nhật thành công!";
        header('location:quanly_lop.php');
    }

    $malop=$_GET['malop'];
    $sql = "SELECT lop.*, khoahoc.tenkhoa 
    FROM lop 
    LEFT JOIN khoahoc ON lop.makhoa = khoahoc.makhoa 
    WHERE malop=:malop";
    $query = $dbh->prepare($sql);
    $query->bindParam(':malop',$malop,PDO::PARAM_STR);
    $query->execute();
    $lop_results = $query->fetchAll(PDO::FETCH_OBJ);
    
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Chỉnh Sửa Lớp Học</title>
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
                    <h4 class="header-line">Chỉnh Sửa Lớp Học</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Chỉnh Sửa Lớp Học
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php 
                                foreach($lop_results as $result) {
                                ?>
                                <div class="form-group">
                                    <label>Tên Khóa Học</label>
                                    <input class="form-control" type="text" name="tenkhoa"
                                        value="<?php echo htmlentities($result->tenkhoa);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Ngày Khai Giảng</label>
                                    <input class="form-control" type="date" name="ngaykhaigiang"
                                        value="<?php echo htmlentities($result->ngaykhaigiang);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Phòng</label>
                                    <input class="form-control" type="text" name="maphong"
                                        value="<?php echo htmlentities($result->maphong);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Giáo Viên</label>
                                    <input class="form-control" type="text" name="magv"
                                        value="<?php echo htmlentities($result->magv);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Buổi Học</label>
                                    
                                    <input class="form-control" type="text" name="buoihoc"
                                        value="<?php echo htmlentities($result->buoihoc);?>" required />
                                    
                                </div>

                                <div class="form-group">
                                    <label>Sĩ Số</label>
                                    <input class="form-control" type="number" name="siso"
                                        value="<?php echo htmlentities($result->siso);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Trạng Thái</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="trangthai" value="1"
                                                <?php if($result->trangthai == 1) echo "checked"; ?>> Đang Mở
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="trangthai" value="0"
                                                <?php if($result->trangthai == 0) echo "checked"; ?>> Đã Đóng
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>Ghi Chú</label>
                                    <textarea class="form-control" rows="5"
                                        name="ghichu"><?php echo htmlentities($result->ghichu);?></textarea>
                                </div>
                                <?php } ?>
                                <button type="submit" name="submit" class="btn btn-primary">Cập Nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>
<?php 
} 
?>
