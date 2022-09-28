<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice</title>
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
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fas fas-university" aria-hidden="true"></i> Payslip</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Home</a></li>
                        <li class="breadcrumb-item active"> Invoice</li>
                    </ol>
                </div>
            </div>
            <style type="text/css">
                table.table.table-hover thead{
                    background-color: #e8e8e8;
                }
            </style>
            <div class="container-fluid">
                <div class="row m-b-10 mt-3"> 
                    <div class="col-12">
<!--                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#TypeModal" data-whatever="@getbootstrap" class="text-white TypeModal"><i class="" aria-hidden="true"></i> Add Payroll </a></button>-->
                       <a href="<?php echo base_url(); ?>Payroll/Salary_List" class="btn btn-secondary"><i class="fas fas-arrow-left"></i>  Back</a>
                        <button type="button" class="btn btn-primary print_payslip_btn ml-2"><i class="fas fas-print"></i><i class="" aria-hidden="true" onclick="printDiv()"></i>  Print</button>
                    </div>
                </div> 

                <div class="row payslip_print mt-3" id="payslip_print" contenteditable="true">
                    <div class="col-md-12">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-4 col-xs-6 col-sm-6">
                                <img src="<?php echo base_url();?>/public/assets/images/logo.svg" alt="DKS" height="45" class="DRI-logo" />
                                    <!-- <img src="<?php echo base_url();?>public/assets/images/dri_Logo.png" style=" width:80px; margin-right: 10px;" /> -->
                                </div>
                                <div class="col-md-8 col-xs-6 col-sm-6 text-left payslip_address" style="font-weight:400">
                                    <p>
                                        <?= $address; ?>
                                    </p>
                                    <!-- <p>
                                        <?php // echo $settingsvalue->address2; ?>
                                    </p> -->
                                    <p>
                                        Phone: <?= $phone; ?>, Email: <?= $email; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <h5 style="margin-top: 15px;">Payslip for the period of <?= $month.' '.$year ?></h5>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-md-12"><!-- 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php //$obj_merged = (object) array_merge((array) $employee_info, (array) $salaryvaluebyid, (array) $salarypaybyid, (array) $salaryvalue, (array) $loanvaluebyid); print_r($obj_merged); ?>
                                            <?php //print_r($otherInfo[0]); ?>
                                        </div>
                                    </div> -->
                                    <table class="table table-condensed borderless payslip_info">
                                        <tr>
                                            <td>Employee Code</td>
                                            <td>: <?= $emp_id; ?></td>
                                            <td>Pay Date</td>
                                            <td>: <?= $paid_date ?></td>
                                        </tr>
                                        <tr>
                                            <td>Employee Name</td>
                                            <td>: <?= $emp_name; ?> <?php // echo $obj_merged->last_name; ?></td>
                                            <td>Pay Type</td>
                                            <td>: <?= $paid_type; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Designation</td>
                                            <td>: <?= $des_name ?></td>
											<td></td>
											<td></td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>: <?= $dep_name; ?></td>
											<td></td>
											<td></td>

                                            <!-- <td>Days Worked</td> -->
                                            <td></td>
                                            <td>
                                                <!-- <?php
                                                  // $days = ceil($salary_info->total_days / 8);
                                                  // echo $days;
                                                ?> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date of Joining</td>
                                            <td>: <?= $em_joining_date; ?></td>
											<td></td>
											<td></td>
                                        </tr>
                                        <?php if(!empty($bankinfo->bank_name)){ ?>
                                        <tr>
                                            <td>Account Name</td>
                                            <td>: <?php //echo $bankinfo->holder_name; ?></td>
                                            <td>Account Number</td>
                                            <td>: <?php //echo $bankinfo->account_number; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <style>
                                .table-condensed>thead>tr>th, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>tbody>tr>td, .table-condensed>tfoot>tr>td { padding: 2px 5px; }
                            </style>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-condensed borderless" style="border-left: 1px solid #ececec;">
                                        <thead class="thead-light" style="border: 1px solid #ececec;">
                                            <tr style="font-weight:400">
                                                <th>Description</th>
                                                <th class="text-right">Earnings</th>
                                                <th class="text-right">Deductions</th>
                                            </tr>
                                        </thead>
                                        <tbody style="border: 1px solid #ececec;">
                                            <tr style="font-weight:400">
                                                <td>Basic Salary</td>
                                                <td class="text-right">₹ <?= $basic; ?> </td>
                                                <td class="text-right">₹0  </td>
                                            </tr>
                                            <tr style="font-weight:400">
                                                <td>Madical Allowance</td>
                                                <td class="text-right">₹ <?php //echo $addition[0]->medical; ?> </td>
                                                <td class="text-right">₹0  </td>
                                            </tr>
                                            <tr style="font-weight:400">
                                                <td>House Rent</td>
                                                <td class="text-right">₹ <?php //echo $addition[0]->house_rent; ?> </td>
                                                <td class="text-right"> ₹0 </td>
                                            </tr>
                                            <tr style="font-weight:400">
                                                <td>Conveyance Allowance</td>
                                                <td class="text-right">₹ <?php //echo $addition[0]->conveyance; ?> </td>
                                                <td class="text-right"> ₹0 </td>
                                            </tr>
                                            <tr style="font-weight:400">
                                                <td>Bonus</td>
                                                <td class="text-right">₹ <?= $bonus; ?> </td>
                                                <!-- <td class="text-right">₹<?//=$salary_info->bonus?$salary_info->bonus:"0" ?></td> -->
                                                <td class="text-right">₹0</td>
                                            </tr>
                                            <tr style="font-weight:400">
                                                <td>Loan</td>
                                                <td class="text-right">₹0 </td>
                                                <td class="text-right"><?php //if(!empty($salary_info->loan)) {
                                                    //echo "₹ ".$salary_info->loan . "";
                                               // } else{ echo "₹0";  } ?> </td>
                                            </tr>
                                            <!-- <tr>
                                                <td>Working Hour (<?php //echo $salary_info->total_days; ?> hrs)</td>
                                                <td class="text-right">
                                                    <?php
                                                       // if($a > 0) { echo'₹ ' .round($a,2).''; }
                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                      //  if($d > 0) { echo'₹ '. round($d,2).''; }
                                                    ?>        
                                                </td>
                                                <td class="text-right"> </td>
                                            </tr> -->
                                            <!--<tr>
                                                <td>Without Pay( <?php //echo $work_h_diff ?> hrs)</td>
                                                <td class="text-right"> </td>
                                                <td class="text-right"> <?php
                                                        /*if($d > 0) { echo '₹ '. round($d,2).''; }*/
                                                       // echo '₹ '.$salary_info->diduction .'';
                                                    ?> </td>
                                                
                                            </tr>-->
                                            <tr style="font-weight:400">
                                                <td>Tax</td>
                                                <td class="text-right"> </td>
                                                <td class="text-right"> </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="tfoot-light">
                                            <tr>
                                                <th>Total</th>
                                                <th class="text-right">₹ <?= $total_pay//$total_add = $salary_info->basic + $salary_info->medical + $salary_info->house_rent + $salary_info->bonus+$a; echo round($total_add,2); ?> </th>
                                                <th class="text-right">₹ <?php //$total_did = $salary_info->loan+$salary_info->diduction; echo round($total_did,2); ?></th>
                                            </tr>
                                            <tr>
												<th colspan="3">&nbsp</th>
                                            </tr>
                                            <tr class="border-0">
                                                <th class="h6">Net Pay</th>
                                                <!-- <th class="text-right"></th> -->
                                                <th colspan="2" class="text-right h6">₹ <?= $total_pay //echo $salary_info->total_pay/*round($total_add - $total_did,2)*/; ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div><!-- 
                            <div class="row" style="margin-top: 25px;">
                                <div class="col-md-6">
                                    _____________________________________
                                    <br>
                                    Employer's Signature
                                </div>
                                <div class="col-md-6 text-right">
                                    _____________________________________
                                    <br>
                                    Employee's Signature
                                </div>
                            </div> -->
                        </div>
                        <!-- <div class="card card-body printableArea">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left " style="height:80px;margin-left:10px;">
                                        <img src="<?php echo base_url();?>assets/images/dri_Logo.png" style="position:absolute; top:0; left:0;width:250px;margin-left:15px;" />
                                    </div>
                                    <div class="pull-right">
                                        <h4 class="pull-right">Pay Slip for the period of <?php //echo $salary_info->month;?> 2018</h4>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
                                            <p class="text-muted m-l-5">Employee PIN: <?php //echo $employee_info->em_code;?>
                                                <br/> Department:  <?php //echo $employee_info->dep_name;?>
                                                <br/> Payment Date: <?php //echo $salary_info->paid_date;?></p>
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
                                            <p class="text-muted m-l-30">Employee Name:  <?php //echo $employee_info->first_name .' '. $employee_info->last_name;?>
                                                <br/> Designation:   <?php //echo $employee_info->des_name;?>
                                                <br/> Month:  <?php //echo $salary_info->month;?></p>
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-right">Earning</th>
                                                    <th class="text-right">Deduction</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Basic Salary</td>
                                                    <td class="text-right"> <?php //echo $salaryvaluebyid->basic;?> BDT</td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Madical</td>
                                                    <td class="text-right"> <?php //echo $salaryvaluebyid->medical;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>House Rent</td>
                                                    <td class="text-right"> <?php //echo $salaryvaluebyid->house_rent;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Conveyance</td>
                                                    <td class="text-right"> <?php //echo $salaryvaluebyid->conveyance;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Loan</td>
                                                    <td class="text-right"> </td>
                                                    <td class="text-right"><?php //echo $salary_info->loan;?>  BDT</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th class="text-right"><?php //echo $salaryvaluebyid->total;?> BDT</th>
                                                    <th class="text-right"><?php //echo $salary_info->diduction;?>  BDT</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-30 text-right">
                                        <h3><b>Total :</b>  <?php //echo $salary_info->total_pay;?> BDT</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="text-right">
                                        <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal fade" id="Salarymodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <h4 class="modal-title" id="exampleModalLabel1">Salary Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <form method="post" action="Add_Salary" id="salaryform" enctype="multipart/form-data">
                            <div class="modal-body">
                                    <!--   <div class="form-group">
                                        <label>Salary Type</label>
                                        <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="typeid" required>
                                            <?php #foreach($typevalue as $value): ?>
                                            <option value="<?php #echo $value->id ?>"><?php #echo $value->salary_type; ?></option>
                                            <?php #endforeach; ?>
                                        </select>
                                    </div> -->                                        
                                    <div class="form-group">
                                        <label class="control-label">Employee Id</label>
                                        <input type="text" name="emid" class="form-control" id="recipient-name1" value="" readonly>
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Basic</label>
                                        <input type="text" name="basic" class="form-control" id="recipient-name1" value="">
                                    </div>
                                    <h4>Addition</h4>                                         
                                    <div class="form-group">
                                        <label class="control-label">Medical</label>
                                        <input type="text" name="medical" class="form-control" id="recipient-name1"  value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">House Rent</label>
                                        <input type="text" name="houserent" class="form-control" id="recipient-name1" value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Bonus</label>
                                        <input type="text" name="bonus" class="form-control" id="recipient-name1" value="">
                                    </div>
                                    <h4>Deduction</h4>                                         
                                    <div class="form-group">
                                        <label class="control-label">Provident Fund</label>
                                        <input type="text" name="provident" class="form-control" id="recipient-name1" value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Bima</label>
                                        <input type="text" name="bima" class="form-control" id="recipient-name1" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Tax</label>
                                        <input type="text" name="tax" class="form-control" id="recipient-name1"  value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Others</label>
                                        <input type="text" name="others" class="form-control" id="recipient-name1"  value="">
                                    </div>                                          
                                
                            </div>
                            <div class="modal-footer">                                       
                            <input type="hidden" name="sid" value="" class="form-control" id="recipient-name1">                                       
                            <input type="hidden" name="aid" value="" class="form-control" id="recipient-name1">                                       
                            <input type="hidden" name="did" value="" class="form-control" id="recipient-name1">                                       
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                </body>	
</html>


<script type="text/javascript">
    $(document).ready(function () {
        $(".SalarylistModal").click(function (e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $('#salaryform').trigger("reset");
            $('#Salarymodel').modal('show');
            $.ajax({
                url: 'GetSallaryById?id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).done(function (response) {
                console.log(response);
                // Populate the form fields with the data returned from server
                $('#salaryform').find('[name="sid"]').val(response.salaryvalue.id).end();
                $('#salaryform').find('[name="aid"]').val(response.salaryvalue.addi_id).end();
                $('#salaryform').find('[name="did"]').val(response.salaryvalue.de_id).end();
               /* $('#salaryform').find('[name="typeid"]').val(response.salaryvalue.type_id).end();*/
                $('#salaryform').find('[name="emid"]').val(response.salaryvalue.emp_id).end();
                $('#salaryform').find('[name="basic"]').val(response.salaryvalue.basic).end();
                $('#salaryform').find('[name="medical"]').val(response.salaryvalue.medical).end();
                $('#salaryform').find('[name="houserent"]').val(response.salaryvalue.house_rent).end();
                $('#salaryform').find('[name="bonus"]').val(response.salaryvalue.bonus).end();
                $('#salaryform').find('[name="provident"]').val(response.salaryvalue.provident_fund).end();
                $('#salaryform').find('[name="bima"]').val(response.salaryvalue.bima).end();
                $('#salaryform').find('[name="tax"]').val(response.salaryvalue.tax).end();
                $('#salaryform').find('[name="others"]').val(response.salaryvalue.others).end();
            });
        });
    });
</script>    
    <script src="<?php echo base_url(); ?>public/assets/js/jquery.PrintArea.js" type="text/JavaScript"></script>
    <script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $(".print_payslip_btn").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.payslip_print").printArea(options);
        });
    });
</script>                          

