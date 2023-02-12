@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Theme Option | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Theme Option | '.Helpers::get_setting('seo_title'),
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
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Setting</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Theme Option</li>
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
                <h3 class="card-title">Theme Option</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              	<form action="{{route('admin.storeThemeOption')}}" method="POST" id="frm-theme-option">
              		@csrf
	              	<div class="container_group_setting clear">
		              	<div class="group_item_auto_theme clear">
		                <?php
		            	$List=App\Models\Setting::orderBy('updated', 'desc')->first();
		            	if($List):
							$array_option_autos=unserialize($List['value_setting']);
						endif;
						if( !empty($array_option_autos)):
							$count=	count( $array_option_autos);
							for( $i = 0; $i < $count; $i++ ):
								$label_text	=($array_option_autos[$i]['group_tdr']['tdr_name'] != '' ) ? $array_option_autos[$i]['group_tdr']['tdr_name'] : '';
								$option_value=($array_option_autos[$i]['group_tdr']['tdr_value'] != '' ) ? $array_option_autos[$i]['group_tdr']['tdr_value'] : '';
								$option_value=stripslashes(stripslashes(base64_decode($option_value)));
								$option_choise=($array_option_autos[$i]['group_tdr']['tdr_choise'] != '' ) ? $array_option_autos[$i]['group_tdr']['tdr_choise'] : '';
								switch($option_choise):
									case "line":
											echo '<div class="group_item_theme clear">
		                       					<div class="left_item_theme left_genate"><input type="text" value="'.$label_text.'" placeholder="Please enter Name Field"  name="header_option_texts_line[]" /></div>
		                      					<div class="right_item_theme right_genate"><input type="text" class="regular-text" placeholder="Please enter Value Field" name="header_option_values_line[]" value="'.$option_value.'" /><input type="button" class="button button-secondary tbl_button_delete_clean" value="Delete" name="delete_tbl"></div>
		                       				</div>';
									break;
									case "muti_line":
										echo '<div class="group_item_theme clear">
		                       					<div class="left_item_theme left_genate"><input type="text" value="'.$label_text.'" placeholder="Please enter Name Field"  name="header_option_texts_muti_line[]" /></div>
		                       					<div class="right_item_theme right_genate"><textarea class="regular-area" name="header_option_values_muti_line[]" cols="5" rows="5">'.$option_value.'</textarea><input type="button" class="button button-secondary tbl_button_delete_clean" value="Delete" name="delete_tbl"></div>
		                       				 </div>';
									break;
									default:
								endswitch;
							endfor;
						endif;
		                ?>
		                </div><!--group_item_auto_theme-->
		                <div class="group_item_theme tbl_create_theme_add clear">
		                	<div class="left_item_theme"><b><i>Choose Field Create</i></b></div>
		                    <div class="right_item_theme">
		                    	<select name="option_choise_add" class="select_option_choise">
		                        	<option value="line">line</option>
		                        	<option value="muti_line">Mutiline</option>
		                     	</select>
			                  	<button id="create_option" type="button" class="btn btn-primary create_option_class">Create Option</button>
		                    </div>
		             	</div><!--group_item_theme-->
	             	</div><!--container_group_setting-->
	             	<div class="posts_tbl_setting clear text-center">
	            		<button id="submit_setting" class="btn btn-primary pull-left" name="submit" type="submit">Save Changes</button>
	            		<p><b>Use:</b> <i style="color: #FF0000;">\App\Libraries\Helpers::get_option_minhnn('value');</i></p>
	             	</div>
             	</form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".right_item_theme").delegate("#create_option","click", function(event) {
			event.preventDefault();
			var choise_option=$(this).parent().find('.select_option_choise').val();
			var line_html='<div class="group_item_theme clear">'+
               	'<div class="left_item_theme left_genate"><input type="text" value="" placeholder="Please enter Name Field"  name="header_option_texts_line[]" /></div>'+
               	'<div class="right_item_theme right_genate"><input type="text" class="regular-text" placeholder="Please enter Value Field" name="header_option_values_line[]" value="" /><input type="button" class="button button-secondary tbl_button_delete_clean" value="Delete" name="delete_tbl"></div>'
               	'</div>';
			var muti_line_html='<div class="group_item_theme clear">'+
               	'<div class="left_item_theme left_genate"><input type="text" value="" placeholder="Please enter Name Field"  name="header_option_texts_muti_line[]" /></div>'+
               	'<div class="right_item_theme right_genate"><textarea class="regular-area" name="header_option_values_muti_line[]" cols="5" rows="5" placeholder="Please enter Value Field"></textarea><input type="button" class="button button-secondary tbl_button_delete_clean" value="Delete" name="delete_tbl"></div>'
               	'</div>';
			switch(choise_option){
				case "line":
					$('.container_group_setting .group_item_auto_theme').append(line_html);
				break;
				case "muti_line":
					$('.container_group_setting .group_item_auto_theme').append(muti_line_html);
				break;
				default:
				alert('Select one option');
			}
		});

		$(document).delegate(".tbl_button_delete_clean","click", function(event) {
			event.preventDefault();
			var elem = $(this).parent().parent();
			$.confirm({
				'title'		: 'Delete Confirmation',
				'message'	: 'You are about to delete this option. <br />It cannot be restored at a later time! Continue?',
				'buttons'	: {
					'Yes'	: {
						'class'	: 'blue',
						'action': function(){
							elem.remove();
						}
					},
					'No'	: {
						'class'	: 'gray',
						'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		});
	});
</script>
<style type="text/css">
 	.container_group_setting{
		background:#f8f8f8 none repeat scroll 0 0;
		padding:20px 10px;
		margin: 0 -10px;
	}
	.posts_tbl_setting{
		margin:0px -10px 0 -10px;
		padding-top:20px;
		border-top: 1px solid #e1e1e1;
		padding-left:10px;
	}
	.posts_tbl_setting #submit_setting{

	}
	#page_title h3{
		display: block;
    	font-size: 25px;
    	line-height: 30px;
    	margin: 10px 0 0;
	}
	#post_body_content .content_setting{
		display:block;
		background: #FFF none repeat scroll 0 0;
    	border-radius: 4px;
    	margin-bottom: 20px;
    	padding: 10px 10px 20px;
	}
	.tbl_create_theme_add {
	    background-color: #dff0d8;
	    border-color: #d6e9c6;
	    color: #3c763d;
	    line-height: 27px;
	    margin: 10px 0px;
	    padding: 10px 30px;
		border-radius:3px;
	}
	.tbl_create_theme_add {
	    color: #3c763d;
	    line-height: 27px;
	}
	.right_item_theme {
	    display: block;
	    float: left;
	    width: 78%;
	}
	.left_item_theme {
	    display: block;
	    float: left;
	    width: 22%;
		line-height: 29px;
	}
	.right_item_theme select.select_option_choise {
	    height: 28px ;
	    line-height: 28px ;
	    padding: 2px 0;
		width:150px;
		margin-right:20px;
	}
	.right_item_theme select.select_option_choise option{
		height:25px;
		line-height:25px;
		display:block;
		color:#F30;
		margin-top:3px;
	}
	.create_option_class, .tbl_choise_img_set {
	    background-color: #0275d8 !important;
	    border-color: #0275d8 !important;
	    border-radius: 3px;
	    border-style: solid;
	    border-width: 1px;
	    box-sizing: border-box;
	    cursor: pointer;
	    display: inline-block;
	    font-size: 13px;
	    height: 28px;
	    line-height: 26px;
	    margin: 0 5px !important;
		text-align: center;
		color: #fff !important;
	    padding: 0 10px 1px;
	    text-decoration: none;
	    white-space: nowrap;
	}
	.group_item_theme h3.line {
	    border-bottom: 1px solid #e1e1e1;
	    color: #900;
	    font-size: 18px;
	    font-weight: 600;
	    margin: 0 -10px 0px;
	    padding: 20px 30px;
	}
	#create_option{
		line-height: 15px;
	}
	.left_genate{
		width: 30%;
	}
	.left_genate input{
		width: 100%;
		border: 1px solid #ccc;
		border-radius:3px;
	}
	.right_genate{
		width: 68%;
	}
	.right_genate input.regular-text{
		width:80%;
		margin-left:5px;
		margin-right:5px;
		border-radius:3px;
		display:block;
		float:left;
		border: 1px solid #ccc;
	}
	.right_genate textarea.regular-area{
		width:80%;
		margin-left:5px;
		margin-right:5px;
		border-radius:3px;
		display:block;
		float:left;
		border: 1px solid #ccc;
		padding:10px;
	}
	.right_genate input.tbl_button_delete_clean{
		background-color: #d9534f !important;
	    border-color: #d9534f !important;
	    color: #fff !important;
	    cursor: pointer;
	    display: inline-block;
	    font-size: 13px;
	    font-weight: 400;
	    line-height: 1.5;
	    margin: 0 0 0 5px !important;
	    padding: 0.375rem 1rem;
	    text-align: center;
	    vertical-align: middle;
	    white-space: nowrap;
		border:none;
		border-radius:3px;
	}
	.group_item_auto_theme .group_item_theme{
		display:block;
		margin-bottom:10px;
	}


	#confirmOverlay{
		width:100%;
		height:100%;
		position:fixed;
		top:0;
		left:0;
		background:url('../img/ie.png');
		background: -moz-linear-gradient(rgba(11,11,11,0.1), rgba(11,11,11,0.6)) repeat-x rgba(11,11,11,0.2);
		background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(11,11,11,0.1)), to(rgba(11,11,11,0.6))) repeat-x rgba(11,11,11,0.2);
		z-index:100000;
	}

	#confirmBox{
		background:url('../img/body_bg.jpg') repeat-x left bottom #e5e5e5;
		width:460px;
		position:fixed;
		left:50%;
		top:50%;
		margin:-130px 0 0 -230px;
		border: 1px solid rgba(33, 33, 33, 0.6);

		-moz-box-shadow: 0 0 2px rgba(255, 255, 255, 0.6) inset;
		-webkit-box-shadow: 0 0 2px rgba(255, 255, 255, 0.6) inset;
		box-shadow: 0 0 2px rgba(255, 255, 255, 0.6) inset;
	}

	#confirmBox h1,
	#confirmBox p{
		font:26px/1 'Cuprum','Lucida Sans Unicode', 'Lucida Grande', sans-serif;
		background:url('../img/header_bg.jpg') repeat-x left bottom #f5f5f5;
		padding: 18px 25px;
		text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.6);
		color:#666;
	}

	#confirmBox h1{
		letter-spacing:0.3px;
		color:#888;
	}

	#confirmBox p{
		background:none;
		font-size:16px;
		line-height:1.4;
		padding-top: 35px;
	}

	#confirmButtons{
		padding:15px 0 25px;
		text-align:center;
	}

	#confirmBox .button{
		display:inline-block;
		background:url('../img/buttons.png') no-repeat;
		color:white;
		position:relative;
		height: 33px;
		border-radius:0px;
		font:17px/33px 'Cuprum','Lucida Sans Unicode', 'Lucida Grande', sans-serif;

		margin-right: 15px;
		padding: 0 35px 0 40px;
		text-decoration:none;
		border:none;
	}

	#confirmBox .button:last-child{	margin-right:0;}

	#confirmBox .button span{
		position:absolute;
		top:0;
		right:-5px;
		background:url('../img/buttons.png') no-repeat;
		width:5px;
		height:33px
	}

	#confirmBox .blue{				background-position:left top;text-shadow:1px 1px 0 #5889a2;}
	#confirmBox .blue span{			background-position:-195px 0;}
	#confirmBox .blue:hover{		background-position:left bottom;}
	#confirmBox .blue:hover span{	background-position:-195px bottom;}

	#confirmBox .gray{				background-position:-200px top;text-shadow:1px 1px 0 #707070;}
	#confirmBox .gray span{			background-position:-395px 0;}
	#confirmBox .gray:hover{		background-position:-200px bottom;}
	#confirmBox .gray:hover span{	background-position:-395px bottom;}
</style>
@endsection
