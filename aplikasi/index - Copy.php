<?php
session_start();
$action = $_GET['page'];
include '../config/koneksi.php';
include '../config/fungsi.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Aplikasi OCBS</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="../config/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../config/flora.datepicker.css" />
        <link rel="../config/stylesheet" href="../config/css/jquery.ui.all.css">
		<script src="../config/js/jquery-1.10.2.js"></script>
		<script src="../config/js/jquery.ui.core.js"></script>
		<script src="../config/js/jquery.ui.widget.js"></script>
		<script src="../config/js/jquery.ui.datepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="../config/css/demos.css">
		<link rel="stylesheet" type="text/css" href="../config/css/table.css">
		<link rel="stylesheet" type="text/css" href="../config/css/form.css">
		 
        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>




        <![endif]-->
        <link href="../config/css/styles.css" rel="stylesheet">
    </head>
    <body>

        <!-- Header -->
        <div id="top-nav" class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php?page=schedule&act=tampilschedule">Operational Car Booking System</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">

                        <li class="dropdown">
                            <a href="#"><?php echo $_SESSION['s_nama_employee']; ?></a>
                            <ul id="g-account-menu" class="dropdown-menu">
                              
                            </ul>
                        </li>
						<li><a href="logout.php"></span>Logout</a></li>
                        </ul>
                </div>
            </div>
			
			
			
			
			<!-- /container -->
        </div>
        <!-- /Header -->

        <!-- Main -->
        <div class="container">
            <div class="row">
                <!-- /col-3 -->
                <div class="">

                    <div >
                        <div class="">
                            <div class="panel panel-default">
                                <!-- Bagian isi content -->
                                

                                <?php
                                menubutton();
                                
                                if (($action) == 'mobil') {
                                    include('master/master.php');
                                } 
								
								else if (($action) == 'booking') {
                                    include('booking/booking.php');
                                } 
								
								else if (($action) == 'laporan') {
                                    include('laporan/laporan.php');
                                } 
								
								else if (($action) == 'schedule') {
                                    include('schedule/schedule.php');
                                } 
                                ?>

                            </div>

                        </div>
                    </div>
                </div><!--/col-span-9-->
            </div>
        </div>
        <!-- /Main -->

        <footer class="text-center"><b>Operational Car Booking System &copy; 2016</b>
            <br></footer>

        <div class="modal" id="addWidgetModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Tentang Program</h4>
                    </div>
                    <div class="modal-body">
                        <p>Add a widget stuff here..</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" data-dismiss="modal" class="btn">Close</a>
                        <a href="#" class="btn btn-primary">Save changes</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dalog -->
        </div><!-- /.modal -->




        <!-- script references -->
        <script src="../config/js/bootstrap.min.js"></script>
        <script src="../config/js/scripts.js"></script>
    </body>
</html>
