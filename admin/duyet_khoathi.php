<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
} else {
    if(isset($_GET['makhoathi']) && isset($_GET['idhv']) ) {
        $makhoathi = $_GET['makhoathi'];
        $idhv = $_GET['idhv'];

        try {
            $sql = "UPDATE dkthi SET trangthaiduyet = 1 WHERE makhoathi=:makhoathi AND idhv=:idhv";
            $query = $dbh->prepare($sql);
            $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
            $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
            $query->execute();
            $_SESSION['msg'] = "Đã duyệt đăng ký thi thành công !!";
            header('location:duyet_khoathi.php');
        } catch (PDOException $e) {
            $_SESSION['error_msg'] = "Lỗi khi cập nhật trạng thái duyệt: " . $e->getMessage();
            header('location:duyet_khoathi.php'); // Chuyển hướng đến trang có thể hiển thị thông báo lỗi
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
    <title>Quản Lý Học Viên | Duyệt Đăng Ký Thi</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- DATATABLES CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Duyệt Đăng Ký Thi</h4>
                </div>
            </div>
            <div class="row">
                <?php if($_SESSION['error']!="") {?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error :</strong>
                        <?php echo htmlentities($_SESSION['error']);?>
                        <?php echo htmlentities($_SESSION['error']="");?>
                    </div>
                </div>
                <?php } ?>
                <?php if($_SESSION['msg']!="") {?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['msg']);?>
                        <?php echo htmlentities($_SESSION['msg']="");?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                    <div class="panel-heading">
                            <h3>
                            Danh sách học viên đăng kí khóa thi
                            <?php
                            $makhoathi = $_GET['makhoathi'];
                            echo $makhoathi;
                            ?>
                            </h3>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mã Học Viên</th>
                                            <th>Họ và Tên</th>
                                            <th>Mã Lớp</th>
                                            <th>Ngày Sinh</th>
                                            <th>Nơi Sinh</th>
                                            <th>Giới Tính</th>
                                            <th>Email</th>
                                            <th>Số Điện Thoại</th>
                                            <th>Trạng Thái Duyệt</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $sql = "SELECT hv.id, hv.mshv, hv.hoten, hv.malop, hv.ngaysinh, hv.noisinh, hv.gioitinh, hv.email, hv.sdt, dk.makhoathi, dk.trangthaiduyet
                                                    FROM dkthi dk
                                                    INNER JOIN hocvien hv ON dk.idhv = hv.id
                                                    WHERE dk.trangthaiduyet = 0";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
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
                                            <td class="center"><?php echo ($result->gioitinh == 1) ? 'Nam' : 'Nữ';?></td>
                                            <td class="center"><?php echo htmlentities($result->email);?></td>
                                            <td class="center"><?php echo htmlentities($result->sdt);?></td>
                                            <td class="center">
                                                <?php echo ($result->trangthaiduyet == 1) ? 'Đã duyệt' : 'Chưa duyệt';?>
                                            </td>
                                            <td class="center">
                                                <a href="duyet_khoathi.php?makhoathi=<?php echo htmlentities($result->makhoathi);?>&idhv=<?php echo htmlentities($result->id);?>"
                                                    onclick="return confirm('Bạn chắc chắn muốn duyệt cho học viên này?');">
                                                    <button class="btn btn-primary">Duyệt</button>
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
    <!-- DATATABLES SCRIPTS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <!-- SCRIPT TO INITIALIZE DATATABLES -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
        });
    </script>
</body>

</html>
<?php } ?>
