@extends('admin.layouts.app')
@section('seo')
    <?php
    use App\Constants\BaseConstants;
    use App\Tasks\Admin\RoleTask;
    use Illuminate\Support\Facades\Request;

    $data_seo = array(
        'title' => 'Sản phẩm | ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Sản phẩm | ' . Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset('images/logo_seo.png'),
        'current_url' => Request::url(),
        'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    $user_role = Request()->user_role;
    $reviewProductModulePermission = app(RoleTask::class)
        ->checkPermission('review-product-management', [BaseConstants::READ_PERMISSION], $user_role);
    $productQuestionModulePermission = app(RoleTask::class)
        ->checkPermission('product-question-management', [BaseConstants::READ_PERMISSION], $user_role);
    ?>
    @include('admin.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sản phẩm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sản phẩm</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clear">
                                <ul class="nav fl">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('product')"
                                           href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-primary" href="{{route('admin.createProduct')}}"
                                           style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                    </li>
                                </ul>
                                <div class="fr">
                                    <form method="GET" action="{{route('admin.listProduct')}}" id="frm-filter-post"
                                          class="form-inline">
                                        <select class="custom-select mr-2" name="category_theme">
                                            <option value="">Thể loại sản phẩm</option>
                                            @foreach($list_cate as $cate)
                                                <option value="{{$cate->categoryID}}"
                                                        @if(isset($_GET['category_theme']) && $_GET['category_theme'] == $cate->id)
                                                        selected
                                                    @endif
                                                >
                                                    {{$cate->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control" name="search_title" id="search_title"
                                               value="{{ Request()->get('search_title') }}"
                                               placeholder="Từ khoá">
                                        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                    </form>
                                </div>
                            </div>
                            <br/>
                            <div class="clear">
                                <div class="fr">
                                    {!! $data_product->links() !!}
                                </div>
                            </div>
                            <br/>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_index">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall"
                                                                                   onclick="select_all()"></th>
                                        <th scope="col" class="text-center">Title</th>
                                        <th scope="col" class="text-center">Thumbnail</th>
                                        <th scope="col" class="text-center">Price</th>
                                        <th scope="col" class="text-center">Date Event</th>
                                        <th scope="col" class="text-center">Action</th>
                                        <th scope="col" class="text-center">Update</th>
                                        <th scope="col" class="text-center">Store Status</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data_product as $data)
                                        <tr>
                                            <td class="text-center"><input type="checkbox" id="{{$data->id}}"
                                                                           name="seq_list[]" value="{{$data->id}}"></td>
                                            <td class="text-center" style="width: 250px;">
                                                <a class="row-title"
                                                   href="{{route('admin.productDetail', array($data->id))}}">
                                                    <b>{{$data->title}}</b>
                                                    <br>
                                                    <b style="color:#c76805;">{{$data->slug}}</b>
                                                    <?php
                                                    $categories = \App\Models\Product::where('products.id', $data->id)
                                                        ->join(
                                                            'join_category_product',
                                                            'products.id',
                                                            'join_category_product.product_id'
                                                        )
                                                        ->join(
                                                            'product_categories',
                                                            'join_category_product.category_id',
                                                            'product_categories.id'
                                                        )
                                                        ->select(
                                                            'product_categories.id',
                                                            'product_categories.name',
                                                            'product_categories.slug'
                                                        )
                                                        ->orderBy('product_categories.parent', 'ASC')
                                                        ->get();
                                                    if($categories): ?>
                                                    <div class="list_cat_post_content_link">
                                                        @foreach($categories as $category)
                                                            <a class="tag" target="_blank"
                                                               href="javascript:void(0)">{{$category->name}}</a>
                                                        @endforeach
                                                    </div>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                @if($data->thumbnail != '')
                                                    <img src="{{ asset('images/product/' . $data->thumbnail) }}"
                                                         style="height: 70px;">
                                                @else
                                                    <img src="{{ asset('img/default-150x150.png') }}">
                                                @endif
                                            </td>
                                            <td class="text-center" style="width: 130px;">
                                                <div class="clear price_colum_item form-group">
                                                    <label for="origin-price-{{$data->id}}">Giá gốc</label>
                                                    <input id="origin-price-{{$data->id}}"
                                                           class="colunm_price form-control" placeholder="Giá gốc"
                                                           type="text" value="{{$data->price_origin}}"
                                                           name="price_origin">
                                                </div>
                                                <div class="clear price_colum_item form-group">
                                                    <label for="promotion-price-{{$data->id}}">Giá KM</label>
                                                    <input id="promotion-price-{{$data->id}}"
                                                           class="colunm_price form-control"
                                                           placeholder="Giá khuyến mãi" type="text"
                                                           value="{{$data->price_promotion}}" name="price_promotion">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="clear price_colum_item form-group">
                                                    <label for="start-event-{{$data->id}}">Bắt đầu</label>
                                                    <input id="start-event-{{$data->id}}"
                                                           class="colunm_price form-control"
                                                           placeholder="YYYY-mm-dd H:i:s" type="text"
                                                           value="{{$data->start_event}}" name="start_event">
                                                </div>
                                                <div class="clear price_colum_item form-group">
                                                    <label for="end-event-{{$data->id}}">Kết thúc</label>
                                                    <input id="end-event-{{$data->id}}"
                                                           class="colunm_price form-control"
                                                           placeholder="YYYY-mm-dd H:i:s" type="text"
                                                           value="{{$data->end_event}}" name="end_event">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="clear action_group_box_touth">
                                                    <label style="color: #FF0000;" class="title_action_lb">New Arrivals</label>
                                                    <input id="toggle-new_arrival-{{$data->id}}"
                                                           onchange="product_quick_update_option({{$data->id}}, 'arrival')"
                                                           type="checkbox" value="1" data-toggle="toggle"
                                                           name="new_arrival" @if($data->new_arrival == 1) checked @endif>
                                                </div>
                                                <div class="clear action_group_box_touth">
                                                    <label style="color: #FF0000;" class="title_action_lb">Hot Deal</label>
                                                    <input id="toggle-hot_deal-{{$data->id}}" class="toggle_hot_deal"
                                                           onchange="product_quick_update_option({{$data->id}}, 'hot_deal')"
                                                           type="checkbox" value="1" data-toggle="toggle"
                                                           name="hot_deal" @if($data->hot_deal == 1) checked @endif>
                                                </div>
                                                <div class="clear action_group_box_touth">
                                                    <label style="color: #FF7E00;" class="title_action_lb">Suggest (Like)</label>
                                                    <input id="toggle-propose-{{$data->id}}" data-toggle="toggle"
                                                           onchange="product_quick_update_option({{$data->id}}, 'propose')"
                                                           type="checkbox" value="1"
                                                           name="propose" @if($data->propose == 1) checked @endif>
                                                </div>
                                                <div class="clear action_group_box_touth">
                                                    <label style="color: #FF7E00;" class="title_action_lb">Best Seller</label>
                                                    <input id="toggle-best_seller-{{$data->id}}" data-toggle="toggle"
                                                           onchange="product_quick_update_option({{$data->id}}, 'best_seller')"
                                                           type="checkbox" value="1" name="best_seller"
                                                           @if($data->best_seller == 1) checked @endif>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-warning product_quick_update"
                                                        onclick="product_quick_update({{$data->id}})"
                                                        data-product-id="{{$data->id}}" name="update_column">Update
                                                </button>
                                                <p id="alert_{{ $data->id }}" class="text-center color_show_alert"
                                                   style="display: none;"></p>
                                            </td>
                                            <td class="text-center">
                                                <input id="toggle-store-status-{{$data->id}}"
                                                       class="toggle_store_status"
                                                       onchange="store_status_click({{$data->id}})"
                                                       postID="{{ $data->id }}" type="checkbox" value="1"
                                                       name="store_status" @if($data->store_status == 1) checked @endif
                                                       data-toggle="toggle">
                                                <div id="console_event_{{$data->id}}"></div>
                                            </td>
                                            <td class="text-center">
                                                {{$data->created}}
                                                <br>
                                                @if($data->status == 1)
                                                    Public
                                                @else
                                                    Draft
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="fr">
                                {!! $data_product->links() !!}
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section>
@endsection
