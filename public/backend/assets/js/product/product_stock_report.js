var table = "";

$(document).ready(function () {
    $("#category_id").change(function () {
        table.destroy();
        listproductstockreport();
        table.ajax.reload();
    });

    $("#ddType").change(function () {
        table.destroy();
        listproductstockreport();
        table.ajax.reload();
    });
    listproductstockreport();
});

function listproductstockreport() {
    var category_id = $("#category_id").val();
    var type = $("#ddType").val();
    var logo = $("#logo").val();
    table = $("#dataTablestock").DataTable({
        processing: true,
        serverSide: true,
        order: [
            [0, "asc"]
        ],
        ajax: {
            url: "/product/stock/report/" + (category_id ? category_id : 0),
            data: { type: type }, 
        },
        oLanguage: {sProcessing: "<div class='loader'><img src='" + logo + "' style='width: 60px; height: 50px;'></div>" },
        fnRowCallback: serialNoCount,
        columns: [
            { data: null, orderable: false },
            
            {
                data: "product_name",
                render: function(data, type, row) {
                    let size = row.size ? `size: ${row.size}` : '';
                    let html = `
                        ${row.product_name}
                        ${size != '' ? `<div class="text-muted bg-light p-1"><small>${size}</small></div>` : ''}
                    `;
                    return html;
                },
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
                data: "product_sku"
                
            },
            { 
                data: "product_price",
                render: function(data, type, row){
                    var price = Math.round(row.product_price)
                    return price;
                }
            },
            { 
                data: "product_discount",
                render: function(data, type, row){
                    var price = Math.round(row.product_discount)
                    return price;
                }
            },
            { 
                data: "seller_discount",
                render: function(data, type, row){
                    var price = Math.round(row.seller_discount)
                    return price;
                }
            },
            {
                data: "current_stock",
            },
            
        ],
        createdRow: function(row, data, dataIndex) {
            var currentPage = this.api().page();
            var rowsPerPage = this.api().page.len();
            var serialNumber = currentPage * rowsPerPage + dataIndex + 1;
            $('td:eq(0)', row).html(serialNumber);
        }
    });
}
