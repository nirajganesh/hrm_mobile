
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


<body>

<div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <!-- <h3 class="text-themecolor"><i class="fa fa-list" aria-hidden="true"></i> Invoice #<?//=$invoice->inv_no?></h3> -->
				<h3 class="text-themecolor"><i class="fa fa-list" aria-hidden="true"></i> Invoice #<?= $inv_no?></h3>  
				</div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <!-- <li class="breadcrumb-item"><a href="<?=base_url('invoice')?>">Invoice</a></li> -->
                        <li class="breadcrumb-item"><a>Invoice</a></li>
						<!-- <li class="breadcrumb-item active">Invoice #<?//=$invoice->inv_no?></li> -->
						<li class="breadcrumb-item active">Invoice #<?= $inv_no?></li>
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
									<span class="">
										<button id="print" onclick="printDiv('printableArea')" class="btn btn-primary" type="button"><span><i class="fa fa-print"></i> Print</span></button>
									</span>
									<a href="#" onclick="window.close();" class="float-right"><i class="fa fa-times"></i> close</a>
								</h4>
								<div class="mt-3">
									<input type="checkbox" id="hideStamp" onclick='hideStamp()'><label class="mr-5" for="hideStamp">Hide stamp</label>								
									<input type="checkbox" id="hideMnth" onclick='hideMnth()'><label class="mr-5" for="hideMnth">Hide invoice month</label>
									<input type="checkbox" id="hideDues" onclick='hideDues()'><label for="hideDues">Hide previous dues</label>
								</div>
							</div>
							<div class="card-body">
							<section class="invoice">
								<div id="printableArea">
									<!-- title row -->
									<style>
										h1,h2,h3,h4,h5,h6,.h6,th,td,span,div .col 
										{
											color: #222;
										}
										button span
										{
										 	color:white;
										}
									</style>
									<div class="row bg-light p-3 align-items-center">
										<div class="col-sm-6">
											<h1 class="page-header font-weight-bold" style="border:0px;">INVOICE 
												<span class="mnth" style="text-transform:uppercase" contentEditable ><?=date('M')?></span>
											</h1>
											 <?php if(isset($_GET['final'])) { ?>
											<span class="stamp"><img src="<?=base_url('/public/assets/images/paid.png')?>" alt="" height="30"></span>
											<?php } else { ?>
											<span class="stamp"><img src="<?=base_url('/public/assets/images/unpaid.png')?>" alt="" height="30"></span>
											<?php } ?> 
										</div>
										<div class="col-sm-6">
											<div class="invoice-col d-flex justify-content-end">
												<address class="mb-0 text-dark mr-5">
											      	<br>
													<h5 class="text-muted">Billed by:</h5>
													<!-- <h5 class="text-uppercase"><? //= $settings->company_name?></h5> -->
													<h5 class="text-uppercase"><?=$copyright;?></h5>
													<!-- <span class="d-block"><?//= $settings->address.' '.$settings->address2; ?></span> -->
													<span class="d-block"><?=$address;?></span>
													<span class="d-block">+91 <?=$contact;?>
						                            <span class="d-block"><?=$system_email;?>
													</span>
												</address>
												<img src="<?= base_url("/public/assets/images/logo.svg"); ?>" height="40px">
											</div>
										</div>
									</div>

									<div class="row mt-4">
										<div class="col-sm-7 pl-4">
											<h5 class="text-muted">Billed to:</h5>
											<address class="mb-0 mt-2 text-dark">
										
												<!-- <h5 class="text-uppercase"><?//= $client->name?></h5> -->
												<h5 class="text-uppercase"><?=$name?></h5>
												<!-- <span>Contact : <?//= strlen($client->contact_no)==10?'+91-'.$client->contact_no:$client->contact_no; ?></span> -->
												<span>Contact : +91 <?= $contact_no?></span>
												<br>
												<!-- <span>E-mail : <?//= $client->email; ?></span> -->
												<span>E-mail:- <?= $email?></span>
											</address>
										</div>
										<div class="col-sm-4 ml-4 pl-3">
											<h5 class="text-muted">Invoice details:</h5>
											<address class=" mt-2 text-dark">
												<?php if(isset($_GET['final'])) { ?>
													<div class="row">
														<div class="col-5">Invoice no. </div>
														<!-- <div class="col">: #<?//= $invoice->inv_no; ?></div> -->
														<div class="col"><?= $inv_no?></div>
													</div>
												<?php } ?>
												<div class="row">
													<div class="col-5">Invoice date</div>
													<!-- <div class="col">: <?//= date('d-m-Y', strtotime($invoice->inv_date)); ?></div> -->
													<div class="col-5"><?= $inv_date?></div>
												</div>
												<?php if(isset($_GET['final'])) { ?>
													<div class="row">
														<div class="col-5">Amount Paid:</div>
														<!-- <div class="col">: <?//= $settings->symbol; ?><?// = $invoice->total_paid; ?>/-</div> -->
													    <div class="col">₹<?= $total_paid?></div>
													</div>
												<?php } else { ?>
													<div class="row">
														<div class="col-5">Amount Paid:</div>
														<!-- <div class="col-7">: <?//= $settings->symbol; ?><?//= $invoice->total_paid; ?>/-</div> -->
														<div class="col-7">₹<?= $total_paid?></div>
														<div class="col-5">Amount due:</div>
														<!-- <div class="col-7">: <?//= $settings->symbol; ?><?//= $invoice->total_due; ?>/-</div> -->
														<div class="col-7">₹<?= $total_due?></div>
														<div class="col-5">Due date:</div>
														<div class="col-7"><?= $due_date?></div>
														<!-- <div class="col-7">: <?//= date('d-m-Y',strtotime($invoice->due_date)) ?></div> -->	
													</div>
												<?php } ?>
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
													<?php  $z=1;foreach ($invoice_item as $item):?>
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
													<td class="text-right"><strong><?//= $settings->symbol . ' '; ?><?//= $invoice->sub_total; ?>/-</strong></td>
												</tr> -->
												<!-- <tr>
													<th colspan="3" class="text-right">GST (<?//= $invoice->gst; ?>%)</th>
													<td class="text-right"><?//= $settings->symbol . ' '; ?><?php // echo $invoice->sub_total * (($invoice->gst) / 100); ?>/-</td>
												</tr> -->
											   
												<tr>
													<?php if(isset($_GET['final'])) { ?>
														<td colspan="2" class=" py-1 border-0">Total amount in words:</td>
														<th  class="text-right py-1">Total:</th>
														<!-- <td class="text-right py-1"><strong><?//= $settings->symbol . ' '; ?><?//= $invoice->total; ?>/-</strong></td> -->
													<?php } else { ?>
														<th colspan="3" class="text-right py-1">Total:</th>
														<td class="text-right py-1"><strong>₹<?= $total?>/-</strong></td>
														<!-- <td class="text-right py-1"><strong><?//= $settings->symbol . ' '; ?><?//= $invoice->total; ?>/-</strong></td> -->
													<?php } ?>
												</tr>
												
												
												<?php  if(isset($_GET['final'])) { ?>
													<tr>
														<th colspan="2" class=" py-1"><?//=$amtWords?></th>
														<th class="text-right">Status:</th>
														<td class="text-right h6">PAID	</td>
													</tr>
												<?php  } else { ?>
													<tr>
														<td colspan="2" class=" py-1 border-0">Total amount in words:<br><h6><?=AmountInWords($total)?></h6></td>
														<th class="text-right py-1">Amount Paid:</th>
														<td class="text-right py-1"><strong>₹<?= $total_paid?>/-</strong></td>
														<!-- <td class="text-right py-1"><?//= $settings->symbol . ' '; ?><?//= $invoice->total_paid; ?>/-</td> -->
													</tr>
													<tr class="">
														<th colspan="2" class=" py-1"><?//=$amtWords?></th>
														<th class="text-right py-1">Amount Due:</th>
														<td class="text-right py-1"><strong>₹<?= $total_due?>/-</strong></td>
														<!-- <td class="text-right h6 py-1"><?//= $settings->symbol . ' '; ?><?//= $invoice->total_due; ?>/-</td> -->
													</tr>
													<tr class="prevDue">
														<th colspan="2" class=" py-1"></th>
														<th class="text-right py-1">Prev. Due:</th>
														<td class="text-right py-1"><strong>₹0/-</strong></td>
														<!-- <td class="text-right h6 py-1"><?//= $settings->symbol . ' '; ?><?//= $invoice->prev_due; ?>/-</td> -->
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
									<hr>

									<div class="row mt-4">
										<div class="col-sm-8 bankQr" style="display:flex;">
											<div class="" style="flex-basis:100%">
												<h6 class="">BANK DETAILS -</h6>
												<address class=" mt-4 text-dark">
													<div class="row">
														<div class="col-5">Bank</div>
														<!-- <div class="">: <?//= $settings->bank_name; ?></div> -->
														<div class=""><?=$bank_name;?></div>
													</div>
													<div class="row">
														<div class="col-5 nowrap">Account name</div>
														<!-- <div class="">: <?//= $settings->bank_acc_name; ?></div> -->
														<div class=""><?=$account_name;?></div>
													</div>
													<div class="row">
														<div class="col-5">Account no.</div>
														<!-- <div class="">: <?//= $settings->bank_acc_no; ?></div> -->
														<div class=""><?=$account_number;?></div>
													</div>
													<div class="row">
														<div class="col-5">IFSC</div>
														<!-- <div class="">: <?//= $settings->bank_ifsc; ?></div> -->
														<div class=""><?=$ifsc;?></div>
													</div>
													<div class="row">
														<div class="col-5">UPI ID</div>
														<!-- <div class="">: <?//= $settings->bank_upi; ?></div> -->
														<div class=""><?=$upi_id;?></div>
													</div>
												</address>
											</div>
											
										</div>
										<div class="qr" style="margin-left: 20px;">
												<h6 class="">UPI SCAN & PAY</h6>
												<img src="<?=base_url('/public/assets/images/qr.png')?>" height="100" alt="">
											</div>
										<?php if(isset($_GET['final'])) { ?>
											<div class="col-sm-4 ">
												<h6>Additional notes -</h6>
												<address class="mt-2 text-dark">
													<div class="row">
														<div class="col">- Please quote invoice no. when remitting funds.</div>
													</div>
												</address>
											</div>
										<?php  } else { ?>
											<div class="col-sm-4 ">
												<h6 class="">T&C -</h6>
												<address class=" mt-2 text-dark">
													<div class="row">
														<div class="col">- Please pay within 10 days upon receipt of the invoice.</div>
													</div>
												</address>
												<h6 class="mt-4">Additional notes -</h6>
												<address class=" mt-2 text-dark">
													<div class="row">
														<div class="col">- Please quote invoice no. when remitting funds.</div>
													</div>
												</address>
											</div>
										<?php  } ?>
										<!-- /.col -->
									</div>

									<div class="row mt-3">
										<h5 class="col">Thank you for your business.</h5>
									</div>
								</div>
							
								<!-- this row will not appear when printing -->
								<div class="row mt-4 no-print">
									<div class="col">
										<button id="print" onclick="printDiv('printableArea')" class="btn btn-primary" type="button"><span><i class="fa fa-print"></i> Print</span></button>
									</div>
								</div>
							</section>
							</div>
						</div>
					</div>
				</div>

</div>

<footer class="footer"> © <?=date('Y')?> DigiKraft social </footer>

<script>
	function hideStamp()
    {
		Console.print("enter");
        if($('#hideStamp').is(":checked"))
            $(".stamp").hide();
        else
            $(".stamp").show();
    }

	function hideMnth()
    {
        if($('#hideMnth').is(":checked"))   
            $(".mnth").hide();
        else
            $(".mnth").show();
    }

	function hideDues()
    {
        if($('#hideDues').is(":checked"))   
            $(".prevDue").hide();
        else
            $(".prevDue").show();
    }

	function printDiv(divName) 
	{
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
        document.title='Invoice #<?//=isset($_GET['final']) ? $invoice->inv_no." (PAID)": $invoice->inv_no." (DUE)"?>';
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
</body>	
</html>



<!-- Main content -->


<!-- /.content -->
 <?php 
 // $this->load->view('backend/footer'); 
 function AmountInWords(float $amount)
{
   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $x < $count_length ) {
      $get_divider = ($x == 2) ? 10 : 100;
      $amount = floor($num % $get_divider);
      $num = floor($num / $get_divider);
      $x += $get_divider == 10 ? 1 : 2;
      if ($amount) {
       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
        }
   else $string[] = null;
   }
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}
 ?>




