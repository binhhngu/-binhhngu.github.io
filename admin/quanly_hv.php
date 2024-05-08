<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else {
    if(isset($_GET['del'])) {
        $id = $_GET['del'];
        
        // Lấy mã lớp của học viên
        $sql = "SELECT malop FROM hocvien WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $malop = $result->malop;
        
        // Xóa học viên
        $sql = "DELETE FROM hocvien WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        
        // Giảm sisohientai đi 1
        if($query->rowCount() > 0) {
            $sql_update = "UPDATE lop SET sisohientai = sisohientai - 1 WHERE malop=:malop";
            $query_update = $dbh->prepare($sql_update);
            $query_update->bindParam(':malop', $malop, PDO::PARAM_STR);
            $query_update->execute();
        }
        
        $_SESSION['delmsg'] = "Xóa học viên thành công";
        header('location:quanly_hv.php');
    }
    if(isset($_GET['inid']))
{
$id=$_GET['inid'];
$status=0;
$sql = "update hocvien set trangthai=:trangthai  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':trangthai',$trangthai, PDO::PARAM_STR);
$query -> execute();
header('location:quanly_hv.php');
}



//code for active students
if(isset($_GET['id']))
{
$id=$_GET['id'];
$trangthai=1;
$sql = "update hocvien set trangthai=:trangthai  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':trangthai',$trangthai, PDO::PARAM_STR);
$query -> execute();
header('location:quanly_hv.php');
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
    <title>Ouản Lý Học Viên </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                    <h4 class="header-line">Quản lý học viên</h4>
                </div>
                <div class="row">
                    <?php if($_SESSION['error']!="")
    {?>
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            <strong>Error :</strong>
                            <?php echo htmlentities($_SESSION['error']);?>
                            <?php echo htmlentities($_SESSION['error']="");?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($_SESSION['msg']!="")
{?>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Success :</strong>
                            <?php echo htmlentities($_SESSION['msg']);?>
                            <?php echo htmlentities($_SESSION['msg']="");?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($_SESSION['updatemsg']!="")
{?>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Success :</strong>
                            <?php echo htmlentities($_SESSION['updatemsg']);?>
                            <?php echo htmlentities($_SESSION['updatemsg']="");?>
                        </div>
                    </div>
                    <?php } ?>


                    <?php if($_SESSION['delmsg']!="")
    {?>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Success :</strong>
                            <?php echo htmlentities($_SESSION['delmsg']);?>
                            <?php echo htmlentities($_SESSION['delmsg']="");?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Danh sách học viên
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mã Học Viên</th>
                                                <th>Họ Tên</th>
                                                <th>Mã Lớp</th>
                                                <th>Ngày Sinh</th>
                                                <th>Nơi sinh</th>
                                                <th>Giới Tính</th>
                                                <th>Email</th>
                                                <th>Số Điện Thoại</th>
                                                <th>Trạng Thái</th>
                                                <th>Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                        $sql = "SELECT * FROM hocvien";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) {
                                        ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt);?></td>
                                                <td class="center"><?php echo htmlentities($result->mshv);?></td>
                                                <td class="center"><?php echo htmlentities($result->hoten);?></td>
                                                <td class="center"><?php echo htmlentities($result->malop);?></td>
                                                <td class="center"><?php echo htmlentities($result->ngaysinh);?></td>
                                                <td class="center"><?php echo htmlentities($result->noisinh);?></td>
                                                <td><?php echo ($result->gioitinh == 1) ? 'Nam' : 'Nữ';?></td>
                                                <td class="center"><?php echo htmlentities($result->email);?></td>
                                                <td class="center"><?php echo htmlentities($result->sdt);?></td>
                                                <td class="center"><?php if($result->trangthai==1)
                                            {
                                                echo htmlentities("Đang Hoạt Động");
                                            } else {


                                            echo htmlentities("Đã Bị Chặn");
}
                                            ?></td>
                                                
                                                <td class="center">
                                                <?php if($result->trangthai==1)
 {?>
                                                    <a href="quanly_hv.php?inid=<?php echo htmlentities($result->id);?>"
                                                        onclick="return confirm('Bạn có chắc muốn chặn người này?');"" >  <button class="
                                                        btn btn-warning"> Chặn</button>
                                                        <?php } else {?>
                                                <a href="quanly_hv.php?id=<?php echo htmlentities($result->id);?>"
                                                            onclick="return confirm('Bạn có muốn bỏ chặn người này?');""><button class="
                                                            btn btn-primary"> Bỏ Chặn</button>
                                                            <?php } ?>
                                                    <a href="sua_hv.php?idhv=<?php echo htmlentities($result->id);?>">
                                                        <button class="btn btn-primary"><i class="fa fa-edit"></i> Chỉnh
                                                            Sửa</button>
                                                    </a>

                                                    <a href="quanly_hv.php?del=<?php echo htmlentities($result->id);?>"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa?');">
                                                        <button class="btn btn-danger"><i class="fa fa-pencil"></i>
                                                            Xóa</button>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $cnt=$cnt+1;}} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--End Advanced Tables -->
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
        <!-- DATATABLE SCRIPTS  -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>

        <!-- Thêm mã JavaScript sau để khởi tạo DataTables -->
        <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
        });
        </script>
</body>

</html>