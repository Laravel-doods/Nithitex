
$(document).ready(function () {
    miniCart();
    wishlist();
    cart();
});

function addToCart(button) {
    var variant_id = $(button).attr("data-variant-id");
    var offer = $(button).attr("data-offer");

    var product_name = $('#product_name').text();
    var id = $('#product_id').val();
    // var color = $('#color option:selected').text();
    // var size = $('#size option:selected').text();
    var quantity = $('#qty').val();

    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    quantity: quantity,
                    product_name: product_name,
                    variant_id: variant_id != '' ? variant_id : null,
                    offer: offer,
                    device_id: device_id
                },
                url: "/cart/data/store/" + id,
                success: function (data) {
                    miniCart()
                    $('#closeModel').click();
                    // console.log(data)
                    //  Start Message 
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.success
                        })

                    } else {
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error
                        })

                    }
                }
            })
        });
    });
}
function addToCartsimple(id, variant_id) {
    var today_offer = $('#offer').val();
    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    variant_id: variant_id,
                    today_offer: today_offer,
                    device_id: device_id
                },
                url: "/simplecart/store/" + id,
                success: function (data) {
                    miniCart()
                    $('#closeModel').click();
                    // console.log(data)
                    //  Start Message 
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.success
                        })

                    } else {
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error
                        })

                    }
                }
            });
        });
    });
}

function miniCart() {
    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: 'GET',
                url: '/product/mini/cart',
                data: { device_id: device_id },
                dataType: 'json',
                success: function (response) {
                    if (response.carts != undefined && response.carts.length > 0) {
                        $(".cart-content").show();
                        $(".empty-cart").hide();
                        $('span[id="cartSubTotal"]').text('₹' + response.cartTotal);
                        $('span[id="cartQty"]').text(response.cartQty);
                        var miniCart = ""

                        $.each(response.carts, function (key, value) {

                            var size = value.size != null ? `<span style="background-color: #bb2d3b;padding: 0px 5px;/* border: 1px solid #6c6a6a; */border-radius: 50%;color: white;">${value.size}</span>` : '';

                            miniCart += `<li class="single-product-cart">
                                          <div class="cart-img">
                                            <a>
                                              <img src="${value.product_image}" alt="">
                                            </a>
                                          </div>
                                          <div class="cart-title">
                                            <h4>
                                              <a>${value.name} ${size}</a>
                                            </h4>
                                            <span>  ${value.qty} * ${parseInt(value.price)} </span>
                                          </div>
                                          <div class="cart-delete">
                                            <button type="button" id="${value.id}" class="btn btn-danger"onclick="miniCartRemove(this.id)"><i class="icon-trash"></i></button> </div>
                                          </div>
                                        </li>`
                        });

                        $('#miniCart').html(miniCart);
                    } else {
                        $(".cart-content").hide();
                        $(".empty-cart").show();
                        $('span[id="cartQty"]').text(0);
                    }
                }
            });
        });
    });
}

function miniCartRemove(rowId) {
    $.ajax({
        type: 'GET',
        url: '/minicart/product-remove/' + rowId,
        dataType: 'json',
        success: function (data) {
            miniCart();
            // Start Message 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            })
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success
                })
            } else {
                Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error
                })
            }
            // End Message 
        }
    });
}

function addToWishList(product_id) {

    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    variant_id: null,
                    device_id: device_id
                },
                url: "/add-to-wishlist/" + product_id,
                success: function (data) {
                    $('span[id="wishlist_count"]').text(data.wishlistQty);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',

                        showConfirmButton: false,
                        timer: 3000
                    })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.success
                        })
                    } else {
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error
                        })
                    }
                    // End Message 
                }
            });
        });
    });
}

function productdetailaddToWishList(product_id) {
    var variant_id = $(".wishlist-icon[data-variant-id]").attr("data-variant-id");
    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    variant_id: variant_id == '' ? null : variant_id
                },
                url: "/add-to-wishlist/" + product_id,
                success: function (data) {

                    if (data.variant_wishlist) {
                        wishlistProduct(data.variant_wishlist)
                    }
                    $('span[id="wishlist_count"]').text(data.wishlistQty);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',

                        showConfirmButton: false,
                        timer: 3000
                    })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.success
                        })
                    } else {
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: data.error
                        })
                    }
                    // End Message 
                }
            });
        });
    });
}

