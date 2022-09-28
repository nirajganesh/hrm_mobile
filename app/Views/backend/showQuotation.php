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

      <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fas fas-list" aria-hidden="true"></i> Quotation #<?= $quo_no?></h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?//=base_url('invoice')?>">Quotation</a></li>
                        <li class="breadcrumb-item active">Quotation #<?= $quo_no;?></li>
                    </ol>
                </div>
            </div>
            <div class="message"></div>
            <div class="container">
				<div class="row mt-3">
					<div class="col-12">
						<div class="card card-outline-info">
							<div class="card-header">
								<h4 class="m-b-0 text-white">
									<span class="" >
										<button id="print" onclick="printDiv('printableArea')" class="btn btn-primary" type="button"><span><i class="fa fa-print"></i> Print</span></button>
									</span>
									<a href="#" onclick="window.close();" class="float-right"><i class="fas fas-times"></i> close</a>
								</h4>
								<div class="mt-3">
									<input type="checkbox" id="hideTotal" onclick='hideTotal()'><label class="mr-5" for="hideTotal">Hide total section</label>
								</div>
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
									<div class="row bg-light p-3 align-items-center">
										<div class="col-sm-6">
											<h2 class="page-header font-weight-bold" style="border:0px;">QUOTATION</h2>
										</div>
										<div class="col-sm-6">
											<div class="invoice-col d-flex justify-content-end">
												<address class="mb-0 text-dark ml-2">
													<h5 class="text-muted">Quotation by:</h5>
													<h5 class="text-uppercase"><?= $copyright?></h5>
													<span class="d-block"><?= $address; ?></span>
													<span class="d-block"><?= '+91-'.$contact; ?></span>
													<span class="d-block"><?= $system_email; ?></span>
												</address>
                                                <img src="<?= base_url("/public/assets/images/logo.svg"); ?>" height="30px">
												<!-- <img src="<?= base_url("/public/assets/images/$sitelogo"); ?>" height="80px"> -->
											</div>
										</div>
									</div>

									<div class="row mt-4">
										<div class="col-sm-7 pl-4">
											<h5 class="text-muted">Quotation for:</h5>
											<address class="mb-0 mt-2 text-dark">
											
												<h5 class="text-uppercase"><?= $name?></h5>
												<span>Contact : <?= $contact//= strlen($client->contact_no)==10?'+91-'.$client->contact_no:$client->contact_no; ?></span>
												<br>
												<span>E-mail : <?= $email; ?></span>
											</address>
										</div>
										<div class="col-sm-4 ml-3 pl-3">
											<h5 class="text-muted">Quotation details:</h5>
											<address class=" mt-2 text-dark">
													<div class="row">
														<div class="col-5">Reference no.</div>
														<div class="col">: #<?= $quo_no; ?></div>
													</div>
												<div class="row">
													<div class="col-5">Date</div>
													<div class="col">: <?= $quo_date; ?></div>
												</div>
												<div class="row">
													<div class="col-5">Valid date</div>
													<div class="col">: <?= $valid_till; ?></div>
												</div>
											</address>
										</div>
										<!-- /.col -->
									</div>


									<!-- Table row -->
									<div class="row mt-5 px-3">
										<div class="col-xs-12 table-responsive">
											<table class="table table- table-full">
												<thead class="bg-light">
												<tr>
													<th>S. No.</th>
													<th>Service</th>
													<th>Price &nbsp; x &nbsp; Qty</th>
													<th width="100px" class="text-right">Subtotal</th>
												</tr>
												</thead>
												<tbody>
                                                <?php  $z=1;foreach ($quotation_item as $item):?>
														<tr>
															<!-- <td class="" style="width:100px"><?//= $z?>.</td> -->
															<td class="" style="width:100px"><?= $z?></td>
															<td class="">
															    <h6><?= $item['descr']?></h6>
																<p>- Catalogue/Brochure/Flyer(Flyer A5 - Front back Qty: 10000 Quality: Standard )</p>
															</td>
															<td class="">₹<?= $item['price']?> X <?= $item['qty']?></td>
															<td class="">₹<?= $item['subtotal']?></td>
														</tr>
													<?php  $z++; endforeach; ?>
											

												<!-- <tr>
													<th colspan="3" class="text-right">Subtotal:</th>
													<td class="text-right"><strong><?//= $settings->symbol . ' '; ?><?//= $quotation->sub_total; ?>/-</strong></td>
												</tr> -->
												<!-- <tr>
													<th colspan="3" class="text-right">GST (<?//= $quotation->gst; ?>%)</th>
													<td class="text-right"><?//= $settings->symbol . ' '; ?><?php // echo $quotation->sub_total * (($quotation->gst) / 100); ?>/-</td>
												</tr> -->
												<?php //if($quotation->discount!=0){?>
												<tr class="hideTogglable">
													<th colspan="3" class="text-right">Sub Total:</th>
													<td class="text-right"><strong><?= $symbol . ' '; ?><?= $sub_total?>/-</strong></td>
												</tr>
												<tr class="hideTogglable">
													<th colspan="3" class="text-right">Discount:</th>
													<td class="text-right"><strong>- <?= $symbol . ' '; ?><?= $discount?>/-</strong></td>
												</tr>
												<?php // }?>
												<tr class="hideTogglable">
													<th colspan="3" class="text-right ">Estimated Amount:</th>
													<td class="text-right h6"><?= $symbol . ' '; ?><?= $total?>/-</td>
												</tr>
												</tbody>
											</table>
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
									<hr>

									<div class="row mt-4">
										<div class="col-sm-6 ">
											<h6 class="">T&C -</h6>
											<address class=" mt-2 text-dark">
												<div class="row">
													<div class="col">
														- Cost estimate exclude applicable taxes.
														<br>
														- Any changes in above specifications require a written change order for work to be completed.
														<br>
														- Cost may increase due to additional, unexpected work.
													</div>
												</div>
											</address>
										</div>
										<div class="col-sm-1"></div>
										<div class="col-sm-5">
											<h6 class="text-right h6">Happy to serve you.</h6>
										</div>
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
</body>
<script>	 
	function hideTotal()
    {
        if($('#hideTotal').is(":checked"))   
            $(".hideTogglable").hide();
        else
            $(".hideTogglable").show();
    }
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
        document.title='Quotation #<?//=$quotation->quote_no?>';
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
