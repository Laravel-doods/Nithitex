$(document).ready(function () {
    const logo = $("#logo").val();
    const baseUrl = window.location.origin; // Assuming baseUrl is set dynamically here

    $("#tblViewAnalytics").DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "asc"]],
        ajax: {
            url: "/report/get-view-analytics",
            type: "GET",
        },
        oLanguage: {
            sProcessing: `<div class='loader'><img src='${logo}' style='width: 60px; height: 50px;'></div>`
        },
        fnRowCallback: serialNoCount,
        columns: [
            { data: null, orderable: false }, // For serial numbers
            { data: "product_name" },
            {
                data: "product_image",
                render: function (data, type, row) {
                    return `<img src="${baseUrl}/${row.product_image}" style="width: 60px; height: 50px;" />`;
                },
            },
            { data: "product_sku" },
            { data: "view_count" },
            { data: "current_stock" },
        ],
        createdRow: function (row, data, dataIndex) {
            let currentPage = this.api().page();
            let rowsPerPage = this.api().page.len();
            let serialNumber = currentPage * rowsPerPage + dataIndex + 1;
            $('td:eq(0)', row).html(serialNumber);
        },
    });
});
