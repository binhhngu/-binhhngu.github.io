<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) { 
    header('location:index.php');
} else {
    // Query to get total number of students
    $sqlTotalStudents = "SELECT COUNT(*) AS total_students FROM hocvien";
    $queryTotalStudents = $dbh->prepare($sqlTotalStudents);
    $queryTotalStudents->execute();
    $totalStudents = $queryTotalStudents->fetch(PDO::FETCH_ASSOC);

    // Query to get total number of classes
    $sqlTotalClasses = "SELECT COUNT(*) AS total_classes FROM lop";
    $queryTotalClasses = $dbh->prepare($sqlTotalClasses);
    $queryTotalClasses->execute();
    $totalClasses = $queryTotalClasses->fetch(PDO::FETCH_ASSOC);

    // Query to get total number of courses
    $sqlTotalCourses = "SELECT COUNT(*) AS total_courses FROM khoahoc";
    $queryTotalCourses = $dbh->prepare($sqlTotalCourses);
    $queryTotalCourses->execute();
    $totalCourses = $queryTotalCourses->fetch(PDO::FETCH_ASSOC);

    // Query to get total number of certified students
    $sqlCertifiedStudents = "SELECT COUNT(*) AS certified_students FROM ketquathi WHERE ketqua = 1";
    $queryCertifiedStudents = $dbh->prepare($sqlCertifiedStudents);
    $queryCertifiedStudents->execute();
    $certifiedStudents = $queryCertifiedStudents->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản Lý Thư Viện | ADMIN</title>
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
                    <h4 class="header-line">TRANG QUẢN LÝ</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-success back-widget-set text-center">
                        <i class="fa fa-users fa-5x"></i>
                        <h3><?php echo htmlentities($totalStudents['total_students']);?></h3>
                        Số học viên
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-info back-widget-set text-center">
                        <i class="fa fa-graduation-cap fa-5x"></i>
                        <h3><?php echo htmlentities($totalClasses['total_classes']);?></h3>
                        Số lớp
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-book fa-5x"></i>
                        <h3><?php echo htmlentities($totalCourses['total_courses']);?></h3>
                        Số khóa học
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-danger back-widget-set text-center">
                        <i class="fa fa-certificate fa-5x"></i>
                        <h3><?php echo htmlentities($certifiedStudents['certified_students']);?></h3>
                        Số học viên đạt chứng chỉ
                    </div>
                </div>
            </div>
            <div class="row">
                <br><br><br><br><br><br><br><br>
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
