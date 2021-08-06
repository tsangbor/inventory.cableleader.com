@extends('backend.layouts.master')

@section('title') {{$title}} @endsection

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{ URL::asset('/backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

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
                    <button type="button" class="btn btn-info btn-filter-type" data-type="">
                        全部訂單 <span class="badge bg-danger ms-1">{{ $Orders->where('status', 'Processing')->count() }}</span>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <table id="datatable-table" class="table table-bordered  w-100">
                    <thead>
                        <tr>
                            <th name="increment_id">ID</th>
                            <th name="created_at">Purchase Date</th>
                            <th name="customer" class="nosort">Bill-to Name</th>
                            <th name="subtotal_incl_tax"class="nosort">SubTotal</th>
                            <th name="base_grand_total" class="nosort">GrandTotal</th>
                            <th name="status"class="nosort">Status</th>
                            <th class="nosort">Action</th>
                        </tr>
                    </thead>

                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<form method="post" enctype="multipart/form-data" id="frm-post" action="{{ route('dashboard.orders.list') }}">
    <inptu type="hidden" name="_type" id="frm-status" value=""/>
    <inptu type="hidden" name="_token" id="frm-token" value="{{csrf_token()}}"/>
</form>

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
                    "url": "{{ route('dashboard.orders.list') }}",
                    "data": function ( d ) {
                                d._type = $("#frm-status").val();
                                d._token = _token;
                            }
                },
                "columns": [
                    { "data": "increment_id", "width":"15%"  },
                    { "data": "created_at", "width":"15%"  },
                    { "data": "customer", "width":"20%"  },
                    { "data": "subtotal_incl_tax", "width":"15%"  },
                    { "data": "base_grand_total", "width":"15%"  },
                    { "data": "status", "width":"10%"  },
                    { "data": "action", "width":"10%"  },

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


            $('#datatable-table').on( 'draw.dt', function () {

            });
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



        @if (session('status'))
            fnNotifications('{{ session('status') }}');
        @endif

    });


</script>

@endsection
