$(document).ready(function () {
    var logo = $("#logo").val();
    $("#tblProductList").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ordering: false,
        ajax: {
            url: 'productlistdata',
            data: function (d) {
                d.page = (d.start / d.length) + 1; // Get the current page
            },
            dataSrc: function (json) {
                // Update pagination information
                json.recordsTotal = json.recordsTotal;
                json.recordsFiltered = json.recordsFiltered;
                return json.data;
            }
        },
        oLanguage: {
            sProcessing:
                "<img src='" + logo + "' style='width: 60px; height: 50px;'>",
        },
        fnRowCallback: serialNoCount,
        columns: [
            { data: null },
            {
                data: "product_image",
                render: function (data, type, row, meta) {
                    if (row.product_image) {
                        const imageUrl = row.product_image.startsWith("http")
                            ? row.product_image
                            : baseUrl + "/" + row.product_image;
                        return (
                            '<img src="' + imageUrl + '" style="width: 60px; height: 50px;">'
                        );
                    } else {
                        return (
                            '<button class="btn btn-success btn-sm" onclick="showAddImagePopup(' +
                            row.id +
                            ',' +
                            row.product_sku +
                            ')">Add Image</button>'
                        );
                    }
                },
            },
            {
                data: "product_name",
                render: function (data, type, row) {
                    let colorDisplay = row.color && row.color.color_name ? `(${row.color.color_name})` : '';
                    return `
                        ${row.product_name} ${colorDisplay}
                        <div class="text-muted bg-light p-1"><small>${row.main_category.main_category_name} / ${row.category.category_name}</small></div>
                    `;
                },
            },
            { data: "product_sku" },
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
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        ${roundDiscount(
                        row.product_discount,
                        row.product_price
                    )}
                        /
                        ${roundDiscount(row.seller_discount, row.seller_price)}
                    `;
                },
            },
            { data: "current_stock" },
            { data: "action" },
        ],
        createdRow: function (row, data, dataIndex) {
            var currentPage = this.api().page();
            var rowsPerPage = this.api().page.len();
            var serialNumber = currentPage * rowsPerPage + dataIndex + 1;
            $("td:eq(0)", row).html(serialNumber);
        },
        // pageLength: 10 // Set the number of items per page
    });
});

function showAddImagePopup(productId, productSKU) {
    document.getElementById("hdProductId").value = productId;
    document.getElementById("product_sku").value = productSKU;

    $("#addImageModal").modal("show");
}

function roundDiscount(discount, price) {
    if (discount === null) {
        return '<span class="badge badge-pill badge-danger">No Discount</span>';
    } else {
        let amount = price - discount;
        let calculatedDiscount = (amount / price) * 100;
        return `<span class="badge badge-pill badge-warning">${Math.round(
            calculatedDiscount
        )}%</span>`;
    }
}
