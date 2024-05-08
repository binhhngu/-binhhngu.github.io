<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
                <img src="assets/img/logo.png" />
            </a>
        </div>
        <?php if(isset($_SESSION['login']) && $_SESSION['login']) { ?>
            <div class="right-div">
                <a href="logout.php" class="btn btn-danger pull-right">ĐĂNG XUẤT</a>
            </div>
        <?php } ?>
    </div>
</div>
<!-- LOGO HEADER END-->
<?php if(isset($_SESSION['login']) && $_SESSION['login']) { ?>
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="dashboard_hv.php" class="menu-top-active">TRANG CHỦ</a></li>
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> TÀI KHOẢN <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="my-profile.php">Tài khoản của tôi</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> ĐĂNG KÍ THI <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="dangki_thi.php">Đăng kí thi</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="danhsach_dkthi.php">Các khóa thi đã đăng kí</a></li>
                                </ul>
                                
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="adminlogin.php">ĐĂNG NHẬP ADMIN</a></li>
                            <li><a href="dangki.php">ĐĂNG KÝ KHÓA HỌC</a></li>
                            <li><a href="index.php">ĐĂNG NHẬP</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
