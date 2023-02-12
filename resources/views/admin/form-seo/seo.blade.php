<script type="text/javascript">
	jQuery(function($){
		$('#seo_title').bind('input keyup keydown keypress', function(){
			var charCount1 = $(this).val().replace(/\s/g, '').length;
			$("#char_title").text(charCount1);
		});
		$('#seo_keyword').bind('input keyup keydown keypress', function(){
			var charCount2 = $(this).val().replace(/\s/g, '').length;
			$("#char_keyword").text(charCount2);
		});
		$('#seo_description').bind('input keyup keydown keypress', function(){
			var charCount3 = $(this).val().replace(/\s/g, '').length;
			$("#char_description").text(charCount3);
		});
	});
</script>
<!-- SEO Title-->
<div class="form-group">
	<label for="seo_title">SEO Title</label>
	<textarea id="seo_title" name="seo_title" class="form-control" rows="3"><?php echo $seo_title; ?></textarea>
	<div class="clear pd_lender" style="font-size:14px; color:#060;"><span id="char_title" style="color:#F00; font-weight:bold;"></span> ký tự, Max <b>70</b> ký tự</div>
</div>

<!-- SEO Keyword-->
<div class="form-group">
	<label for="seo_keyword">SEO Keyword</label>
	<textarea id="seo_keyword" name="seo_keyword" class="form-control" rows="3"><?php echo $seo_keyword; ?></textarea>
	<div class="clear pd_lender" style="font-size:14px;color:#060;"><span id="char_keyword" style="color:#F00; font-weight:bold;"></span> ký tự</div>
</div>

<!-- SEO Description-->
<div class="form-group">
	<label for="seo_description">SEO Description</label>
	<textarea id="seo_description" name="seo_description" class="form-control" rows="3"><?php echo $seo_description; ?></textarea>
	<div class="clear pd_lender"  style="font-size:14px;color:#060;"><span id="char_description" style="color:#F00; font-weight:bold;"></span> ký tự, Max <b>150-160</b> ký tự</div>
</div>