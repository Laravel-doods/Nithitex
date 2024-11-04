var EditIndex = 0;
var RowIndex = 1;

$(document).ready(function () {
    getCategoryCode();

    $("#divVariantSize").hide();
    $("#divVariantOther").hide();
    // $("#divVariantStock").hide();

    if (isSize !== 'undefined' && isSize) {
        $("#ddlVariantType").val("0").trigger("change");
        $("#ddlVariantType").prop('disabled', true);
        $("#divVariantSize").show();
        // $("#divVariantStock").show();
    }
    if (isOther !== 'undefined' && isOther) {
        $("#ddlVariantType").val("1").trigger("change");
        $("#ddlVariantType").prop('disabled', true);
        $("#divVariantOther").show();
        // $("#divVariantStock").hide();
    }

    if (isEdit == 1) {
        RowIndex = $("#tbodyVariantList").find("tr").length + 1;
    }

    //Choose variant type
    $("#ddlVariantType").on("change", function () {
        var selectedValue = $(this).val();
        $("#divVariantSize, #divVariantOther").hide();
       
        formClear();
        if (selectedValue === "0") {
            $("#divVariantSize").show();
        } else if (selectedValue === "1") {
            $("#divVariantOther").show();
        }
        $("#hdVariantType").val(selectedValue);
    });
});

function addVariant() {
    if (validateVariant()) {
        var editRowIndex = $("#hdProductVariant").val();
        var productVariantData = "";
        var variantType = $("#ddlVariantType").val();
        var variantSize = $("#ddlVariantSize").val();
        var variantOtherSize = $("#ddlVariantotherSize").val();

        var VSZ = $("#ddlVariantSize").is(":visible") ?  variantSize : variantOtherSize;
        var VSZT = $("#ddlVariantSize").is(":visible") ?  $("#ddlVariantSize option:selected").text() : variantOtherSize;


        var VSTK = $("#numVariantStock").val();
        var VPRC = $("#numVariantMRPPrice").val();
        var VCSPrice = $("#numVariantCSPrice").val();
        var VRSPrice = $("#numVariantRSPrice").val();
        var typ = VSZ;

        var existingRows = $("#tbodyVariantList").find("tr");
        var totalVariantStock = parseFloat(VSTK);
        existingRows.each(function () {
            totalVariantStock += parseFloat($(this).attr('VSTK'));
        });

        if ((EditIndex == 0 && $("tr[typ='" + typ + "']").length == 0) || (EditIndex != 0 && $("tr[Id!='trProductVariant" + EditIndex + "'][typ='" + typ + "']").length == 0)) {

            if (variantType == "0") {
                var productCurrentStock = parseFloat($('#stock').val());
                if (totalVariantStock > productCurrentStock) {
                    Swal.fire({
                        title: "Product stock and variants stocks mismatch!",
                        text: "The sum of variants stock does not match the product current stock.",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                        buttonsStyling: false,
                    });
                    return false;
                }
            }

            if (EditIndex == 0) {
                productVariantData += `<tr id='trProductVariant${RowIndex}' typ="${typ}" VSZ='${VSZ}' VSZT='${VSZT}' VSTK='${VSTK}' VPRC='${VPRC}' VCSPrice='${VCSPrice}' VRSPrice='${VRSPrice}'>`;
                productVariantData += `<td><input type='hidden' class='variantClass' id='tabVariantSize${RowIndex}' name='tabVariantSize[]' value='${VSZ}'><span id='spnVariantSize'>${VSZT || '--'}</span></td>`;
                productVariantData += `<td><input type='hidden' class='variantClass' id='tabVariantStock${RowIndex}' name='tabVariantStock[]' value='${VSTK}'><span id='spnVariantStock'>${VSTK || '--'}</span></td>`;
                productVariantData += `<td><input type='hidden' class='variantClass' id='tabVariantMRPPrice${RowIndex}' name='tabVariantMRPPrice[]' value='${VPRC}'><span id='spnVariantMRPPrice'>${VPRC}</span></td>`;
                productVariantData += `<td><input type='hidden' class='variantClass' id='tabVariantCSPrice${RowIndex}' name='tabVariantCSPrice[]' value='${VCSPrice}'><span id='spnVariantCSPrice'>${VCSPrice}</span></td>`;
                productVariantData += `<td><input type='hidden' class='variantClass' id='tabVariantRSPrice${RowIndex}' name='tabVariantRSPrice[]' value='${VRSPrice}'><span id='spnVariantRSPrice'>${VRSPrice}</span></td>`;
                productVariantData += `<td><a><button type="button" id="btnSave" onclick='doEdit(${RowIndex});' class="btn btn-xs btn-flat btn-success">Edit</button></a><a><button type="button" id="btnSave" onclick='removeRow(${RowIndex});' class="btn btn-xs btn-flat btn-danger">Delete</button></a></td>`;
                productVariantData += "</tr>";

                $("#tbodyVariantList").append(productVariantData);
                $("#ddlVariantType").prop('disabled', true);
                formClear();
                RowIndex++;
            } else {
                var trElement = $(`#trProductVariant${editRowIndex}`);
                //attribute 
                trElement.attr('typ', typ);
                trElement.attr('VSZ', VSZ);
                trElement.attr('VSZT', VSZT);
                trElement.attr('VSTK', VSTK);
                trElement.attr('VPRC', VPRC);
                trElement.attr('VCSPrice', VCSPrice);
                trElement.attr('VRSPrice', VRSPrice);

                //text
                trElement.find(`#spnVariantSize`).text(VSZT);
                trElement.find(`#spnVariantStock`).text(VSTK);
                trElement.find(`#spnVariantMRPPrice`).text(VPRC);
                trElement.find(`#spnVariantCSPrice`).text(VCSPrice);
                trElement.find(`#spnVariantRSPrice`).text(VRSPrice);

                //input value
                trElement.find(`#tabVariantSize${editRowIndex}`).val(VSZ);
                trElement.find(`#tabVariantStock${editRowIndex}`).val(VSTK);
                trElement.find(`#tabVariantMRPPrice${editRowIndex}`).val(VPRC);
                trElement.find(`#tabVariantCSPrice${editRowIndex}`).val(VCSPrice);
                trElement.find(`#tabVariantRSPrice${editRowIndex}`).val(VRSPrice);
                $("#hdProductVariant").val(0);
                formClear();
            }
        } else {
            Swal.fire({
                title: "Variant Already Exists!",
                text: "This variant is already present in the list!",
                icon: "error",
                customClass: {
                    confirmButton: "btn btn-primary",
                },
                buttonsStyling: false,
            });
            $("#ddlVariantType").focus();
        }
    }
}

