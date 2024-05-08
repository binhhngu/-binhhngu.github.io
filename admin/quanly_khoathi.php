<?php
session_start();
error_reporting(0);
include('includes/config.php');

function capNhatTrangThaiThi($dbh) {
    $today = date("Y-m-d");
    $sql = "UPDATE thi SET trangthai = 0 WHERE ngaythi < :today";
    $query = $dbh->prepare($sql);
    $query->bindParam(':today', $today, PDO::PARAM_STR);
    $query->execute();
}

function capNhatTrangThaiDk($dbh) {
    $today = date("Y-m-d");
    $sql = "UPDATE thi SET trangthai = CASE WHEN hanchotdk < :today THEN -1 ELSE 1 END";
    $query = $dbh->prepare($sql);
    $query->bindParam(':today', $today, PDO::PARAM_STR);
    $query->execute();
}


if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else { 
    if(isset($_GET['del'])) {
        $makhoathi = $_GET['del'];
        $sql = "DELETE FROM thi WHERE makhoathi=:makhoathi";
        $query = $dbh->prepare($sql);
        $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg'] = " Xóa khóa thi thành công !!";
        // header('location:quanly_khoathi.php');
    }
capNhatTrangThaiDk($dbh);
capNhatTrangThaiThi($dbh);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Học Viên | Quản Lý Khóa Thi</title>
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
                    <h4 class="header-line">Quản Lý Khóa Thi</h4>
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


            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách khóa thi
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã Khóa Thi</th>
                                            <th>Tên Chứng Chỉ/Chứng Nhận</th>
                                            <th>Hội Đồng Thi</th>
                                            <th>Địa Điểm Thi</th>
                                            <th>Ngày Thi</th>
                                            <th>Hạn Chót Đăng Kí</th>
                                            <th>Trạng Thái</th>
                                            <th>Số học viên thi</th>
                                            <th>Số đăng kí chưa duyệt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from  thi";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center"><?php echo htmlentities($result->makhoathi);?></td>
                                            <td class="center"><?php echo htmlentities($result->chungchi);?></td>
                                            <td class="center"><?php echo htmlentities($result->hoidongthi);?></td>
                                            <td class="center"><?php echo htmlentities($result->diadiem);?></td>
                                            <td class="center"><?php echo htmlentities($result->ngaythi);?></td>
                                            <td class="center"><?php echo htmlentities($result->hanchotdk);?></td>
                                            <td class="center">
                                                <?php 
        if($result->trangthai == 1) {
            echo "Đang mở đăng kí";
        } elseif($result->trangthai == 0) {
            echo "Đã thi";
        } elseif($result->trangthai == -1) {
            echo "Đã đóng đăng kí";
        } else {
            echo "Không xác định";
        }
    ?>
                                            </td>
                                            <td class="center">
                                                <?php
    // Lấy makhoathi từ khóa GET
    $makhoathi = $result->makhoathi;
    
    // Truy vấn SQL để đếm số học viên thi cho makhoathi được chỉ định
    $sql_count = "SELECT COUNT(*) AS count FROM dkthi WHERE trangthaiduyet = 1 AND makhoathi = :makhoathi";
    $query_count = $dbh->prepare($sql_count);
    $query_count->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
    $query_count->execute();
    $result_count = $query_count->fetch(PDO::FETCH_ASSOC);
    $hocvien_thi = $result_count['count'];

    // Hiển thị số học viên thi
    echo htmlentities($hocvien_thi);
    ?>
                                            </td>
                                            <td class="center">
                                                <?php
    // Lấy makhoathi từ khóa GET
    $makhoathi = $result->makhoathi;
    
    // Truy vấn SQL để đếm số học viên thi cho makhoathi được chỉ định
    $sql_count = "SELECT COUNT(*) AS count FROM dkthi WHERE trangthaiduyet = 0 AND makhoathi = :makhoathi";
    $query_count = $dbh->prepare($sql_count);
    $query_count->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
    $query_count->execute();
    $result_count = $query_count->fetch(PDO::FETCH_ASSOC);
    $hocvien_thi = $result_count['count'];

    // Hiển thị số học viên thi
    echo htmlentities($hocvien_thi);
    ?>
                                            </td>



                                            <td class="center">
                                                <a
                                                    href="sua_khoathi.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>">
                                                    <button class="btn btn-primary"><i class="fa fa-edit"></i>
                                                        Sửa</button>
                                                </a>
                                                <a href="quanly_khoathi.php?del=<?php echo htmlentities($result->makhoathi);?>"
                                                    onclick="return confirm('Bạn đồng ý xóa ? ');">
                                                    <button class="btn btn-danger"><i class="fa fa-pencil"></i>
                                                        Xóa</button>
                                                </a>
                                                <a
                                                    href="danhsach_thi.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>">
                                                    <button class="btn btn-light"><i class="fa fa-check"></i> Danh Sách
                                                        Thi</button>
                                                </a>
                                                <!-- Hiển thị nút duyệt nếu trạng thái khác 0 -->
                                                <?php if($result->trangthai != 0) { ?>
                                                <a
                                                    href="duyet_khoathi.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>">
                                                    <button class="btn btn-success"><i class="fa fa-check"></i> Duyệt
                                                        đăng kí thi</button>
                                                </a>
                                                <?php } else { ?>
                                                <a
                                                    href="thongke_ketqua.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>">
                                                    <button class="btn btn-info"><i class="fa fa-chart-bar"></i> Thống
                                                        kê kết quả</button>
                                                </a>
                                                <?php } ?>
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
</body>

</html>
<?php } ?>