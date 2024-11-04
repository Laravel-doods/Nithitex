$(document).ready(function () {
    var selectedSizeID = $("#firstvariantID").val();
    if(is_variant == 1){
        productVariantData(selectedSizeID);
    }
});


function productVariantData(selectedSizeID) {
    var offer = $("#hdTodayOffer1").val();
    $.ajax({
        type: "GET",
        dataType: "json",
        data: {
            id: productId,
            variant_id: selectedSizeID,
            offer: offer
        },
        url: "/getVariantData",
        success: function (data) {
            var variant = data.variant;
            var selectedSizeID = variant.id;

            $(".pro-details-add-to-cart button").attr("data-variant-id", selectedSizeID);
            $(".wishlist-icon").attr("data-variant-id", selectedSizeID);
            $("#hdvariantID").val(selectedSizeID);

            if(variant != null){
                wishlistProduct(data.wishlist)

                if(variant.stock == 0){
                    $('#outofstock').show();
                    $('.stockProduct').hide();
                }else{
                    $('#outofstock').hide();
                    $('.stockProduct').show();
                }
                
                $('.sizeListItem').removeClass('product-Item-select'); 
                var $selectedItem = $('.sizeListItem[data-variant-id="' + selectedSizeID + '"]');
                $selectedItem.addClass('product-Item-select');


                if(isseller == 1){
                    $('#product-selling-price').text('₹' + Math.round(variant.seller_price));
                    var amount = variant.seller_price - variant.seller_price;                    
                }else{
                    $('#product-selling-price').text('₹' + Math.round(variant.customer_price));
                    var amount = variant.price - variant.customer_price;
                }

                var discount = amount / variant.price * 100;
                $('#discountPercentage').text(Math.round(discount)+'%')
                $('#product-original-price').text('₹' + Math.round(variant.price));
                $('#currentStocks').text('Available Quantity: '+variant.stock);
                $('#hidstk').val(variant.stock);
                $('#pro-sku').text('Product SKU: '+variant.product_sku);
            }
        }
    });
} 

function wishlistProduct(wishlistdata){
    if(wishlistdata == null){
        $('.wishlist-icon').removeClass('fa-solid fa-heart heart'); 
        $('.wishlist-icon').addClass('icon-heart');
    }else{
        $('.wishlist-icon').removeClass('icon-heart');
        $('.wishlist-icon').addClass('fa-solid fa-heart heart'); 
    }
}
