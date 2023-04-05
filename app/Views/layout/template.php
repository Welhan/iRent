<?php
$menus = generateMenu(session('userID'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="WProject" content="" />
    <title>iRent</title>
    <!-- CSS Fontawsome -->
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/fontawesome.css">
    <!-- Select2 -->
    <link href="assets/bootstrap-5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/select2/css/select2.min.css">
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/datatable/datatables.css" rel="stylesheet" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" /> -->
    <script src="assets/font-awesome/all.js"></script>


</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/">LinkedRent</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav me-lg-4 ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php foreach ($menus as $menu) : ?>
                            <div class="sb-sidenav-menu-heading"><?= strtoupper($menu->menu); ?></div>
                            <?php $submenu = generateSubmenu($menu->id, session('userID')); ?>
                            <?php foreach ($submenu as $sub) : ?>
                                <a class="nav-link" href="<?= $sub->link; ?>">
                                    <div class="sb-nav-link-icon"><i class="<?= $sub->icon; ?>"></i></div>
                                    <?= ucwords($sub->submenu); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= ucwords(user_profile(session('userID'))->nama); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <!-- Alert Jika Client Sudah Mau Expired -->
                <div class="container-fluid px-4">
                    <?php if (check_Expired(session('userID'))) : ?>
                        <div class="row">
                            <div class="col-lg">
                                <div class="alert alert-warning mt-3" role="alert">
                                    A simple warning alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Akhir Alert Expired -->

                    <?= $this->renderSection('content'); ?>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2022</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.4.min.js"></script>
    <script src="assets/select2/js/select2.full.min.js"></script>
    <script src="assets/bootstrap-5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/datatable/datatables.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
    <!-- <script src="assets/js/datatables-simple-demo.js"></script> -->

    <?= $this->renderSection('javascript'); ?>
</body>

</html>