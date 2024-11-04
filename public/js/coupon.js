function docouponupdate(item) {
    if (confirm("Are you sure you want to change the Status?")) {
        $("#coupon_submit" + item).submit();
    }
}

function commoncouponupdate(item) {
    if (confirm("Are you sure you want to change this coupon as common?")) {
        $("#coupon_common" + item).submit();
    }
}