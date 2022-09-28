<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice service</title>
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
<body>
<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="mdi mdi-note-text"></i>  Invoices</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Invoices</li>
                    </ol>
                </div>
            </div>
            <?php //if($this->session->flashdata('feedback')){?>
                <div class="message d-block"><?//=$this->session->flashdata('feedback')?> </div>
            <?php //}?>
            <?php //if($this->session->flashdata('error')){?>
                <div class="message d-block bg-danger"><?//=$this->session->flashdata('error')?>  </div>
            <?php //}?>
            <div class="container-fluid">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header d-flex">
								<h4 class="m-b-0 text-white"><i class="mdi mdi-note-text"></i> Final  Invoices List </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="employees123" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Invoice no.</th>
                                                <th>Client</th>
                                                <th>Date</th>
                                                <th>Total Amt.</th>
                                                <th>Last payment on</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php //foreach($invoices as $c): ?>
                                            <tr>
                                                <td>
                                                    <?//= $c->inv_no ?>
                                                </td>
                                                <td><?//= $c->name ?> <br> (<?//=$c->person?>)</td>
                                                <td class="nowrap"><?//=date('d-m-Y',strtotime($c->inv_date)) ?></td>
                                                <td class="nowrap">₹ <?//= $c->total ?></td>
                                                <td class="nowrap"><?//=date('d-m-Y',strtotime($c->inv_date)) ?></td>
                                                <td style="min-width:200px">
                                                    <div class="readmore">
                                                    <?php // if(strlen($c->remarks)>100){?>
                                                        <? //= nl2br(substr($c->remarks,0,100))?>
                                                        <span class="ellipsis">...</span>
                                                        <span class="moreText"><? //= nl2br(substr($c->remarks,100))?></span> <br>
                                                        <a class="more"  href="javascript:;">Show more +</a>
                                                    <?php // } else{?>
                                                        <? //= nl2br($c->remarks)?>
                                                    <?php //}?>
                                                    </div>
                                                </td>
                                                <td class="jsgrid-align-center nowrap">
													<a class="btn btn-success btn-edit mr-1 btn-sm" target="_blank"
													href="<?php //echo base_url("invoice/showInvoice/$c->id?final=1"); ?>"><i class="fa fa-eye"></i></a>

													<a href="<?php //echo base_url();?>invoice/editInvoice/<?php //echo $c->id?>?final=1" title="Edit" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-pencil-square-o"></i></a>

													<a onclick="return confirm('Are you sure to delete this data?')"  href="<?php echo base_url();?>invoice/deleteInvoice/<?php //echo $c->id;?>" title="Reject invoice" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-ban"></i></a>
													
                                                    <a  href="<?php //echo base_url();?>invoice/sendInvoice/<?php //echo $c->id;?>/final" title="Send invoice by mail" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-envelope"></i></a>

                                                    
                                                    <a target="_blank" href="<?php //echo base_url("invoice/sendWhatsAppProforma/$c->id"); ?>" title="Send invoice by whatsapp" style="background:#41BF52" class="btn btn-sm btn-dark waves-effect waves-light"><i class="fa fa-whatsapp"></i></a>
                                                    
                                                    <?php //if($c->ref_quotation_id){?>
                                                        <br>
                                                        <small><a target="_blank" class="btn btn-default btn-sm mt-2" href="<?//= base_url("quotation/showQuotation/$c->ref_quotation_id")?>">See ref. quotation</a></small>
                                                    <?php //}?>
                                                </td>
                                            </tr>
                                            <?php //endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</body>
</html>
        
<script src="<?php echo base_url(); ?>/public/assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?php echo base_url(); ?>/public/assets/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?php echo base_url(); ?>/public/assets/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<!--Custom JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/js/custom.min.js"></script>

<!-- ============================================================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<!-- ============================================================== -->
<!--sparkline JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!--morris JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/raphael/raphael-min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/morrisjs/morris.js"></script>
<!-- Chart JS -->

<!-- <script src="<?php echo base_url(); ?>assets/js/dashboard1.js"></script> -->

<script src="<?php echo base_url(); ?>/public/assets/plugins/moment/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>  

<script src="<?php echo base_url(); ?>/public/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

<!-- Editable -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/jsgrid/db.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/public/assets/plugins/jsgrid/dist/jsgrid.min.js"></script>
<!-- This is data table -->

<script type="text/javascript" src="<?php echo base_url(); ?>/public/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>


<!-- Clock Plugin JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>                        
<!-- Date range Plugin JavaScript -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>  
<!-- end - This is for export functionality only -->
<script src="<?php echo base_url(); ?>/public/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/public/assets/plugins/multiselect/js/jquery.multi-select.js"></script>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/calendar/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>/public/assets/plugins/calendar/dist/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/public/assets/plugins/calendar/dist/cal-init.js"></script>
<script>
    $('#employees123').DataTable({
        "aaSorting": [],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
	$(document).ready(function(){
		<?php if(isset($_GET['client'])){
			$cl=urldecode($_GET['client']) ?>
    		$('#employees123').DataTable().search('<?=$cl?>').draw();
		<?php }?>
	});
</script>