document.addEventListener("DOMContentLoaded", function () {
    var logo = $("#logo").val();
    $("#tblDeliveredOrder").DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        ajax: "customer-getdelivereddata",
        oLanguage: {sProcessing: "<div class='loader'><img src='" + logo + "' style='width: 60px; height: 50px;'></div>" },
        fnRowCallback: serialNoCount,
        columns: [
            { data: "id", orderable: false },
            {
                data: "created_at",
                render: function (data, type, row) {
                    var formattedDate = moment(row.created_at).format(
                        "DD/MM/YYYY"
                    );
                    return formattedDate;
                },
            },
            {
                data: "order_number",
                render: function (data, type, row) {
                    let html = '<a class="text-dark" href="' + baseUrl + '/order/details/' + row.id + '">' + row.order_number + '</a>';
            
                    if (row.invoice_no) {
                        html += '<div class="text-muted bg-light p-1"><small>' + row.invoice_no + '</small></div>';
                    }
            
                    if (row.invoice) {
                        html += '<a href="' + baseUrl + '/' + row.invoice + '" target="_blank"><i class="fa fa-file"></i></a>';
                    }
            
                    return html;
                },
            },
            {
                data: "name",
                render: function (data, type, row) {
                    let html =
                        " " +
                        row.name +
                        ' <div class="text-muted bg-light p-1"><small>' + (row.phone != null ? row.phone + " " : "") +
                        "</small>";

                    // if (row.alternative_number ) {
                    //     html +=
                    //         " / <small>" + (row.alternative_number != null ? row.alternative_number + ", " : "") + "</small>";
                    // }

                    html += "</div>";
                    html +=
                        '<div class="text-muted bg-light p-1 mt-1"><small>' +
                        (row.city_name != null ? row.city_name + ", " : "") +
                        (row.state_name != null ? row.state_name : "") +
                        "</small></div>";

                    return html;
                },
            },
            { data: "tot_Qty" },
            { data: "sub_total" },
            { data: "coupon_discount" },
            { data: "shipping_charge" },
            { data: "amount" },
            {
                data: "status",
                render: function (data) {
                    return `<label for="">
                    <span class="badge badge-pill badge-warning text-white" style="background: #008000;"> Delivered </span>
                </label>`;
                },
            },
            {
                data: "payment_status",
                render: function (data, type, row) {
                    let orderId = row.id;
                    let paymentStatus = row.payment_status;
                    let html =
                        '<label class="update-order-status" data-order-id="' +
                        orderId +
                        '">';

                    if (paymentStatus === "paid") {
                        html +=
                            '<a class="badge badge-pill badge-success text-white update-status-btn" data-status="unpaid" data-order-id="' +
                            orderId +
                            '">Paid</a>';
                    } else {
                        html +=
                            '<a class="badge badge-pill badge-danger text-white update-status-btn" data-status="paid" data-order-id="' +
                            orderId +
                            '">Unpaid</a>';
                    }

                    html += "</label>";

                    return html;
                },
            },
            { data: "payment_type" },
        ],
        createdRow: function(row, data, dataIndex) {
            var currentPage = this.api().page();
            var rowsPerPage = this.api().page.len();
            var serialNumber = currentPage * rowsPerPage + dataIndex + 1;
            $('td:eq(0)', row).html(serialNumber);
        }
    });
});

// Update Payment Status
$(document).ready(function () {
    $(document).on("click", ".update-status-btn", function (e) {
        e.preventDefault();

        var url;
        var newStatus = $(this).data("status");
        var orderId = $(this).data("order-id");

        if (newStatus === "paid") {
            url = 'paid_status/update/' + orderId;
        } else {
            url = 'unpaid_status/update/' + orderId;
        }

        Swal.fire({
            title: "Confirm Status Change",
            text: "Are you sure, you want to change the status to " + newStatus + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "No, cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        Swal.fire({
                            title: "Status Updated",
                            text: "Payment status updated successfully",
                            icon: "success",
                        }).then(() => {
                            $('#tblDeliveredOrder').DataTable().ajax.reload();
                        });
                    },
                    error: function (error) {
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred while updating the status",
                            icon: "error",
                        });
                    },
                });
            }
        });
    });
});