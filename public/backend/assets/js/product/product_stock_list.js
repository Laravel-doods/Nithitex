var table = "";
$(document).ready(function () {
    $("#category_id").change(function () {
        table.destroy();
        listproductstockreport();
        table.ajax.reload();
    });
    listproductstockreport();
});

function listproductstockreport() {
    var logo = $("#logo").val();
    var category_id = $("#category_id").val();
    table = $("#tblProductMaintenanceList").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "asc"]],
        ajax: "/product/getStockMaintenaceData/" + category_id,
        oLanguage: {
            sProcessing:
                "<div class='loader'><img src='" +
                logo +
                "' style='width: 60px; height: 50px;'></div>",
        },
        fnRowCallback: serialNoCount,
        columns: [
            {
                data: null,
            },
            {
                data: "product_image",
                render: function (data, type, row) {
                    return (
                        '<img src="' +
                        baseUrl +
                        "/" +
                        row.product_image +
                        '" style="width: 60px; height: 50px;"/>'
                    );
                },
            },
            {
                data: "product_name",
            },
            {
                data: "product_sku",
            },
            {
                data: "product_price",
                render: function (data) {
                    return parseFloat(data).toFixed(0);
                },
            },
            {
                data: "product_discount",
                render: function (data) {
                    return parseFloat(data).toFixed(0);
                },
            },
            {
                data: "seller_discount",
                render: function (data) {
                    return parseFloat(data).toFixed(0);
                },
            },
            // {
            //     data: "current_stock",
            //     render: function (data, type, row) {
            //         return `
            //         <input type="text"  name="current_qty" id="current_qty_${row.id}" class="form-control" maxlength="50" title="Please enter Quantity" value=" ${row.current_stock}">
            //         `;
            //     },
            //     type: 'num',
            //     searchable: true
            // },
            { data: "action" },
        ],
        createdRow: function (row, data, dataIndex) {
            var currentPage = this.api().page();
            var rowsPerPage = this.api().page.len();
            var serialNumber = currentPage * rowsPerPage + dataIndex + 1;
            $("td:eq(0)", row).html(serialNumber);
        },
    });
}

function stockUpdatePopup(productId) {
    document.getElementById("hdProductId").value = productId;

    $.ajax({
        url: "/product/getProductVariantStock",
        type: "GET",
        data: { productId: productId },
        success: function (response) {
            $("#stock").val(response.product.current_stock);

            if (response.variants.length > 0) {
                $("#divVariantSize").empty();
                $("#divVariantStock").empty();

                var isFirstSize = true;
                var isFirstStock = true;

                response.variants.forEach(function (variant) {
                    // Append label only for the first variant
                    if (isFirstSize) {
                        $("#divVariantSize").append("<label>Size</label>");
                        isFirstSize = false; // Set to false after the first variant
                    } else {
                        // For subsequent variants, add an empty space to maintain alignment
                        $("#divVariantSize").append("<label>&nbsp;</label>");
                    }
                    if (isFirstStock) {
                        $("#divVariantStock").append("<label>Stock</label>");
                        isFirstStock = false; // Set to false after the first variant
                    } else {
                        // For subsequent variants, add an empty space to maintain alignment
                        $("#divVariantStock").append("<label>&nbsp;</label>");
                    }

                    // Append input field for size
                    $("#divVariantSize").append(
                        '<input type="text" control="int" class="form-control" autocomplete="off" ' +
                            'value="' +
                            variant.size +
                            '" disabled />'
                    );

                    // Append input field for stock
                    $("#divVariantStock").append(
                        '<input type="number" min="0" class="form-control" name="ddlVariantStock" autocomplete="off" ' +
                            'value="' +
                            variant.stock +
                            '" />'
                    );
                });
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        },
    });

    $("#stockUpdateModal").modal("show");
}

$(document).ready(function () {
    $("#product").submit(function (e) {
        e.preventDefault();

        var stock = parseFloat($("#stock").val());
        var totalVariantStock = 0;

        $('input[name="ddlVariantStock"]').each(function () {
            var variantStock = parseFloat($(this).val());
            if (!isNaN(variantStock)) {
                totalVariantStock += variantStock;
            }
        });

        if (isNaN(stock) || isNaN(totalVariantStock)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Please enter valid stock values.",
            });
            return;
        }

        if (totalVariantStock > stock) {
            Swal.fire({
                icon: "error",
                title: "Stock Validation",
                text: "The sum of variants stock does not match the product current stock.",
            });
            return;
        }

        var formData = new FormData(this); // Create a FormData object

        // Initialize an array to store all ddlVariantStock values
        var variantStockValues = [];

        // Iterate over inputs with name ddlVariantStock and push their values to the array
        $('input[name="ddlVariantStock"]').each(function () {
            variantStockValues.push($(this).val());
        });

        // Append the array to the FormData object
        formData.append("ddlVariantStock", JSON.stringify(variantStockValues));

        $.ajax({
            url: "/product/update-variant-stock",
            type: "POST",
            data: formData, // Use FormData object instead of serialized data
            contentType: false, // Set contentType to false when using FormData
            processData: false, // Set processData to false when using FormData
            success: function (response) {
                $("#stockUpdateModal").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.success,
                }).then((result) => {
                    $("#tblProductMaintenanceList").DataTable().ajax.reload();
                });
            },
            error: function (xhr) {
                // Handle error response
                var errors = xhr.responseJSON;
                if (errors && errors.error) {
                    alert(errors.error);
                } else {
                    alert("An error occurred while updating stock.");
                }
            },
        });
    });
});

function updateProductQuantity(id) {
    var current_qty = $("#current_qty_" + id).val();
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    if (current_qty === "") {
        swal.fire(
            "Error",
            "Please enter product quantity before updating!",
            "error"
        );
        return;
    }
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update the product stock?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, change it!",
        cancelButtonText: "No, cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "/product/quantity/update",
                data: {
                    _token: csrfToken,
                    product_id: id,
                    current_qty: current_qty,
                },
                success: function (data) {
                    Swal.fire({
                        title: "Updated!",
                        text: "Product Stock has been updated.",
                        icon: "success",
                    }).then(() => {
                        $("#tblProductMaintenanceList")
                            .DataTable()
                            .ajax.reload();
                    });
                },
                error: function (data) {
                    Swal.fire(
                        "Error!",
                        "An error occurred while updating the product stock.",
                        "error"
                    );
                },
            });
        }
    });
}