function wishlist() {
    $('span[id="wishlist_count"]').text("0");
    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: 'GET',
                url: '/get-wishlist-product',
                data: {
                    device_id: device_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.wishlistQty > 0) {
                        $('span[id="wishlist_count"]').text(response.wishlistQty);
                        var rows = ""
                        $.each(response.wishlist, function (key, value) {
                            if (value.size) {
                                var price = response.userrole_id == 1 ? value.customer_price : value.seller_price;
                                var product_name = value.product.product_name + " - " + value.size;
                            } else {
                                var price = response.userrole_id == 1 ? value.product.product_discount : value.product.seller_discount;
                                var product_name = value.product.product_name
                            }
                            var addToCartButton = "";
                            if (value.stock > 0) {
                                addToCartButton = `<button type="button" data-bs-toggle="modal" class="btn btn-dark btn-sm" variant_id ="${value.variant_id}" id="${value.product.id}" onclick="addToCartsimple(this.id, this.variant_id)">Add To Cart</button>`;
                            } else {
                                addToCartButton = `<button type="button" data-bs-toggle="modal" class="btn btn-warning btn-sm">Out of Stock</button>`;
                            }
                            rows += `<tr>
                          <td class="product-thumbnail">
                              <a><img src="${value.product.product_image}" alt=""style="width:60px; height:60px;"></a>
                          </td>
                          <td class="product-name"><a><span id="pname">
                            ${product_name}</span></a></td>
                          <td class="product-price-cart">
                            ${price}
                            </td>
                          <td class="product-wishlist-cart">
                            ${addToCartButton}
                            <button type="button" id="${value.id}" class="btn btn-danger"onclick="wishlistRemove(this.id)"><i class="icon-trash"></i></button>
                          </td>
                      </tr>`
                        });

                        $('#wishlist').html(rows);
                        $(".mywishlist").removeClass("hidden");
                        $("#emptywishlist").addClass("hidden");
                    }
                    else {
                        $("#emptywishlist").removeClass("hidden");
                        $(".mywishlist").addClass("hidden");
                    }
                }
            });
        });
    });
}

function wishlistRemove(id) {
    $.ajax({
        type: 'GET',
        url: '/wishlist-remove/' + id,
        dataType: 'json',
        success: function (data) {
            wishlist();
            // Start Message 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',

                showConfirmButton: false,
                timer: 3000
            })
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success
                })
            } else {
                Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error
                })
            }
            // End Message 
        }
    });
}

function cart() {
    $('span[id="cartQty"]').text("0");

    FingerprintJS.load().then(fp => {
        fp.get().then(result => {
            var device_id = result.visitorId;
            $.ajax({
                type: 'GET',
                url: '/get-cart-product',
                data: { device_id: device_id },
                dataType: 'json',
                success: function (response) {
                    // console.log(response.cartQty);
                    if (response.cartQty > 0) {
                        $('span[id="cartSubTotal"]').text('₹' + response.cartTotal);
                        $('span[id="cartQty"]').text(response.cartQty);
                        var rows = ""
                        $.each(response.carts, function (key, value) {
                            if (value.variant_id != null) {
                                var current_stock = value.stock;
                            } else {
                                var current_stock = value.current_stock;
                            }
                            var size = value.size != null ? `<span style="background-color: #bb2d3b;padding: 0px 5px;/* border: 1px solid #6c6a6a; */border-radius: 50%;color: white;">${value.size}</span>` : '';
                            rows += `<tr>
                    <td class="product-thumbnail">
                        <a><img src="${value.image}" style="width:60px; height:60px;"></a>
                    </td>
                    <td class="product-name"><a>${value.name} ${size}</a></td>
                    <td class="product-price-cart"><span class="amount">₹${parseInt(value.price)}</span></td>
                    <td>
                      ${current_stock > 0
                                    ? `${value.qty > 1
                                        ? `<button type="button" class="btn btn-danger btn-sm" id="${value.id}" onclick="cartDecrement(this.id)" >-</button> `
                                        : ` `
                                    }
                              <input type="text" value="${value.qty}" min="1" max="100" disabled="" style="width:35px;" >  
                              ${parseInt(current_stock) <= value.qty
                                        ? ''
                                        : `<button type="button" class="btn btn-success btn-sm" id="${value.id}" onclick="cartIncrement(this.id)" >+</button>`
                                    }
                              <p class="bg-light">(Avl.qty) = ${current_stock}</p>`
                                    : '<span class="btn btn-sm btn-warning">Out of Stock</span>'
                                }                         
                    </td> 
                    <td class="product-subtotal">₹${value.total}</td>
                    <td class="product-remove">
                      <button type="button" id="${value.id}" class="btn btn-danger"onclick="cartRemove(this.id)"><i class="icon-trash"></i></button>
                    </td>
                </tr>`
                        });
                        $('#cartPage').html(rows);
                        $(".mycart").removeClass("hidden");
                        $("#emptycart").addClass("hidden");
                    }
                    else {
                        $("#emptycart").removeClass("hidden");
                        $(".mycart").addClass("hidden");
                    }
                }
            });
        });
    });
}
///  Cart  remove Start 
function cartRemove(rowId) {
    $.ajax({
        type: 'GET',
        url: '/cart-remove/' + rowId,
        dataType: 'json',
        success: function (data) {
            cart();
            miniCart();
            // Start Message 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',

                showConfirmButton: false,
                timer: 3000
            })
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success
                })
            } else {
                Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error
                })
            }
            // End Message 
        }
    });
}
// End Cart  remove 
// -------- CART INCREMENT --------//
function cartIncrement(id) {
    // alert(rowId)
    $.ajax({
        type: 'GET',
        url: "/cart-increment/" + id,
        dataType: 'json',
        success: function (data) {
            cart();
            miniCart();
        }
    });
}
// ---------- END CART INCREMENT -----///
// -------- CART Decrement  --------//
function cartDecrement(id) {
    $.ajax({
        type: 'GET',
        url: "/cart-decrement/" + id,
        dataType: 'json',
        success: function (data) {
            cart();
            miniCart();
        }
    });
}
// ---------- END CART Decrement -----///