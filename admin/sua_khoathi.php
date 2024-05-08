<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

    if(isset($_POST['update'])) {
        $makhoathi = $_POST['makhoathi'];
        $chungchi = $_POST['chungchi'];
        $hoidongthi = $_POST['hoidongthi'];
        $diadiem = $_POST['diadiem'];
        $ngaythi = $_POST['ngaythi'];
        $hanchotdk = $_POST['hanchotdk'];
        
        $sql = "UPDATE thi SET chungchi=:chungchi, hoidongthi=:hoidongthi, diadiem=:diadiem, ngaythi=:ngaythi, hanchotdk=:hanchotdk WHERE makhoathi=:makhoathi";
        $query = $dbh->prepare($sql);
        $query->bindParam(':chungchi', $chungchi, PDO::PARAM_STR);
        $query->bindParam(':hoidongthi', $hoidongthi, PDO::PARAM_STR);
        $query->bindParam(':diadiem', $diadiem, PDO::PARAM_STR);
        $query->bindParam(':ngaythi', $ngaythi, PDO::PARAM_STR);
        $query->bindParam(':hanchotdk', $hanchotdk, PDO::PARAM_STR);
        $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Cập nhật thông tin khóa thi thành công.";
        header('location:quanly_khoathi.php');


}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Sửa Khóa Học</title>
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
                    <h4 class="header-line">Chỉnh sửa</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
<div class="panel-heading">
                            Thông tin khóa thi
                        </div>

                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php 
                                    $makhoathi = $_GET['makhoathi'];
                                    $sql = "SELECT * FROM thi WHERE makhoathi=:makhoathi";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) {               
                                ?>
                                <div class="form-group">
                                    <label>Mã Khóa Thi</label>
                                    <input class="form-control" type="text" name="makhoathi"
                                        value="<?php echo htmlentities($result->makhoathi);?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Tên Chứng Chỉ/Chứng Nhận</label>
                                    <input class="form-control" type="text" name="chungchi"
                                        value="<?php echo htmlentities($result->chungchi);?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Hội Đồng Thi</label>
                                    <input class="form-control" type="text" name="hoidongthi"
                                        value="<?php echo htmlentities($result->hoidongthi);?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Địa Điểm Thi</label>
                                    <input class="form-control" type="text" name="diadiem"
                                        value="<?php echo htmlentities($result->diadiem);?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Ngày Thi</label>
                                    <input class="form-control" type="date" name="ngaythi"
                                        value="<?php echo htmlentities($result->ngaythi);?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Hạn Chót Đăng Kí</label>
                                    <input class="form-control" type="date" name="hanchotdk"
                                        value="<?php echo htmlentities($result->hanchotdk);?>" required />
                                </div>
                                <?php }} ?>
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