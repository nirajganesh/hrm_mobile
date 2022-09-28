            </div>

            <footer class="footer"> © <?=date('Y')?> DigiKraft social </footer>

        </div>

    </div>


    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>public/assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url(); ?>public/assets/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/js/custom.min.js"></script>

    <!-- ============================================================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <!-- ============================================================== -->
    <!--sparkline JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--morris JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/raphael/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/morrisjs/morris.js"></script>
    <!-- Chart JS -->

    <!-- <script src="<?php echo base_url(); ?>assets/js/dashboard1.js"></script> -->
    
    <script src="<?php echo base_url(); ?>public/assets/plugins/moment/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>  
   
    <script src="<?php echo base_url(); ?>public/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

    <!-- Editable -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/jsgrid/db.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/jsgrid/dist/jsgrid.min.js"></script>
    <!-- This is data table -->

    <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/export/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

   
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>                        
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>  
    <!-- end - This is for export functionality only -->
    <script src="<?php echo base_url(); ?>public/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    
    <!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/calendar/jquery-ui.min.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/calendar/dist/fullcalendar.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/calendar/dist/cal-init.js"></script>

    <script type="text/javascript">
        $(function () {
            $('.mydatetimepicker').datepicker({
            format: "mm-yyyy",
            viewMode: "years", 
            minViewMode: "months"   
            });
        });
        $(function () {
            $('.mydatetimepickerFull').datepicker({
            format: "yyyy-mm-dd"   
            });
        });
    </script>  
         
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": 2
                    }],
                    "order": [
                        [2, 'asc']
                    ],
                    "displayLength": 25,
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });
                // Order by the grouping
                $('#example tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        });
        $(function () {
            $("#datepicker").datepicker({ 
                    autoclose: true, 
                    todayHighlight: true
            }).datepicker('update', new Date());

            $('.input-daterange input').each(function() {
                $(this).datepicker({
                    format: 'dd-mm-yyyy'
                });
            });

        });
        jQuery('.mydatepicker, #datepicker').datepicker();
        jQuery('#datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true
        });        
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('#single-input').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });
        $('#single-input').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done',
        }).find('input').change(function() {
            console.log(this.value);
        });
        $('#check-minutes').click(function(e) {
            // Have to stop propagation here
            e.stopPropagation();
            input.clockpicker('show').clockpicker('toggleView', 'minutes');
        });


        $(function() {
            $('#datetimepicker2').datetimepicker({
            language: 'en',
            pick12HourFormat: true
            });
        });
    
        $(".select2").select2({
            theme:'bootstrap4'
        });

        $('#datepickerSumm').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true, 
            todayHighlight: true 
        });
    </script>

    <script type="text/javascript">
        $('form').each(function() {
            $(this).validate({
                submitHandler: function(form) {
                    var formval = form;
                    var url = $(form).attr('action');
                    if(form.id=='noScript'){
                        // alert('in');
                        form.submit();
                    }
                    else{
                        // Create an FormData object
                        var data = new FormData(formval);
                        $.ajax({
                            type: "POST",
                            enctype: 'multipart/form-data',
                            // url: "crud/Add_userInfo",
                            url: url,
                            data: data,
                            processData: false,
                            contentType: false,
                            cache: false,
                            timeout: 600000,
                            success: function (response) {
                                // console.log(response);            
                                $(".message").fadeIn('fast').delay(3000).fadeOut('fast').html(response);
                                $('form').trigger("reset");
                                window.setTimeout(function(){location.reload()},1500);
                            },
                            error: function (e) {
                                alert('server error');
                                console.log(e);
                            }
                        });
                    }
                }
            });
        });

        $(function(){   
            $('.readmore a.more').on('click', function(){
                var $parent = $(this).parent();
                if($parent.data('visible')) {
                    $parent.data('visible', false).find('.ellipsis').show()
                    .end().find('.moreText').hide()
                    .end().find('a.more').text('Show more +');
                } else {
                    $parent.data('visible', true).find('.ellipsis').hide()
                    .end().find('.moreText').show()
                    .end().find('a.more').text('Show less -');
                }
            });
        });
    </script>     

    <script src="<?php echo base_url(); ?>public/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
