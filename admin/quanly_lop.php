<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_GET['del'])) {
        $malop=$_GET['del'];
        $sql = "delete from lop  WHERE malop=:malop";
        $query = $dbh->prepare($sql);
        $query->bindParam(':malop',$malop, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg']="Xóa lớp thành công ";
    }

    // Thực hiện câu truy vấn SQL
    $sql = "SELECT lop.malop, 
            khoahoc.tenkhoa, 
            lop.ngaykhaigiang, 
            lop.maphong, 
            giaovien.tengv, 
            lop.buoihoc, 
            lop.siso, 
            COUNT(hocvien.id) AS siso_hientai, 
            lop.trangthai, 
            lop.ghichu
            FROM lop
            LEFT JOIN khoahoc ON lop.makhoa = khoahoc.makhoa
            LEFT JOIN giaovien ON lop.magv = giaovien.magv
            LEFT JOIN hocvien ON lop.malop = hocvien.malop
            GROUP BY lop.malop";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // Cập nhật giá trị siso_hientai vào cột sisohientai trong cơ sở dữ liệu
    foreach($results as $result) {
        $malop = $result->malop;
        $siso_hientai = $result->siso_hientai;

        // Thực hiện câu truy vấn cập nhật
        $sql_update = "UPDATE lop SET sisohientai = :siso_hientai WHERE malop = :malop";
        $query_update = $dbh->prepare($sql_update);
        $query_update->bindParam(':siso_hientai', $siso_hientai, PDO::PARAM_INT);
        $query_update->bindParam(':malop', $malop, PDO::PARAM_STR);
        $query_update->execute();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Quản Lý Lớp Học</title>
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
                    <h4 class="header-line">Quản Lý Lớp</h4>
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
                        <div class="panel-heading">Danh sách lớp học</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã Lớp</th>
                                            <th>Tên Khóa Học</th>
                                            <th>Ngày Khai Giảng</th>
                                            <th>Phòng</th>
                                            <th>Giáo Viên</th>
                                            <th>Buổi Học</th>
                                            <th>Sỉ Số</th>
                                            <th>Sỉ Số Hiện Tại</th>
                                            <th>Trạng Thái</th>
                                            <th>Ghi Chú</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center"><?php echo htmlentities($result->malop);?></td>
                                            <td class="center"><?php echo htmlentities($result->tenkhoa);?></td>
                                            <td class="center"><?php echo htmlentities($result->ngaykhaigiang);?></td>
                                            <td class="center"><?php echo htmlentities($result->maphong);?></td>
                                            <td class="center"><?php echo htmlentities($result->tengv);?></td>
                                            <td class="center"><?php echo htmlentities($result->buoihoc);?></td>
                                            <td class="center"><?php echo htmlentities($result->siso);?></td>
                                            <td class="center"><?php echo htmlentities($result->siso_hientai);?></td>
                                            <td class="center">
                                                <?php if($result->trangthai==1) {?>
                                                    <a href="#" class="btn btn-success btn-xs">Đang Mở</a>
                                                <?php } else {?>
                                                    <a href="#" class="btn btn-danger btn-xs">Đã Đóng</a>
                                                <?php } ?>
                                            </td>
                                            <td class="center"><?php echo htmlentities($result->ghichu);?></td>
                                            <td class="center">
                                                <a href="sua_lop.php?malop=<?php echo htmlentities($result->malop);?>">
                                                    <button class="btn btn-primary"><i class="fa fa-edit"></i> Sửa</button>
                                                </a>
                                                <a href="quanly_lop.php?del=<?php echo htmlentities($result->malop);?>" onclick="return confirm('Bạn chắc chắn muốn xóa?');">
                                                    <button class="btn btn-danger"><i class="fa fa-pencil"></i> Xóa</button>
                                                </a>
                                                <a href="thongtin_lop.php?malop=<?php echo htmlentities($result->malop);?>">
                                                    <button class="btn btn-success"><i class="fa fa-pencil"></i> Xem thông tin</button>
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
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <!-- Thêm mã JavaScript sau để khởi tạo DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
        });
    </script>
</body>
</html>
<?php 
} 
?>
