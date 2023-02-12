@extends('user.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'List of reviews- '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'List of reviews - '.Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset(Helpers::get_setting('seo_image')),
    );
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <div class="page-title">
        <h1>List of product reviews</h1>
    </div>
    <div class="table-responsive">
        <table class="table tbl-my-reviews">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectall"></th>
                <th>Product</th>
                <th>Images</th>
                <th>Review</th>
                <th>Review Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td><input type="checkbox" id="{{ $row->id }}" name="seq_list[]" value="{{ $row->id }}"></td>
                    <td>
                        <a href="{{route('single.detail', [$row->product->categories->slug, $row->product->slug])}}">
                            {{ $row->product->title }}
                        </a>
                    </td>
                    <td>
                        <?php
                        $thumb = asset('images/product/' . $row->product->thumbnail);
                        ?>
                        <img src="{{ $thumb }}" width="100">
                    </td>
                    <td>
                        <div class="rating-box">
                            {{ $row->review }}
                        </div>
                    </td>
                    <td>
                        <?php
                        $created_at = $row->created_at;
                        $timestamp_created_at = strtotime($created_at);
                        $created_at = date("d-m-Y h:i:s", $timestamp_created_at);
                        echo $created_at;
                        ?>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="page_navi clear">
        {{ $data->links() }}
    </div><!--page_navi-->
@endsection
