@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Nhập sản phẩm từ Excel | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Nhập sản phẩm từ Excel | '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => asset('images/logo_seo.png'),
    'current_url' =>Request::url(),
    'current_url_amp' => ''
);
$seo = WebService::getSEO($data_seo);
?>
@include('admin.partials.seo')
@endsection
@section('content')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Nhập sản phẩm từ Excel</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Nhập sản phẩm từ Excel</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        @if(Session::has('success_msg'))
            <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ Session::get('success_msg') }}
            </div>
        @endif
        <form action="{{route('admin.importProductAction')}}" method="POST" enctype="multipart/form-data" class="mt-2">
            @csrf
            <div class="row mb-2 justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="import_file" class="custom-file-input" id="import-file" required>
                            <label class="custom-file-label" for="import-file">Upload file Excel</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Nhập Excel</button>
            </div>
        </form>

        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lịch sử thêm dữ liệu</h3>
                </div> <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_index"></table>
                    </div>
                    <script>
                        $(function() {
                            let data2 ={!! $import_histories !!};
                            $('#table_index').DataTable({
                                data: data2,
                                columns: [
                                    {title: 'Filename', data: 'filename'},
                                    {title: 'File Location', data: 'file_location'},
                                    {title: 'Process', data: 'process'},
                                    {title: 'Message', data: 'message'},
                                    {title: 'Created', data: 'created_at'},
                                ],
                                order: [[ 4, "desc" ]],
                                columnDefs: [
                                    {//Filename
                                        visible: true,
                                        targets: 0,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return data;
                                        }
                                    },
                                    {//File Location
                                        visible: true,
                                        targets: 1,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return '<a href="' + data + '" target="_blank">' + data + '</a>';
                                        }
                                    },
                                    {//Process
                                        visible: true,
                                        targets: 2,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            if (data == 0) {
                                                return 'Đang xử lý';
                                            } else if(data == 1){
                                                return 'Xong';
                                            } else {
                                                return 'Lỗi';
                                            }
                                        }
                                    },
                                    {//Message
                                        visible: true,
                                        targets: 3,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return data;
                                        }
                                    },
                                    {//Created
                                        visible: true,
                                        targets: 4,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return getFormattedDate(data);
                                        }
                                    }
                                ],
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
  	</div> <!-- /.container-fluid -->
</section>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })
</script>
@endsection
