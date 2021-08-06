@extends('backend.layouts.master')

@section('title') {{$title}} @endsection

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('css_raw')
    <style>
        input[type='file'] {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
@endsection

@section('content')

@component('backend.components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') {{$title}} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"></h4>

                <div class="d-flex flex-wrap gap-2 btn--actions">
                    <button type="button" class="btn btn-info " data-bs-toggle="modal" data-bs-target="#comtopUploadModal">
                        匯入COMTOP庫存
                    </button>
                </div>
            </div>
            <div class="card-body">

                <table id="datatable-table" class="table table-bordered  w-100">
                    <thead>
                        <tr>
                            <th name="entity_id">ID</th>
                            <th name="name">Name</th>
                            <th name="cl_sku" class="nosort">SKU(CL)</th>
                            <th name="cl_qty" class="nosort">Qty(CL)</th>
                            <th name="ct_sku"class="nosort">SKU(CT)</th>
                            <th name="ct_qty" class="nosort">Qty(CT)</th>
                            <th name="min_qty" class="nosort">MinQty</th>
                            <th name="recom_qty" class="nosort">RecommandQty</th>
                            <th class="nosort">Action</th>
                        </tr>
                    </thead>

                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<form method="post" enctype="multipart/form-data" id="frm-post" action="{{ route('dashboard.products.list') }}">
    <inptu type="hidden" name="_type" id="frm-status" value=""/>
    <inptu type="hidden" name="_token" id="frm-token" value="{{csrf_token()}}"/>
</form>


<!-- COMTOP Upload Modal -->
<div class="modal fade" id="comtopUploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="comtopUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comtopUploadModalLabel">匯入COMTOP庫存表</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>注意：檔案上傳後會立即刷新資料庫</p>

                <div class="form-group row">

                    <div class="input-group">
                        <input type="text" id="ComtopInventoryDataUploadText" class="form-control" value="COMTOP庫存表(限上傳Excel格式)" readonly="">
                        <span class="input-group-text">
                            <button class="btn btn-info ">
                                <span class=" fas fa-cloud-upload-alt"></span>
                                <span class="ks-text">Select file</span>
                                <input type="file" id="ComtopInventoryDataUpload" accept=".xls,.xlsx">
                            </button>
                        </span>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Understood</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<!-- Required datatable js -->
<script src="{{ URL::asset('/backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('/backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ URL::asset('/backend/assets/js/pages/datatables.init.js') }}"></script>

@endsection


@section('script-bottom')

<script type="text/javascript">
    var _token = '{{csrf_token()}}';
    var oTable;
    $(function(){


        oTable = $("#datatable-table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('dashboard.products.list') }}",
                    "data": function ( d ) {
                                d._type = $("#frm-status").val();
                                d._token = _token;
                            }
                },
                "columns": [
                    { "data": "entity_id", "width":"7%"  },
                    { "data": "name", "width":"35%"  },
                    { "data": "cl_sku", "width":"10%"  },
                    { "data": "cl_qty", "width":"10%"  },
                    { "data": "ct_sku", "width":"12%"  },
                    { "data": "ct_qty", "width":"10%"  },
                    { "data": "min_qty", "width":"8%"  },
                    { "data": "recom_qty", "width":"8%"  },
                    { "data": "action", "width":"5%"  },
                ],
                /*
                "language":{
                    "url": "{{ URL::asset('/backend/assets/libs/datatables/language/zh_TW.txt') }}"
                },*/
                dom: '<"row"<"col-md-12"<"row"<"col-md-6"l><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                "columnDefs": [ {
                  "targets": 'nosort',
                  "orderable": false
                } ],
                "iDisplayLength": 50,
                "aaSorting": [[ 0, "asc" ]]
            });


            $('#datatable-table').on( 'draw.dt', function () { });
            $("body").on("click",".dropdown-btn-edit",function(event){
                    var btn = $(this);
                    var id = $(this).attr("data-rel");
                    window.location.href='/dashboard/life-edit-'+id;
                    event.preventDefault();
                });

            $("body").on("click",".dropdown-btn-delete",function(event){

                var btn = $(this);
                var id = $(this).attr("data-rel");
                if( confirm("是否要刪除該筆資料，移除後將無法回復。") == true ){
                    $.post("",{"_token":_token,"id":id},function(data){
                        if( data.result == true ){
                            fnNotifications('資料刪除完成');
                            $("#datatable-table").dataTable().api().ajax.reload(null, false);
                        }else{
                            fnNotifications(data.error_txt);
                        }
                    },"JSON");
                }

                event.preventDefault();
            });


        $("button.btn-filter-type").on("click", function(e){
            var type = $(this).data("type");
            $("#frm-rType").val( type );
            $(".btn--actions button").removeClass("btn-info btn-warning").addClass("btn-warning");
            $(this).removeClass("btn-warning").addClass("btn-info");
            $("#datatable-table").dataTable().api().ajax.reload(null, false);
        });



        $("body").on('change', '#ComtopInventoryDataUpload', function(event){
            var input = $(this)[0].files[0];
            if( input.size > 1 ){
                $("#ComtopInventoryDataUploadText").val(input.name + '資料處理中．．．');
                var formData = new FormData();
                formData.append('action', 'AjaxUploadComtop');
                formData.append('csrf-token', _token);
                formData.append('file', input);

                $.ajax({
                    url : '{{ route('AjaxUploadComtop') }}',
                    type : 'POST',
                    data : formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success : function (response) {
                        if( response.result == true ){
                            $("#ComtopInventoryDataUploadText").val(response.success);
                            fnNotifications(response.success);
                        }else{
                            fnNotifications(response.error_txt);
                        }
                        $("#booksCoverUpload").val('');
                    }
                });
            }
        });

        @if (session('status'))
            fnNotifications('{{ session('status') }}');
        @endif

    });


</script>

@endsection
