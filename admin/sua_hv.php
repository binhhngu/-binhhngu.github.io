<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin']) == 0)
{   
    header('location:index.php');
}
else
{ 
    if(isset($_POST['update']))
    {
        $idhv = intval($_GET['idhv']);
        $hoten = $_POST['hoten'];
        $malop = $_POST['malop'];
        $ngaysinh = $_POST['ngaysinh'];
        $noisinh = $_POST['noisinh'];
        $gioitinh = $_POST['gioitinh'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        
        $sql = "UPDATE hocvien SET hoten=:hoten, malop=:malop, ngaysinh=:ngaysinh, noisinh=:noisinh, gioitinh=:gioitinh, email=:email, sdt=:sdt WHERE id=:idhv";
        $query = $dbh->prepare($sql);
        $query->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $query->bindParam(':malop', $malop, PDO::PARAM_STR);
        $query->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $query->bindParam(':noisinh', $noisinh, PDO::PARAM_STR);
        $query->bindParam(':gioitinh', $gioitinh, PDO::PARAM_INT);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Cập nhật thông tin học viên thành công";
        header('location:quanly_hv.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Cập Nhật Thông Tin Học Viên</title>
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
                    <h4 class="header-line">Cập nhật thông tin học viên</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Thông tin học viên
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label></label>
                                    <?php 
                                    $idhv = intval($_GET['idhv']);
                                    $sql = "SELECT * FROM hocvien WHERE id=:idhv";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if($query->rowCount() > 0)
                                    {
                                        foreach($results as $result)
                                        {               
                                    ?>
                                    <div class="form-group">
                                        <label>Họ Tên</label>
                                        <input class="form-control" type="text" name="hoten"
                                            value="<?php echo htmlentities($result->hoten);?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Lớp<span style="color:red;"></span></label>
                                        <select class="form-control" name="malop" required="required">
                                            <?php 
                                            $sql = "SELECT * from lop WHERE trangthai = 1";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if($query->rowCount() > 0)
                                            {
                                                foreach($results as $row)
                                                {   
                                                    ?>
                                            <option value="<?php echo htmlentities($row->malop);?>">
                                                <?php echo htmlentities($row->malop);?></option>
                                            <?php 
                                                } 
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày Sinh</label>
                                        <input class="form-control" type="date" name="ngaysinh"
                                            value="<?php echo htmlentities($result->ngaysinh);?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Nơi Sinh</label>
                                        <input class="form-control" type="text" name="noisinh"
                                            value="<?php echo htmlentities($result->noisinh);?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Giới Tính</label>
                                        <select class="form-control" name="gioitinh" required>
                                            <option value="1">Nam</option>
                                            <option value="0">Nữ</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" type="text" name="email"
                                            value="<?php echo htmlentities($result->email);?>" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Số Điện Thoại</label>
                                        <input class="form-control" type="text" name="sdt"
                                            value="<?php echo htmlentities($result->sdt);?>" required />
                                    </div>
                                    <?php 
                                        }
                                    } 
                                    ?>
                                </div>
                                <button type="submit" name="update" class="btn btn-info">Cập Nhật </button>
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
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>