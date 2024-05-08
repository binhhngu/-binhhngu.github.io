<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['update']))
{    
$id=$_SESSION['id'];  
$hoten=$_POST['hoten'];
$ngaysinh=$_POST['ngaysinh'];
$noisinh=$_POST['noisinh'];
$sdt=$_POST['sdt'];
$email=$_POST['email'];

$sql="update hocvien set hoten=:hoten,ngaysinh=:ngaysinh,noisinh=:noisinh,sdt=:sdt,email=:email where id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->bindParam(':hoten',$hoten,PDO::PARAM_STR);
$query->bindParam(':ngaysinh',$ngaysinh,PDO::PARAM_STR);
$query->bindParam(':noisinh',$noisinh,PDO::PARAM_STR);
$query->bindParam(':sdt',$sdt,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Thông tin tài khoản đã được cập nhật")</script>';
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Hệ thống quản lý học viên | Thông tin tài khoản</title>
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
                <h4 class="header-line">Thông tin tài khoản</h4>
                
                            </div>

        </div>
             <div class="row">
           
<div class="col-md-9 col-md-offset-1">
               <div class="panel panel-danger">
                        <div class="panel-heading">
                          Thay Đổi Thông Tin 
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post">
<?php 
$id=$_SESSION['id'];
$sql="SELECT mshv,hoten,malop,ngaysinh,noisinh,gioitinh,email,sdt,trangthai from  hocvien  where id=:id ";
$query = $dbh -> prepare($sql);
$query-> bindParam(':id', $id, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  

<div class="form-group">
<label>Mã học viên : </label>
<?php echo htmlentities($result->mshv);?>
</div>


<div class="form-group">
<label>Mã lớp : </label>
<?php echo htmlentities($result->malop);?>
</div>


<div class="form-group">
<label>Trạng thái tài khoản: </label>
<?php if($result->trangthai==1){?>
<span style="color: green">Active</span>
<?php } else { ?>
<span style="color: red">Blocked</span>
<?php }?>
</div>


<div class="form-group">
<label>Họ và Tên</label>
<input class="form-control" type="text" name="hoten" value="<?php echo htmlentities($result->hoten);?>" autocomplete="off" required />
</div>

<div class="form-group">
<label>Ngày sinh</label>
<input class="form-control" type="date" name="ngaysinh" value="<?php echo htmlentities($result->ngaysinh);?>" autocomplete="off" required />
</div>

<div class="form-group">
<label>Nơi sinh</label>
<input class="form-control" type="text" name="noisinh" value="<?php echo htmlentities($result->noisinh);?>" autocomplete="off" required />
</div>


<div class="form-group">
<label>Số Điện Thoại:</label>
<input class="form-control" type="text" name="sdt" maxlength="11" value="<?php echo htmlentities($result->sdt);?>" autocomplete="off" required />
</div>
                                        
<div class="form-group">
<label>Email :</label>
<input class="form-control" type="text" name="email"  value="<?php echo htmlentities($result->email);?>" autocomplete="off" required />
</div>
<?php }} ?>
                              
<button type="submit" name="update" class="btn btn-primary" id="submit">Thay đổi </button>

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
<?php } ?>