//Edit List Data
function doEdit(SID) {
    EditIndex = SID;
    $("#editVariant").text("Update");
    $("#hdProductVariant").val(SID);
    // $("#ddlVariantType").val($("#trProductVariant" + SID).attr("VSZ") ? "0" : "1").trigger("change");
    var type = $("#ddlVariantType").val();
    $("#divVariantSize, #divVariantOther").hide();
    if(type == "0"){
        $("#divVariantSize").show();
        $("#ddlVariantSize").val($("#trProductVariant" + SID).attr("VSZ")).trigger("change");
    }else{
        $("#divVariantOther").show();
        $("#ddlVariantotherSize").val($("#trProductVariant" + SID).attr("VSZ"));
    }

    // $("#ddlVariantotherSize").val($("#trProductVariant" + SID).attr("VSZ"));
    // $("#ddlVariantMeter").val($("#trProductVariant" + SID).attr("VMR")).trigger("change");
    $("#numVariantStock").val($("#trProductVariant" + SID).attr("VSTK"));
    $("#numVariantMRPPrice").val($("#trProductVariant" + SID).attr("VPRC"));
    $("#numVariantCSPrice").val($("#trProductVariant" + SID).attr("VCSPrice"));
    $("#numVariantRSPrice").val($("#trProductVariant" + SID).attr("VRSPrice"));
    $("#trProductVariant" + SID).attr("VSTK", 0);
}

//Delete List Data
function removeRow(SID) {
    $("#trProductVariant" + SID).remove();
    formClear();
    if ($("#tbodyVariantList tr").length === 0) {
        $("#ddlVariantType").prop('disabled', false);
    }
}

