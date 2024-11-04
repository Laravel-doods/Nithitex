document.addEventListener("DOMContentLoaded", function () {
    var logo = $("#logo").val();
    $("#tblAllCustomer").DataTable({
        processing: true,
        serverSide: true,
        ajax: "all-getallcustomerordersdata",
        ordering: false,
        oLanguage: {sProcessing: "<div class='loader'><img src='" + logo + "' style='width: 60px; height: 50px;'></div>" },
        fnRowCallback: serialNoCount,
        columns: [
            { 
                data: null,
            },
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
                        html +=
                            '<div class="text-muted bg-light p-1"><small>' +
                            row.invoice_no +
                            "</small></div>";
                    }

                   
                    return html;
                },
            },
            {
                data: "name",
                render: function (data, type, row) {
                    let html = row.name;
                    if(row.alternative_number){
                        html =
                        " " +
                        row.name +
                        ' <div class="text-muted bg-light p-1"><small>' + (row.alternative_number != null ? row.alternative_number + " " : "") +
                        "</small>";
                    }
                     

                    // if (row.alternative_number) {
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
            { 
                data: "phone",
                // render: function (data, type, row) {
                //     let html =
                //         " " +
                //         row.name +
                //         ' <div class="text-muted bg-light p-1"><small>' + (row.phone != null ? row.phone + " " : "") +
                //         "</small>";

                //     if (row.alternative_number) {
                //         html +=
                //             " / <small>" + (row.alternative_number != null ? row.alternative_number + ", " : "") + "</small>";
                //     }

                //     html += "</div>";
                //     html +=
                //         '<div class="text-muted bg-light p-1 mt-1"><small>' +
                //         (row.city_name != null ? row.city_name + ", " : "") +
                //         (row.state_name != null ? row.state_name : "") +
                //         "</small></div>";

                //     return html;
                // },
            },
            { data: "tot_Qty" },
            { data: "sub_total" },
            { data: "coupon_discount" },
            { data: "shipping_charge" },
            { data: "amount" },
            {
                data: "status",
                render: function (data, type, row) {
                    let statusBadge = "";

                    switch (row.status) {
                        case "pending":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #800080;">Pending</span>';
                            break;
                        case "confirmed":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #0000FF;">Confirm</span>';
                            break;
                        case "processing":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #FFA500;">Processing</span>';
                            break;
                        case "picked":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #808000;">Picked</span>';
                            break;
                        case "shipped":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #808080;">Shipped</span>';
                            break;
                        case "delivered":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #008000;">Delivered</span>';
                            break;
                        case "cancelled":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #80000b;">Cancelled</span>';
                            break;
                        case "returned":
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white" style="background: #80000b;">Returned</span>';
                            break;
                        default:
                            statusBadge =
                                '<span class="badge badge-pill badge-warning text-white">Unknown</span>';
                            break;
                    }

                    let html = '<label for="">' + statusBadge + "</label>";

                    if (row.track_number) {
                        html +=
                            '<div class="text-muted bg-light p-1 mb-1"><small>' +
                            row.track_number +
                            "</small></div>";
                    }

                    return html;
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
            { data: "action" },
        ],
        
    });
});


// Update Payment Status

    $(document).on("click", ".update-status-btn", function (e) {
        e.preventDefault();

        var url;
        var newStatus = $(this).data("status");
        var orderId = $(this).data("order-id");

        if (newStatus === "paid") {
            url = "paid_status/update/" + orderId;
        } else {
            url = "unpaid_status/update/" + orderId;
        }

        Swal.fire({
            title: "Confirm Status Change",
            text:
                "Are you sure, you want to change the status to " +
                newStatus +
                "?",
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
                            $("#tblAllCustomer").DataTable().ajax.reload();
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

