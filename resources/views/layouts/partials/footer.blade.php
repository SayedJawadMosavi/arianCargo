<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                Copyright © <span id="year"></span> <a href="https://nethub.af" target="_blank">Nethub</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="https://nethub.af" target="_blank"> Nethub </a> All rights reserved.
            </div>
        </div>
    </div>
</footer>


</div>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JQUERY JS -->
<script src="{{asset('back/js/jquery.min.js')}}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{asset('back/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('back/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- SPARKLINE JS-->
{{-- <script src="{{asset('back/js/jquery.sparkline.min.js')}}"></script> --}}

<!-- Sticky js -->
<script src="{{asset('back/js/sticky.js')}}"></script>

<!-- CHART-CIRCLE JS-->
{{-- <script src="{{asset('back/js/circle-progress.min.js')}}"></script> --}}

<!-- SIDEBAR JS -->
<script src="{{asset('back/plugins/sidebar/sidebar.js')}}"></script>


<!-- INTERNAL SELECT2 JS -->
{{-- <script src="{{asset('back/plugins/select2/select2.full.min.js')}}"></script> --}}

<!-- INTERNAL Data tables js-->

<script src="{{asset('/js/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('/js/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
 <script src="{{asset('/js/datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/vfs_fonts.js')}}"></script>

<script src="{{asset('/js/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/buttons.colvis.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/responsive.bootstrap5.min.js')}}"></script>
<script src="{{asset('/js/datatable/js/table-data.js')}}"></script>
<script>
    $.fn.poshytip = {
        defaults: null
    }
</script>
<script src="{{asset('/js/datatable/js/jquery-editable-poshytip.min.js')}}"></script>
<!-- INTERNAL Flot JS -->

<!-- SIDE-MENU JS-->
<script src="{{asset('back/plugins/sidemenu/sidemenu.js')}}"></script>

<!-- TypeHead js -->
{{-- <script src="{{asset('back/plugins/bootstrap5-typehead/autocomplete.js')}}"></script> --}}
{{-- <script src="{{asset('back/js/typehead.js')}}"></script> --}}

<!-- INTERNAL INDEX JS -->
<script src="{{asset('back/js/index1.js')}}"></script>
<!-- Color Theme js -->
<script src="{{asset('back/js/themeColors.js')}}"></script>
<!-- CUSTOM JS -->
{{-- <script src="{{asset('back/js/custom1.js')}}"></script> --}}
<script src="{{asset('back/js/select2.min.js')}}"></script>
<script src="{{asset('back/js/custom.js')}}"></script>
<script src="{{asset('back/js/custom1.js')}}"></script>
<script src="{{asset('/jalali/src/persion.js')}}"></script>
<script src="{{asset('/jalali/src/persian-datepicker.js')}}"></script>
<script src="{{asset('sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('/js/toastr.min.js')}}"></script>

<script type="text/javascript">

    @if(isset($client))
        var clientName = "{{ $client->name }}";
    @else
        var clientName = null;
    @endif

    $(document).ready(function() {
        // $('.summernote').summernote();
        $('#datatable').DataTable({
            paging: true,
            ordering: true,
            info: true,
            stateSave: true,
            dom: 'lBfrtip',
            buttons: [
                'colvis',
                {
                    extend: 'excelHtml5',
                    text: 'Excel'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    customize: function (doc) {
                        if (clientName) {
                            doc.content.splice(0, 0, {
                                text: 'Client Name: ' + clientName,
                                margin: [0, 0, 0, 12],
                                alignment: 'center',
                                fontSize: 14,
                                bold: true
                            });
                        }
                    }
                },
                'copy',
                {
                    extend: 'print'
                }
            ]
        });

        $('#datatable-report').DataTable({
            paging: false,
            ordering: true,
            info: true,
            stateSave: true,
            dom: 'Bfrtip',
            buttons: ['colvis', 'excel', 'copy', 'pdf',
                {
                    extend: 'print',
                    footer: true, //Print tfoot data also
                    title: '',
                    customize: function(win) {
                        $(win.document.body).css('direction', 'rtl');
                    }
                }
            ],
        });

    });

    $('.select2').select2();
    $('.select1').select2();
</script>
@stack('script')