//Form clear
function formClear() {
    EditIndex = 0;
    $("#editVariant").text("Add");
    // $("#ddlVariantType").val("");
    $("#ddlVariantSize").val("");
    $("#ddlVariantotherSize").val("");
    $("#ddlVariantMeter").val("");
    $("#numVariantStock").val("");
    $("#numVariantMRPPrice").val("");
    $("#numVariantCSPrice").val("");
    $("#numVariantRSPrice").val("");
}

//Validate Variant List
function validateVariant() {
    var variantType = $("#ddlVariantType").val();
    var variantSize = $("#ddlVariantSize").val();
    var variantOtherSize = $("#ddlVariantotherSize").val();
    var variantStock = $("#numVariantStock").val();
    var variantPrice = $("#numVariantMRPPrice").val();
    var variantCSPrice = $("#numVariantCSPrice").val();
    var variantRSPrice = $("#numVariantRSPrice").val();


    if (variantType == "") {
        Swal.fire({
            title: "Select variant type!",
            text: "You have to choose variant type.",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
        });
        $("#ddlVariantType").focus();
        return false;
    }

    if (variantType === "0" && variantSize == "") {
        Swal.fire({
            title: "Select variant size!",
            text: "You have to choose variant size.",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
        });
        $("#ddlVariantSize").focus();
        return false;
    }

    if (variantType === "1" && variantOtherSize == "") {
        Swal.fire({
            title: "Enter variant Size!",
            text: "You have to choose variant meter.",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
        });
        $("#ddlVariantMeter").focus();
        return false;
    }

    if (variantStock == "") {
        Swal.fire({
            title: "Enter variant stock!",
            text: "You have to enter variant stock.",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
        });
        $("#numVariantStock").focus();
        return false;
    }

    if (variantPrice == "" || variantCSPrice == "" ||variantRSPrice == "" ) {
        Swal.fire({
            title: "Enter variant price!",
            text: "You have to enter variant price.",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-primary",
            },
            buttonsStyling: false,
            
        });
        if (variantPrice == "") {
            $("#numVariantMRPPrice").focus();
        } else if (variantCSPrice == "") {
            $("#numVariantCSPrice").focus();
        } else {
            $("#numVariantRSPrice").focus();
        }
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    $("#color").select2();
});

$(document).ready(function () {
    $("#ddlMainCategoryType").on("change", function (){
        var mainCategoryId = $(this).val();
        $.ajax({
            url: '/product/fetch-categories',
            type: 'GET',
            data: { mainCategoryId: mainCategoryId },
            success: function (response) {
                var categories = response.categories;
                var options = '<option value="">Select Category</option>';
                categories.forEach(function (category) {
                    options += '<option value="' + category.id + '">' + category.category_name + '</option>';
                });
                $('#ddlCategoryType').html(options);
            }
        });
    });
    $("#ddlMainCategoryType").on("change", function (){
        var mainCategoryId = $(this).val();
        $.ajax({
            url: '/product/fetch-categories',
            type: 'GET',
            data: { mainCategoryId: mainCategoryId },
            success: function (response) {
                var categories = response.categories;
                var options = '<option value="">Select Category</option>';
                categories.forEach(function (category) {
                    options += '<option value="' + category.id + '">' + category.category_name + '</option>';
                });
                $('#ddlCategoryTypeUp').html(options);
            }
        });
    });
});

function getCategoryCode() {
    $("#ddlCategoryType").on("change", function () {
        var category_id = this.value;
        $("#product_sku").html("");
        $.ajax({
            url: "/product/getproductsku",
            type: "POST",
            data: {
                category_id: category_id,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: function (data) {
                if (data.product_sku == "") {
                    $("#product_sku").html("");
                }
                $("#product_sku").val(data.product_sku);
            },
        });
    });
}

// function validation(event){
//     event.preventDefault();  
//     var category = $('#ddlCategoryType').val();
//     if(category == 0 || category == "0"){
//         const Toast = Swal.mixin({
//             toast: true,
//             position: "top-end",
//             icon: "success",
//             showConfirmButton: false,
//             timer: 3000,
//         });
//         Toast.fire({
//             icon: "error",
//             title: "Pleace select the category",
//         });
       
//         $('#ddlCategoryType').focus();
//         return false;
//     } else {
//         $('#productSubmit').submit(); 
//     }

// }