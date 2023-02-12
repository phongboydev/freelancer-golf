jQuery(document).ready(function($){
	//slide_top
	$('#instagram .wrap-slide').slick({

		infinite: true,
		slidesToShow: 3,
		draggable: false,
		pauseOnFocus: false,
		pauseOnHover: false,
		autoplay: true,
		touchMove: false,
		swipe: false,
		autoplaySpeed: 0,
		speed: 4700,
		dots: false,
		arrows: false,
		cssEase: 'linear',
		responsive: [
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 3,
				rows:2,
				slidesPerRow: 2,
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 2,
			}
		}
		],

	});
	//end slide_top

	//scroll menu
	$('.menu li a').click(function() {
		let target = $(this).attr("href");
		var href = $(this).attr('href');

		$('html,body').stop().animate({
			scrollTop: $(target).offset().top - 50
		}, 1000);
	});
	//end scrol menu

	//event click photos
	$('.img-thum-photos img').click(function(e) {
		e.preventDefault();
		let src_img = $(this).attr('src');
		$('#avt_photos').attr('src', src_img);
		$('.link-avt-photo').attr('href', src_img);

		//get link img
		// var html="";
		// $('.ebike-img .img-thum-photos img').each(function() {
		// 	var src = $(this).attr('src');
		// 	$('.ebike-img .box-img-light').html("");
		// 	html +="<div style='display: none;' class='img-thum-photos'>";
		// 	html +="<a data-lightbox='show-img' href='"+src+"'><img src='"+src+"' alt=''></a>";
		// 	html +="</div>";
		// 	$('.ebike-img .box-img-light').append(html);
		// });

		//alert(src_img);
	});
	//end event click photos

	//count price home
	var price_double_seat = 0;
	var check_led_head = 0;
	var price_unit = 200000;
	$('.option_price').change(function(e){
		var name = jQuery(this).attr('name');
		if($('input[name="'+name+'"]').is(':checked')){
			var val_radio = jQuery('input[name="'+name+'"]:checked').val();		
		}
		else{
			var val_radio =0;
		}
		console.log(val_radio);
	});


	//FORM ====================================================
	var bike_price_hidden 			= $('.bike_price_hidden').val();
	var price_led_hidden 			= $('.price-led-hidden').val();
	var price_double_seat_hidden 	= $('.price-double-seat-hidden').val();

	var price_text_total 		= 0;
	var seatdouble_price_total 	= 0;
	var headlight_price_total 	= 0;
	var price_bike_num_total 	= 0;
	$('.price-tax').html('¥'+'0');
	//1: chose bike price
	$('#number_product_bike').click(function() {
		var num_bike = $(this).val();
		var price_bike_num = num_bike*bike_price_hidden;
		price_bike_num_total = price_bike_num;

		$('.num-bike').html(num_bike);
		$('.price-bike_val').html('¥'+number_format(price_bike_num_total));
		$('.number_product_bike_total_price').val(price_bike_num_total);

		var total_price = seatdouble_price_total+headlight_price_total;
		var total_price_tax = total_price + (total_price*0.1)+price_bike_num_total+price_text_total;

		$('.price-tax').html('¥'+number_format(total_price*0.1));
		$('.total-price').html('¥'+number_format(total_price_tax));
	});
	//2: add text
	$('.text-add-ip').change(function() {
		let text = $(this).val();
		let num_text = text.length;
		var price_text = 0;

		if(num_text >= 3) {
			price_text = 500*(num_text-2);
		}
		else {
		}

		$('.text-char').html(text);
		$('.text-num').html(num_text);
		$('.logo-price').text();
		$('.text-add-ip-hidden').val(price_text);
		$('.text_length').html('¥'+number_format(price_text));


		//$('.total-price').html(number_format(price_text));

		price_text_total=price_text;

		var total_price = price_text_total+ seatdouble_price_total+headlight_price_total+price_bike_num_total;
		$('.total-price').html('¥'+number_format(total_price));

	});
	//3: chose logo
	var logo = 0;
	$('.sl-logo').click(function() {
		var logo_val = $(this).val();
		var logo_text = "希望しない";//no
		if(logo==0) {
			logo = logo_val;
			logo_text = '希望する';
		}
		else {
			logo = 0;
			logo_text = "希望しない";
		}

		$('.number_logo_print').val(logo);
		$('.logo-text').html(logo_text);
	});
	//4: chose position for logo
	var pos_logo_A="";
	var pos_logo_B="";
	var pos_logo_C="";
	$('.option_price_check').click(function() {
		var pos_logo = $(this).val();
		
		if(pos_logo=="A") {
			if(pos_logo_A=="") {
				pos_logo_A = pos_logo;
				logo_text = 'A';
			}
			else {
				pos_logo_A ="";
				logo_text = "";
			}
		}
		else if(pos_logo=="B"){
			if(pos_logo_B=='') {
				pos_logo_B = pos_logo;
				logo_text = 'B';

			}
			else {
				pos_logo_B ="";
				logo_text = "";
			}
		}
		else if(pos_logo=="C"){
			if(pos_logo_C=='') {
				pos_logo_C = pos_logo;
				logo_text = 'C';
			}
			else {
				pos_logo_C ="";
				logo_text = "";
			}
		}

		var pos_num=0;
		if(pos_logo_A=="A") {
			pos_num+=1;
		}
		if(pos_logo_B=="B") {
			pos_num+=1;
		}
		if(pos_logo_C=="C") {
			pos_num+=1;
		}
		
		$('.pos-logo-num').html(pos_num);
		$('.pos-logo-text_A').html(pos_logo_A);
		$('.pos-logo-text_B').html(pos_logo_B);
		$('.pos-logo-text_C').html(pos_logo_C);

	});
	//5: chose seatdouble
	$('#sl_seatdouble').click(function() {
		var seatdouble = $(this).val();
		console.log(seatdouble);
		var seat_Price = seatdouble*price_led_hidden;
		$('.seatdouble_num').html(seatdouble);
		$('.seatdouble_price').val(seat_Price);
		$('.seatdouble_price').html('¥'+number_format(seat_Price));
		seatdouble_price_total=seat_Price;
		//TOTAL PRICE
		var total_price = seatdouble_price_total+headlight_price_total;
		var total_price_tax = total_price + (total_price*0.1)+price_bike_num_total+price_text_total;

		$('.price-tax').html('¥'+number_format(total_price*0.1));
		$('.total-price').html('¥'+number_format(total_price_tax));

	});
	//6: chose headlight
	$('#sl_headlight').click(function() {
		var headlight = $(this).val();
		console.log(headlight);
		var headlight_Price = headlight*price_double_seat_hidden;
		$('.headlight_num').html(headlight);
		$('.headlight_price').val(headlight_Price);
		$('.headlight_price').html('¥'+number_format(headlight_Price));
		headlight_price_total=headlight_Price;

		//TOTAL PRICE
		var total_price = seatdouble_price_total+headlight_price_total;
		var total_price_tax = total_price + (total_price*0.1)+price_bike_num_total+price_text_total;

		$('.price-tax').html('¥'+number_format(total_price*0.1));
		$('.total-price').html('¥'+number_format(total_price_tax));
	});
	//TOTAL PRICE
	// $('.total-price').html('¥0');
	//END FORM
	$.validator.addMethod(
	    /* The value you can use inside the email object in the validator. */
	    "regex",

	    /* The function that tests a given string against a given regEx. */
	    function(value, element, regexp)  {
	        /* Check if the value is truthy (avoid null.constructor) & if it's not a RegEx. (Edited: regex --> regexp)*/

	        if (regexp && regexp.constructor != RegExp) {
	           /* Create a new regular expression using the regex argument. */
	           regexp = new RegExp(regexp);
	        }

	        /* Check whether the argument is global and, if so set its last index to 0. */
	        else if (regexp.global) regexp.lastIndex = 0;

	        /* Return whether the element is optional or the result of the validation. */
	        return this.optional(element) || regexp.test(value);
	    }
	);

	$.validator.addMethod('filesize', function (value, element, param) {
	    return this.optional(element) || (element.files[0].size <= param)
	}, 'File size must be less than {0}');

	$("#payment-form").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            family_name: "required",
            firstname: "required",
            familyname_kana: "required",
            firstname_kana: "required",
            email: {
		        required: true,
		        email: true,
		        regex: /^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
		    },
		    file_logo: {
                required: true,
                extension: 'jpg|jpeg|png',
                filesize: 4194304
            },
            zipcode_first: "required",
            zipcode_last: "required",
            provice: "required",
            city : "required",
            address : "required",
            phone: "required"
        },
        messages: {
            family_name: "This field is required",
            firstname: "This field is required",
            familyname_kana: "This field is required",
            firstname_kana : "This field is required",
            email : "This field is required",
            zipcode_first: "This field is required",
            zipcode_last: "This field is required",
            provice: "This field is required",
            city: "This field is required",
            address: "This field is required",
            phone: "This field is required"
        },
    });
	//entry form =========================================
	//1: check for create account
	var check_create_acc_vl=0;
	$('#pw_to_cre_acc').css('display', 'none');
	$('.check-to-createacc').click(function() {
		var check_vl = $(this).val();

		if(check_vl==0) {
			check_create_acc_vl=1;
			$(this).val(1);
			$('#pw_to_cre_acc').css('display', 'block');
		}
		else if(check_vl==1) {
			check_create_acc_vl=0;
			$(this).val(0);
			$('#pw_to_cre_acc').css('display', 'none');
		}
	});
	//xử lý stripe

	// Create a Stripe client.
	var stripe = Stripe('pk_test_51GvcZTIuUlTefRYS0asyIGGtTzyrQYFVlkEVXFBMW0daiHuHTLFJrw6CYI9VFVpGEvXdDhqI7Mk2NG91VFyTXrQc00MO4UB7zf');

	// Create an instance of Elements.
	var elements = stripe.elements();

	// Custom styling can be passed to options when creating an Element.
	// (Note that this demo uses a wider set of styles than the guide below.)
	var style = {
	  base: {
	    color: '#32325d',
	    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
	    fontSmoothing: 'antialiased',
	    fontSize: '16px',
	    '::placeholder': {
	      color: '#aab7c4'
	    }
	  },
	  invalid: {
	    color: '#fa755a',
	    iconColor: '#fa755a'
	  }
	};

	// Create an instance of the card Element.
	var card = elements.create('card', {style: style});
	$("input[name=rd_payment]").change(function() {
		if($(this).val() == 0){
			card.mount('#card-element');
			// Create a token or display an error when the form is submitted.
			var form = document.getElementById('payment-form');
			form.addEventListener('submit', function(event) {
			  event.preventDefault();

			  stripe.createToken(card).then(function(result) {
			    if (result.error) {
			      // Inform the customer that there was an error.
			      var errorElement = document.getElementById('card-errors');
			      errorElement.textContent = result.error.message;
			    } else {
			      // Send the token to your server.
			      stripeTokenHandler(result.token);
			    }
			  });
			});
		} else{
			$('#card-element').html('');
		}
	});

	// Add an instance of the card Element into the `card-element` <div>.
});
//end jquery
function stripeTokenHandler(token) {
	// Insert the token ID into the form so it gets submitted to the server
	var form = document.getElementById('payment-form');
	var hiddenInput = document.createElement('input');
	hiddenInput.setAttribute('type', 'hidden');
	hiddenInput.setAttribute('name', 'stripeToken');
	hiddenInput.setAttribute('value', token.id);
	form.appendChild(hiddenInput);

	$("#payment-form").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            family_name: "required",
            firstname: "required",
            familyname_kana: "required",
            firstname_kana: "required",
            email: {
		        required: true,
		        email: true,
		        regex: /^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
		    },
		    file_logo: {
                required: true,
                filesize: 4194304,
                extension: "png|jpeg|jpg"
            },
            zipcode_first: "required",
            zipcode_last: "required",
            provice: "required",
            city : "required",
            address : "required",
            phone: "required"
        },
        messages: {
            family_name: "This field is required",
            firstname: "This field is required",
            familyname_kana: "This field is required",
            firstname_kana : "This field is required",
            email : "This field is required",
            zipcode_first: "This field is required",
            zipcode_last: "This field is required",
            provice: "This field is required",
            city: "This field is required",
            address: "This field is required",
            phone: "This field is required"
        },
    });
    if($("#payment-form").valid()){
		form.submit();
    } else{
    	return;
    }
	// Submit the form
}
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
    	var k = Math.pow(10, prec);
    	return '' + Math.round(n * k) / k;
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
    	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
    	s[1] = s[1] || '';
    	s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}