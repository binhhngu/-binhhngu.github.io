<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION['login']!=''){
$_SESSION['login']='';
}
if(isset($_POST['login']))
{

$mshv=$_POST['mshv'];
$matkhau=md5($_POST['matkhau']);
$sql ="SELECT mshv,matkhau,id,trangthai FROM hocvien WHERE mshv=:mshv and matkhau=:matkhau";
$query= $dbh -> prepare($sql);
$query-> bindParam(':mshv', $mshv, PDO::PARAM_STR);
$query-> bindParam(':matkhau', $matkhau, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
 foreach ($results as $result) {
 $_SESSION['id']=$result->id;
if($result->trangthai==1)
{
$_SESSION['login']=$_POST['mshv'];
echo "<script type='text/javascript'> document.location ='dashboard_hv.php'; </script>";
} else {
echo "<script>alert('Tài khoản của bạn đã bị khóa, hãy liên hệ admin để biết thêm');</script>";

}
}

} 

else{
echo "<script>alert('Sai tài khoản hoặc mật khẩu.');</script>";
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
    <title>Hệ thống quản lý học viên | Đăng Nhập </title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
<?php include('includes/header.php');?>
<div class="content-wrapper">
<div class="container">
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line">ĐĂNG NHẬP</h4>
</div>
</div>
                      
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-info">
<div class="panel-heading">
 Đăng Nhập
</div>
<div class="panel-body">
<form role="form" method="post">

<div class="form-group">
<label>Nhập Mã Học Viên </label>
<input class="form-control" type="text" name="mshv" required autocomplete="off" />
</div>
<div class="form-group">
<label>Nhập Mật Khẩu</label>
<input class="form-control" type="matkhau" name="matkhau" required autocomplete="off"  />
</div>



 <button type="submit" name="login" class="btn btn-info">ĐĂNG NHẬP </button> 
</form>
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
