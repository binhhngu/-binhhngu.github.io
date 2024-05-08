<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['update']))
{
$idgv=intval($_GET['idgv']);
$tengv = $_POST['tengv'];
$email = $_POST['email'];
$sdt = $_POST['sdt'];
$diachi = $_POST['diachi'];
$sql="update  giaovien set tengv=:tengv,email=:email,sdt=:sdt,diachi=:diachi where id=:idgv";
$query = $dbh->prepare($sql);
$query->bindParam(':tengv',$tengv,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':sdt',$sdt,PDO::PARAM_STR);
$query->bindParam(':diachi',$diachi,PDO::PARAM_STR);
$query->bindParam(':idgv',$idgv,PDO::PARAM_STR);
$query->execute();
$_SESSION['updatemsg']="Cập nhật thông tin giáo viên thành công";
header('location:quanly_gv.php');



}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Cập Nhật Thông Tin Giáo Viên</title>
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
                    <h4 class="header-line">Cập nhật thông tin giáo viên</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
                    <div class="panel-heading">
                        Thông tin giáo viên
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group">
                                <label></label>
                                <?php 
$idgv=intval($_GET['idgv']);
$sql = "SELECT * from  giaovien where id=:idgv";
$query = $dbh -> prepare($sql);
$query->bindParam(':idgv',$idgv,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                                <div class="form-group">
                                <label>Tên Giáo Viên</label>
                                <input class="form-control" type="text" name="tengv"
                                    value="<?php echo htmlentities($result->tengv);?>" required />
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
                            <div class="form-group">
                                <label>Địa Chỉ</label>
                                <input class="form-control" type="text" name="diachi"
                                    value="<?php echo htmlentities($result->diachi);?>" required />
                            </div>
                                <?php }} ?>
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