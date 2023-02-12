/*
 * jQuery myCart - v1.7 - 2018-03-07
 * http://asraf-uddin-ahmed.github.io/
 * Copyright (c) 2017 Asraf Uddin Ahmed; Licensed None
 */

(function ($) {

  "use strict";

  var OptionManager = (function () {
    var objToReturn = {};

    var _options = null;
    var DEFAULT_OPTIONS = {
      currencySymbol: '$',
      classCartIcon: 'my-cart-icon',
      classCartBadge: 'my-cart-badge',
      classProductQuantity: 'my-product-quantity',
      classProductRemove: 'my-product-remove',
      classCheckoutCart: 'my-cart-checkout',
      classDeleteCart: 'my-cart-delete-all',
      classBackCart: 'my-cart-back',
      linkProduct:'',
      linkCheckOutProduct:'',
      affixCartIcon: true,
      showCheckoutModal: true,
      numberOfDecimals: 0,
      cartItems: null,
      clickOnAddToCart: function ($addTocart) {},
      addTocartPHP: function (products, totalQuantity,avariable) {},
      afterAddOnCart: function (products, totalPrice, totalQuantity) {},
      updateOutCart: function (products, totalQuantity,avariable) {},
      removeProductID:function (products){},
      clickOnCartIcon: function ($cartIcon, products, totalPrice, totalQuantity) {},
      deleteAllCart: function (products, totalPrice, totalQuantity) {},
      checkoutCart: function (products, totalPrice, totalQuantity) {
        return false;
      },
      getDiscountPrice: function (products, totalPrice, totalQuantity) {
        return null;
      }
    };


    var loadOptions = function (customOptions) {
      _options = $.extend({}, DEFAULT_OPTIONS);
      if (typeof customOptions === 'object') {
        $.extend(_options, customOptions);
      }
    };
    var getOptions = function () {
      return _options;
    };

    objToReturn.loadOptions = loadOptions;
    objToReturn.getOptions = getOptions;
    return objToReturn;
  }());

  var MathHelper = (function () {
    var objToReturn = {};
    var getRoundedNumber = function (number) {
      if (isNaN(number)) {
        throw new Error('Parameter is not a Number');
      }
      number = number * 1;
      var options = OptionManager.getOptions();
        //alert(number);
      return number.toFixed(options.numberOfDecimals);
    };
    objToReturn.getRoundedNumber = getRoundedNumber;
    return objToReturn;
  }());

  var ProductManager = (function () {
    var objToReturn = {};

    /*
    PRIVATE
    */
    localStorage.products = localStorage.products ? localStorage.products : "";
    var getIndexOfProduct = function (id) {
      var productIndex = -1;
      var products = getAllProducts();
      $.each(products, function (index, value) {
        if (value.id == id) {
          productIndex = index;
          return;
        }
      });
      return productIndex;
    };
    var setAllProducts = function (products) {
      localStorage.products = JSON.stringify(products);
    };
    var addProduct = function (id, name, summary, price, quantity, image,avariable) {
      var products = getAllProducts();
      products.push({
        id: id,
        name: name,
        summary: summary,
        price: price,
        quantity: quantity,
        image: image,
        avariable:avariable
      });
      setAllProducts(products);
    };

    /*
    PUBLIC
    */
    var getAllProducts = function () {
      try {
        var products = JSON.parse(localStorage.products);
        return products;
      } catch (e) {
        return [];
      }
    };
    var updatePoduct = function (id, quantity) {
      var productIndex = getIndexOfProduct(id);
      if (productIndex < 0) {
        return false;
      }
      var products = getAllProducts();
      products[productIndex].quantity = typeof quantity === "undefined" ? products[productIndex].quantity * 1 + 1 : quantity;
      setAllProducts(products);
      return true;
    };
    var setProduct = function (id, name, summary, price, quantity, image,avariable) {
      if (typeof id === "undefined") {
        console.error("id required");
        return false;
      }
      if (typeof name === "undefined") {
        console.error("name required");
        return false;
      }
      if (typeof image === "undefined") {
        console.error("image required");
        return false;
      }
      if (typeof avariable === "undefined") {
            console.error("avariable required");
            return false;
      }
      if (!$.isNumeric(price)) {
        console.error("price is not a number");
        return false;
      }
      if (!$.isNumeric(quantity)) {
        console.error("quantity is not a number");
        return false;
      }
      summary = typeof summary === "undefined" ? "" : summary;

      if (!updatePoduct(id)) {
        addProduct(id, name, summary, price, quantity, image,avariable);
      }
    };
    var clearProduct = function () {
      setAllProducts([]);
    };
    var removeProduct = function (id) {
      var products = getAllProducts();
      products = $.grep(products, function (value, index) {
        return value.id != id;
      });
      setAllProducts(products);
    };
    var getTotalQuantity = function () {
      var total = 0;
      var products = getAllProducts();
      $.each(products, function (index, value) {
        total += value.quantity * 1;
      });
      return total;
    };
    var getTotalPrice = function () {
      var products = getAllProducts();
      var total = 0;
      $.each(products, function (index, value) {
        total += value.quantity * value.price;
        total = MathHelper.getRoundedNumber(total) * 1;
      });
      return total;
    };

    objToReturn.getAllProducts = getAllProducts;
    objToReturn.updatePoduct = updatePoduct;
    objToReturn.setProduct = setProduct;
    objToReturn.clearProduct = clearProduct;
    objToReturn.removeProduct = removeProduct;
    objToReturn.getTotalQuantity = getTotalQuantity;
    objToReturn.getTotalPrice = getTotalPrice;
    return objToReturn;
  }());


  var loadMyCartEvent = function (targetSelector) {

    var options = OptionManager.getOptions();
    var $cartIcon = $("." + options.classCartIcon);
    var $cartBadge = $("." + options.classCartBadge);
    var classProductQuantity = options.classProductQuantity;
    var classProductRemove = options.classProductRemove;
    var classCheckoutCart = options.classCheckoutCart;

    var classDeleteCart = options.classDeleteCart;
    var classBackCart = options.classBackCart;

    var idCartModal = 'my-cart-modal';
    var idCartTable = 'my-cart-table';
    var idGrandTotal = 'my-cart-grand-total';
    var idEmptyCartMessage = 'my-cart-empty-message';
    var idDiscountPrice = 'my-cart-discount-price';
    var classProductTotal = 'my-product-total';
    var classAffixMyCartIcon = 'my-cart-icon-affix';


    if (options.cartItems && options.cartItems.constructor === Array) {
      ProductManager.clearProduct();
      $.each(options.cartItems, function () {
        ProductManager.setProduct(this.id, this.name, this.summary, this.price, this.quantity, this.image,this.avariable);
      });
    }

    $cartBadge.text(ProductManager.getTotalQuantity());

    if (!$("#" + idCartModal).length) {
      $('body').append(
        '<div class="modal modal-dialog modal-lg" id="' + idCartModal + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">' +
        '<div class="modal-dialog" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-shopping-cart"></span> Giỏ hàng của bạn</h4>' +
        '</div>' +
        '<div class="modal-body">' +
        '<table class="table table-hover table-responsive" id="' + idCartTable + '"></table>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-default ' + classBackCart + '"><span class="dslc-icon-ext-ccw"></span> Mua hàng tiếp</button>' +
        '<button type="button" class="btn btn-danger ' + classDeleteCart + '" ><span class="dslc-icon-ext-arrows_circle_remove"></span> Xóa hết</button>' +
        '<button type="button" class="btn btn-primary ' + classCheckoutCart + '"><span class="dslc-icon-ext-ecommerce_bag_check"></span> Thanh toán</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'
      );
    }

    var drawTable = function () {
      var $cartTable = $("#" + idCartTable);
      $cartTable.empty();
      $cartTable.append('<tr>' +
          '<th class="text-center">Hình ảnh</th>' +
          '<th class="text-center">Tên SP</td>' +
          '<th title="Unit Price" class="text-center">Giá</th>' +
          '<th title="Quantity" class="text-center">Số lượng</th>' +
          '<th title="Total" class="text-right ' + classProductTotal + '">Tổng</th>' +
          '<th title="Remove from Cart" class="text-center" style="width: 30px;">Xóa</th>' +
          '</tr>');
      var products = ProductManager.getAllProducts();
      //console.log('Products:'+JSON.stringify(products));
      $.each(products, function () {
        var total = this.quantity * this.price;
        //var ob_option_variable=jQuery.parseJSON(this.avariable);
        //console.log('Productsaa:'+this.avariable.color);
        var html_variable="";
        if(this.avariable=== null) {

        }else{
            if (this.avariable.color != '') {
                if (typeof this.avariable.color === "undefined") {
                    html_variable +='';
                }else{
                    html_variable += '<p class="color_atc">Màu:<span>' + this.avariable.color + '</span></p>';
                }
            }
            if (this.avariable.size != '') {
                if (typeof this.avariable.size === "undefined") {
                    html_variable +='';
                }else{
                    html_variable += '<p class="size_atc">Cỡ:<span>' + this.avariable.size + '</span></p>';
                }
            }
        }
        $cartTable.append(
          '<tr class="list_product_item_cart" title="' + this.summary + '" data-id="' + this.id + '" data-price="' + this.price + '">' +
          '<td class="text-center" style="width: 30px;"><img width="70px" height="70px" src="' + this.image + '"/></td>' +
          '<td class="text-center">' + this.name + html_variable +'</td>' +
          '<td title="Unit Price" class="text-right">' + accounting.formatNumber(MathHelper.getRoundedNumber(this.price),0,".", ",")+" "+ options.currencySymbol + '</td>' +
          '<td title="Quantity" style="width: 97px;"><input type="number" min="1" style="width: 55px;" class="' + classProductQuantity + '" value="' + this.quantity + '"/></td>' +
          '<td title="Total" class="text-right ' + classProductTotal + '">'+ accounting.formatNumber(MathHelper.getRoundedNumber(total),0,".", ",") +" "+ options.currencySymbol+'</td>' +
          '<td title="Remove from Cart" class="text-center" style="width: 30px;"><a href="javascript:void(0);" class="btn btn-xs btn-danger ' + classProductRemove + '">X</a></td>' +
          '</tr>'
        );
      });

      $cartTable.append(products.length ?
        '<tr>' +
        '<td></td>' +
        '<td><strong>Tổng Tiền</strong></td>' +
        '<td></td>' +
        '<td></td>' +
        '<td class="text-right"><strong id="' +idGrandTotal+ '"></strong></td>' +
        '<td></td>' +
        '</tr>' :
        '<tr><td colspan="6"><div class="alert alert-danger" role="alert" id="' + idEmptyCartMessage + '">Giỏ hàng rỗng!</div></td></tr>'
      );
/*
      var discountPrice = options.getDiscountPrice(products, ProductManager.getTotalPrice(), ProductManager.getTotalQuantity());
      if (products.length && discountPrice !== null) {
        $cartTable.append(
          '<tr style="color: red">' +
          '<td></td>' +
          //'<td><strong>Total (including discount)</strong></td>' +
          '<td></td>' +
          '<td></td>' +
          //'<td class="text-right"><strong id="' + idDiscountPrice + '"></strong></td>' +
          '<td></td>' +
          '</tr>'
        );
      }
*/
      showGrandTotal();
      //showDiscountPrice();
    };
    /*
    var showModal = function () {
      drawTable();
      $("#" + idCartModal).modal('show');
    };
    */
    var updateCart = function () {
      $.each($("." + classProductQuantity), function () {
        var id = $(this).closest("tr").data("id");
        ProductManager.updatePoduct(id, $(this).val());
      });
    };
    var showGrandTotal = function () {
      $("#" + idGrandTotal).text(accounting.formatNumber(MathHelper.getRoundedNumber(ProductManager.getTotalPrice()),0,".", ",")+" "+options.currencySymbol);
    };
    var showDiscountPrice = function () {
      $("#" + idDiscountPrice).text(MathHelper.getRoundedNumber(options.getDiscountPrice(ProductManager.getAllProducts(), ProductManager.getTotalPrice(), ProductManager.getTotalQuantity()))+" "+options.currencySymbol);
    };

    /*
    EVENT
    */

    if (options.affixCartIcon) {
      var cartIconBottom = $cartIcon.offset().top * 1 + $cartIcon.css("height").match(/\d+/) * 1;
      var cartIconPosition = $cartIcon.css('position');
      $(window).scroll(function () {
        $(window).scrollTop() >= cartIconBottom ? $cartIcon.addClass(classAffixMyCartIcon) : $cartIcon.removeClass(classAffixMyCartIcon);
      });
    }

    $cartIcon.click(function () {
      options.showCheckoutModal ? showModal() : options.clickOnCartIcon($cartIcon, ProductManager.getAllProducts(), ProductManager.getTotalPrice(), ProductManager.getTotalQuantity());
    });

    $(document).on("input", "." + classProductQuantity, function () {
      var price = $(this).closest("tr").data("price");
      var id = $(this).closest("tr").data("id");
      var quantity = $(this).val();

      $(this).parent("td").next("." + classProductTotal).text(accounting.formatNumber(MathHelper.getRoundedNumber(MathHelper.getRoundedNumber(price * quantity)),0,".", ",") +" "+options.currencySymbol);
      ProductManager.updatePoduct(id, quantity);

      $cartBadge.text(ProductManager.getTotalQuantity());
      showGrandTotal();
      options.updateOutCart(id, quantity);
      //showDiscountPrice();
    });

    $(document).on('keypress', "." + classProductQuantity, function (evt) {
      //if (evt.keyCode == 38 || evt.keyCode == 40) {
        //return;
      //}
      //evt.preventDefault();
    });

    $(document).on('click', "." + classProductRemove, function () {
      var $tr = $(this).closest("tr");
      var id = $tr.data("id");
      $tr.hide(500, function () {
        ProductManager.removeProduct(id);
        drawTable();
        $cartBadge.text(ProductManager.getTotalQuantity());
      });
        options.removeProductID(id);
    });

    $(document).on('click', "." + classDeleteCart, function () {
        var $cartTable = $("#" + idCartTable);
        options.deleteAllCart();
        $cartTable.empty();
        $cartTable.append('<div class="alert alert-danger" role="alert" id="' + idEmptyCartMessage + '">Giỏ hàng rỗng!</div>');
        location.href =options.linkProduct;
    });
    $(document).on('click', "." + classBackCart, function () {
        $("#" + idCartModal).modal("hide");
        //linkProduct:'',
         //linkCheckOutProduct:'',
        location.href =options.linkProduct;
    });

    $(document).on('click', "." + classCheckoutCart, function () {
      var products = ProductManager.getAllProducts();
      if (!products.length) {
        $("#" + idEmptyCartMessage).fadeTo('fast', 0.5).fadeTo('fast', 1.0);
        return;
      }
      updateCart();
      var isCheckedOut = options.checkoutCart(ProductManager.getAllProducts(), ProductManager.getTotalPrice(), ProductManager.getTotalQuantity());
      if (isCheckedOut !== false) {
        ProductManager.clearProduct();
        $cartBadge.text(ProductManager.getTotalQuantity());
        $("#" + idCartModal).modal("hide");
      }
    });

    $(document).on('click', targetSelector, function () {
      var $target = $(this);
      options.clickOnAddToCart($target);
      var id = $target.data('id');
      var name = $target.data('name');
      var summary = $target.data('summary');
      var price = $target.data('price');
      var quantity = $target.data('quantity');
      var image = $target.data('image');
      var avariable = $target.data('option');
      ProductManager.setProduct(id, name, summary, price, quantity, image,avariable);
      $cartBadge.text(ProductManager.getTotalQuantity());
      //alert('OK',avariable,name);
      options.addTocartPHP(id,quantity,avariable);
      options.afterAddOnCart(ProductManager.getAllProducts(), ProductManager.getTotalPrice(), ProductManager.getTotalQuantity());
      // showModal();
      //options.showCheckoutModal ? showModal();
      //options.showCheckoutModal ? showModal() : options.clickOnCartIcon($cartIcon, ProductManager.getAllProducts(), ProductManager.getTotalPrice(), ProductManager.getTotalQuantity());

        //showModal();
    });

  };


  $.fn.myCart = function (userOptions) {
    OptionManager.loadOptions(userOptions);
    loadMyCartEvent(this.selector);
    return this;
  };


})(jQuery);