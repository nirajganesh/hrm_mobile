<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Show Invoice</title>
	<meta name="description" content="The small framework with powerful features">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
	<link rel="stylesheet" href="<?=base_url('public/assets/css/style.css')?>"/>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Digikraft Social - Ankur">
    <!-- Favicon icon -->
    <?php // $settingsvalue = $this->settings_model->GetSettingsValue(); ?>
    <!-- <link rel="icon" type="image/ico" sizes="16x16" href="<?php // echo base_url(); ?>assets/images/favicon.png"> -->
    <!-- <title><?php // echo $settingsvalue->sitetitle; ?></title> -->
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>/public/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/assets/plugins/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>/public/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/assets/css/style.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>/public/assets/css/print.css" rel="stylesheet" media='print'>
    <!-- You can change the theme colors from here -->
    <link href="<?php echo base_url(); ?>/public/assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/public/assets/plugins/select2/dist/css/select2-bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/public/assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/public/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
	<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
    <link href="<?php echo base_url(); ?>/public/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Daterange picker plugins css -->
    <link href="<?php echo base_url(); ?>/public/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>/public/assets/plugins/jquery/jquery.min.js"></script>
     <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <link href="<?php echo base_url(); ?>/public/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />   
	<link href="<?php echo base_url(); ?>/public/assets/plugins/calendar/dist/fullcalendar.css" rel="stylesheet" type="text/css" />   
	<style>
		.container-fluid{
			padding-left:15px !important;
			padding-right:10px !important;
		}
		label.error, span.req{
			color:#ff7a7a;
		}
		.dtBox .dtpicker-button{
			color:white !important;
		}
        .readmore {
            font-size:14px;
        }
        .readmore .moreText {
            display:none;
        }
        .readmore a.more {
            display:block;
        }
	</style>

</head>
<style>
	td{
		vertical-align: middle !important;
	}
</style>
<body>
      <div class="page-wrapper"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-list" aria-hidden="true"></i> Payment receipt #<?= $receipt_no?></h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url('payment')?>">Payments</a></li>
                        <li class="breadcrumb-item active">Receipt #<?= $receipt_no?></li>
                    </ol>
                </div>
            </div>
            <div class="message"></div>
            <div class="container-fluid">
				<div class="row mt-3">
					<div class="col-12">
						<div class="card card-outline-info">
							<div class="card-header">
								<h4 class="m-b-0 text-white">
									<span class="" >
										<button id="print" onclick="printDiv('printableArea')" class="btn btn-primary" type="button"><span><i class="fa fa-print"></i> Print</span></button>
									</span>
									<a href="#" onclick="window.close();" class="float-right"><i class="fa fa-times"></i> close</a>
								</h4>
							</div>
							<div class="card-body">
							<section class="invoice">
								<div id="printableArea">
									<!-- title row -->
									<style>
										h1,h2,h3,h4,h5,h6,.h6,th,td,span,div .col {
											color: #222;
										}
										button span{
											color:white;
										}
									</style>
									<div class="row bg-light p-3">
										<div class="col-sm-8">
											<!-- <h2 class="page-header" style="border:0px;"> -->
												<div class="row align-items-center">
													<img src="<?= base_url("/public/assets/images/logo.svg"); ?>" height="100px">
													<div class="invoice-col ml-4">
														<address class="mb-0 text-dark">
															<h5 class="text-uppercase"><strong><?= $copyright?></strong></h5>
															<span class="mb-1 d-block"><i class="fa fa-phone"></i>&nbsp; <?= $contact; ?></span>
															<span class="mb-1 d-block"><i class="fa fa-envelope"></i>&nbsp; <?= $system_email; ?></span>
															<span class="mb-1 d-block"><i class="fa fa-globe"></i>&nbsp; www.digikraftsocial.com</span>
														</address>
													</div>
												</div>

											<!-- </h2> -->
										</div>
										<div class="col-sm-4">
											<div class="invoice-col">
												<address class="mb-0 text-dark">
													<h5 class="text-uppercase h4">PAYMENT RECEIPT</h5>
													<div class="row">
														<div class="col-4">Receipt no.</div>
														<div class="col">: #<?= $receipt_no; ?></div>
													</div>
													<div class="row">
														<div class="col-4">Payment Date</div>
														<div class="col">: <?= $payment_date; ?></div>
													</div>
												</address>
											</div>
										</div>
									</div>

									<div class="row mt-4">
										<div class="col-sm-4">
											<h6 class="">RECEIPT FOR -</h6>
											<address class="mb-0 mt-2 text-dark">
												<span><?= $name; ?></span>
												<br>
												<span>Contact : <?= $contact_no; ?></span>
												<br>
												<span>E-mail : <?= $email; ?></span>
											</address>
										</div>
										<div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-xs-12 table-responsive">
                                                    <table class="table table- table-full">
                                                        <tbody>
                                                            <tr>
                                                                <td>Description</td>
                                                                <td width="190px" class="text-right">Payment Amount</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="">
                                                                    <h4><?= $remarks?></h4>
                                                                </td>
                                                                <td class="text-right">
                                                                    <h5><?="₹".$amount?></h5>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- /.col -->
                                            </div>
										</div>
										<!-- /.col -->
									</div>

									<div class="row mt-3">
											<h6 class="col h6 text-right">Thank you for your business.</h6>
										<!-- /.col -->
									</div>
								</div>
								

								<!-- this row will not appear when printing -->
								<div class="row mt-4 no-print">
									<div class="col ">
										<button id="print" onclick="printDiv('printableArea')" class="btn btn-primary" type="button"><span><i class="fa fa-print"></i> Print</span></button>
									</div>
								</div>
							</section>
							</div>
						</div>
					</div>
				</div>



<!-- Main content -->
<!-- /.content -->
</body>

<script>
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;
        document.title='Receipt #<?//=$rcpt->receipt_no?>';
		window.print();

		document.body.innerHTML = originalContents;
	}
</script>
