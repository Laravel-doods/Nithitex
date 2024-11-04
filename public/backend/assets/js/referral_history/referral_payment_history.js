$(document).ready(function () {
    $('#tblReferralPaymentHistory').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[0, "ASC"]],
        ajax: "/settings/referralpaymenthistorydata", fnRowCallback: serialNoCount,
        columns: [
            { data: "id", orderable: false },
            {
                data: "created_at",
                render: function (data, type, row, meta) {
                    var formattedDate = moment(row.created_at).format("DD-MM-YYYY");
                    return formattedDate;
                },
            },
            { data: "name" },
            { data: function (row) { return '₹' + row.amount_paid.toFixed(2); } },
            { data: "transaction_id" },
        ],
    });
});

function serialNoCount(nRow, aData, iDisplayIndex) {
    var api = this.api();
    var currentPage = api.page.info().page;
    var index = currentPage * api.page.info().length + (iDisplayIndex + 1);

    $("td:first", nRow).html(index);

    return nRow;
}