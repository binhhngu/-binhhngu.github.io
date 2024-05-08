<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
} else { 
    if(isset($_GET['makhoathi'])) {
        $idhv=$_SESSION['id']; 
        $makhoathi = $_GET['makhoathi'];
        $trangthaiduyet = 0; // Trạng thái mặc định là 0 khi đăng ký

        // Kiểm tra xem học viên đã đăng ký thi khóa này chưa
        $check_sql = "SELECT * FROM dkthi WHERE idhv = :idhv AND makhoathi = :makhoathi";
        $check_query = $dbh->prepare($check_sql);
        $check_query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
        $check_query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
        $check_query->execute();
        $count = $check_query->rowCount();

        if($count > 0) {
            echo '<script>alert("Bạn đã đăng ký thi khóa này rồi.")</script>';
            echo '<script>window.location="dangki_thi.php"</script>';
        } else {
            // Nếu chưa đăng ký thì thêm vào CSDL
            $sql = "INSERT INTO dkthi (idhv, makhoathi, trangthaiduyet) VALUES (:idhv, :makhoathi, :trangthaiduyet)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':idhv', $idhv, PDO::PARAM_STR);
            $query->bindParam(':makhoathi', $makhoathi, PDO::PARAM_STR);
            $query->bindParam(':trangthaiduyet', $trangthaiduyet, PDO::PARAM_INT);
            $query->execute();

            echo '<script>alert("Đăng ký thi thành công")</script>';
            echo '<script>window.location="dangki_thi.php"</script>';
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
    <title>Đăng Ký Thi</title>
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
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Đăng Ký Thi</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách khóa thi có thể đăng ký
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = "SELECT * FROM thi WHERE trangthai = 1";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) {
                                        ?>
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
                                                    $check_sql = "SELECT * FROM dkthi WHERE idhv = :idhv AND makhoathi = :makhoathi";
                                                    $check_query = $dbh->prepare($check_sql);
                                                    $check_query->bindParam(':idhv', $_SESSION['id'], PDO::PARAM_STR);
                                                    $check_query->bindParam(':makhoathi', $result->makhoathi, PDO::PARAM_STR);
                                                    $check_query->execute();
                                                    $count = $check_query->rowCount();

                                                    if($count > 0) {
                                                        // Nếu đã đăng ký, thay đổi nút thành "Đã đăng ký"
                                                        echo '<button class="btn btn-success" disabled><i class="fa fa-check"></i> Đã đăng ký</button>';
                                                    } else {
                                                        // Nếu chưa đăng ký, hiển thị nút "Đăng ký"
                                                        echo '<a href="dangki_thi.php?makhoathi='.$result->makhoathi.'" onclick="return confirm(\'Bạn có muốn đăng ký thi khóa này?\');"><button class="btn btn-primary"><i class="fa fa-edit"></i> Đăng Ký</button></a>';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php $cnt++; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>
