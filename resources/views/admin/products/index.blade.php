@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{asset('/')}}assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <style>
        i.zmdi.zmdi-plus-circle-o {
            color: #fff !important;
        }
        label.btn.btn-danger.active.toggle-off, label.btn.btn-success.toggle-on {
            margin-top: 3px;
            background: transparent;
            font-size: 11px;
            font-weight: 600;
        }
        span.toggle-handle.btn.btn-default {
            padding: 10px;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: unset;
        }
        .table tr{
            border-bottom:1px solid #ddd;
        }
        .toggle-off.btn {
            padding-left: 20px;
        }

    </style>
@stop
@section('body')
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>{{$page_heading}}</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="zmdi zmdi-home"></i> Admin </a></li>
                    <li class="breadcrumb-item active">{{$page_heading}}</li>
                </ul>
                <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">

            <div class="col-lg-12">
                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <strong>Congrats</strong> {{Session::get('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="zmdi zmdi-close"></i></span>
                        </button>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-success" role="alert">
                        <strong>Sorry</strong> {{Session::get('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="zmdi zmdi-close"></i></span>
                        </button>
                    </div>
                @endif
                <div class="card">

                    <div class="body">
                        <div class="header">
                            <h2><strong>All</strong> Products </h2>
                            <ul class="header-dropdown">
                                <li>
                                    <a href="{{url('admin/add-products')}}" class="btn bg-purple waves-effect"><i class="zmdi zmdi-plus-circle-o"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="table-responsive">
                            <table class="table dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>Photo</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Show Highlight </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>Photo</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Show Highlight </th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                                <tbody>

                                @if(!empty($products))
                                    @foreach($products as $key =>  $product)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$product->title}}</td>
                                        <td><img src="{{asset($product->photo)}}" width="100"></td>
                                        <td>
                                            {{$product->section->category_name ?? 'NA'}}
                                        </td>

                                        <td>
                                            <input data-id="{{$product->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive"{{ $product->status == 'active' ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            @if($product->highlights == 1)
                                                <span class="badge badge-info text-white" >Current status : Active</span><br>
                                                <span class="badge badge-danger">
                                                <a href="javascript:void(0)" data-id="{{$product->id}}" data-status="0" class="status">
                                                    Change to : Inactive
                                                </a>
                                            </span>
                                            @else
                                                <span class="badge badge-danger">Current status : Inactive</span><br>
                                                <span class="badge badge-info">
                                                <a href="javascript:void(0)" data-id="{{$product->id}}" data-status="1" class="text-white status">
                                                    Change to : Active
                                                </a>
                                            </span>
                                            @endif

                                        </td>
                                        <td class="" style="">
                                            <a href="{{url('admin/edit-products/'.$product->id)}}" class="btn btn-primary waves-effect waves-float btn-sm waves-green"><i class="zmdi zmdi-edit"></i> Edit Product</a> <br>
                                            <a href="{{url('admin/products/add/attribute/display/'.$product->id)}}" class="btn btn-primary waves-effect waves-float btn-sm waves-green"><i class="zmdi zmdi-edit"></i> Edit Attribute</a> <br>
                                            <a href="javascript:void(0);" onclick="deleteConfirmation({{$product->id}})" class="btn btn-primary waves-effect waves-float btn-sm waves-red"><i class="zmdi zmdi-delete"></i> Delete Product</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('/')}}assets/bundles/datatablescripts.bundle.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/buttons.flash.min.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="{{asset('/')}}assets/plugins/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="{{asset('/')}}assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#filesizecheck').on('change',function(){
                for(var i=0; i< $(this).get(0).files.length; ++i){
                    var file1 = $(this).get(0).files[i].size;
                    if(file1){
                        var file_size = $(this).get(0).files[i].size;
                        if(file_size > 2000000){
                            $('#error-message').html("File upload size is larger than 2MB");
                            $('#error-message').css("display","block");
                            $('#error-message').css("color","red");
                        }else{
                            $('#error-message').css("display","none");
                        }
                    }
                }
            });
        });

        $(function() {
            $('.status').click(function() {
                var status = $(this).data('status');
                var id = $(this).data('id');
                var table = 'categories';
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{url("admin/highlight-status")}}',
                    data: {'status': status, 'id': id,'table' : table},
                    success: function(data){
                        console.log(data);
                        // location.reload();
                        // swal("Status Changed!");
                        // location.reload();
                        // console.log(data.success)
                    }
                });
            })
        });

        function deleteConfirmation(id) {
            swal({
                title: "Delete?",
                text: "Please ensure and then confirm!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: !0
            }).then(function (e) {

                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: 'get',
                        url: "{{url('admin/delete-products')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {

                            if (results.success === true) {
                                swal("Done!", results.message, "success");
                                location.reload();
                            } else {
                                swal("Error!", results.message, "error");
                                location.reload();
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>

@stop
