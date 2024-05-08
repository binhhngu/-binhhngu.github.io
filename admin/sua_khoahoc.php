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
$tenkhoa=$_POST['tenkhoa'];
$sobuoi=$_POST['sobuoi'];
$makh=intval($_GET['makh']);
$sql="update  khoahoc set tenkhoa=:tenkhoa,sobuoi=:sobuoi where makhoa=:makh";
$query = $dbh->prepare($sql);
$query->bindParam(':tenkhoa',$tenkhoa,PDO::PARAM_STR);
$query->bindParam(':sobuoi',$sobuoi,PDO::PARAM_STR);
$query->bindParam(':makh',$makh,PDO::PARAM_STR);
$query->execute();
$_SESSION['updatemsg']="Cập nhật thông tin khóa học thành công.";
header('location:quanly_khoahoc.php');


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
                    <h4 class="header-line">Sửa Khóa Học</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
                    <div class="panel-heading">
                        Thông tin khóa học
                    </div>

                    <div class="panel-body">
                        <form role="form" method="post">
                            <?php 
$makh=intval($_GET['makh']);
$sql="SELECT * from khoahoc where makhoa=:makh";
$query=$dbh->prepare($sql);
$query-> bindParam(':makh',$makh, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{               
  ?>
                            <div class="form-group">
                                <label>Tên Khóa Học</label>
                                <input class="form-control" type="text" name="tenkhoa"
                                    value="<?php echo htmlentities($result->tenkhoa);?>" required />
                            </div>
                            <div class="form-group">
                                <label>Số Buổi</label>
                                <input class="form-control" type="text" name="sobuoi"
                                    value="<?php echo htmlentities($result->sobuoi);?>" required />
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