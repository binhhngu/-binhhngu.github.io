<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
} else {
    try {
        if(isset($_POST['submit'])) {
            foreach($_POST['diemlt'] as $key => $value) {
                $idhv = $_POST['idhv'][$key];
                $makhoathi = $_POST['makhoathi'][$key];
                $diemlt = $_POST['diemlt'][$key];
                $diemth = $_POST['diemth'][$key];

                // Kiểm tra xem học viên đã có bản ghi điểm chưa
                $sql_ketqua_check = "SELECT * FROM ketquathi WHERE idhv = :idhv AND makhoathi = :makhoathi";
                $query_ketqua_check = $dbh->prepare($sql_ketqua_check);
                $query_ketqua_check->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                $query_ketqua_check->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                $query_ketqua_check->execute();
                $count = $query_ketqua_check->rowCount();

                if($count > 0) {
                    // Học viên đã có bản ghi điểm, thực hiện cập nhật
                    $sql_update = "UPDATE ketquathi SET diemlt = :diemlt, diemth = :diemth WHERE idhv = :idhv AND makhoathi = :makhoathi";
                    $query_update = $dbh->prepare($sql_update);
                    $query_update->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                    $query_update->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                    $query_update->bindParam(':diemlt', $diemlt, PDO::PARAM_STR);
                    $query_update->bindParam(':diemth', $diemth, PDO::PARAM_STR);
                    $query_update->execute();
                } else {
                    // Học viên chưa có bản ghi điểm, thực hiện thêm mới
                    $sql_insert = "INSERT INTO ketquathi (idhv, makhoathi, diemlt, diemth) VALUES (:idhv, :makhoathi, :diemlt, :diemth)";
                    $query_insert = $dbh->prepare($sql_insert);
                    $query_insert->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                    $query_insert->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                    $query_insert->bindParam(':diemlt', $diemlt, PDO::PARAM_STR);
                    $query_insert->bindParam(':diemth', $diemth, PDO::PARAM_STR);
                    $query_insert->execute();
                }
                if($diemlt >= 5 && $diemth >= 5) {
                    $ketqua = 1; // Nếu cả hai điểm đều lớn hơn hoặc bằng 5
                } else {
                    $ketqua = 0; // Nếu ít nhất một trong hai điểm nhỏ hơn 5
                }
                // Tiến hành cập nhật trường 'ketqua' trong bảng 'ketquathi'
                $sql_update_ketqua = "UPDATE ketquathi SET ketqua = :ketqua WHERE idhv = :idhv AND makhoathi = :makhoathi";
                $query_update_ketqua = $dbh->prepare($sql_update_ketqua);
                $query_update_ketqua->bindParam(':idhv', $idhv, PDO::PARAM_STR);
                $query_update_ketqua->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
                $query_update_ketqua->bindParam(':ketqua', $ketqua, PDO::PARAM_INT);
                $query_update_ketqua->execute();
            }
            $_SESSION['msg'] = "Đã cập nhật điểm thành công !!";
        }
    } catch (PDOException $e) {
        $_SESSION['error_msg'] = "Lỗi khi cập nhật điểm: " . $e->getMessage();
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
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Danh Sách Thi</h4>
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
                    <?php if($_SESSION['msg']!=""){?>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Success :</strong>
                            <?php echo htmlentities($_SESSION['msg']);?>
                            <?php echo htmlentities($_SESSION['msg']="");?>
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
                            <h3>Danh sách học viên khóa thi</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form method="post" action="">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mã Học Viên</th>
                                                <th>Họ và Tên</th>
                                                <th>Ngày Sinh</th>
                                                <th>Nơi Sinh</th>
                                                <th>Giới Tính</th>
                                                <th>Email</th>
                                                <th>Số Điện Thoại</th>
                                                <th>Mã Lớp</th>
                                                <th>Điểm Lý Thuyết</th>
                                                <th>Điểm Thực Hành</th>
                                                <th>Kết Quả</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $makhoathi = $_GET['makhoathi'];
                                            $sql = "SELECT hv.id, hv.mshv, hv.hoten, hv.malop, hv.ngaysinh, hv.noisinh, hv.gioitinh, hv.email, hv.sdt, dk.makhoathi, dk.trangthaiduyet,
                                            kq.diemlt, kq.diemth
                                            FROM dkthi dk
                                            INNER JOIN hocvien hv ON dk.idhv = hv.id
                                            LEFT JOIN ketquathi kq ON hv.id = kq.idhv AND dk.makhoathi = kq.makhoathi
                                            WHERE dk.trangthaiduyet = 1 AND dk.makhoathi = :makhoathi";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR); // Truyền giá trị của makhoathi vào câu truy vấn
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
                                                <td class="center"><?php echo htmlentities($result->ngaysinh);?></td>
                                                <td class="center"><?php echo htmlentities($result->noisinh);?></td>
                                                <td class="center"><?php echo ($result->gioitinh == 1) ? 'Nam' : 'Nữ';?></td>
                                                <td class="center"><?php echo htmlentities($result->email);?></td>
                                                <td class="center"><?php echo htmlentities($result->sdt);?></td>
                                                <td class="center"><?php echo htmlentities($result->malop);?></td>
                                                <td class="center">
                                                    <input type="hidden" name="idhv[]" value="<?php echo $result->id; ?>">
                                                    <input type="hidden" name="makhoathi[]" value="<?php echo $result->makhoathi; ?>">
                                                    <input type="text" name="diemlt[]" placeholder="Điểm lý thuyết" value="<?php echo htmlentities($result->diemlt); ?>" required />
                                                </td>
                                                <td class="center">
                                                    <input type="text" name="diemth[]" placeholder="Điểm thực hành" value="<?php echo htmlentities($result->diemth); ?>" required />
                                                </td>
                                                <td class="center">
                                            <?php 
                                            // Xác định kết quả dựa trên điểm lý thuyết và điểm thực hành
                                            $ketqua = ($result->diemlt >= 5 && $result->diemth >= 5) ? 'Đạt' : 'Không đạt';
                                            echo htmlentities($ketqua);
                                            ?>
                                        </td>
                                            </tr>
                                            <?php $cnt=$cnt+1;}} ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" name="submit" class="btn btn-primary">Lưu điểm</button>
                                </form>
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
    <!-- DATATABLES JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
        });
    </script>
</body>
</html>
