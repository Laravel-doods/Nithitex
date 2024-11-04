$(document).ready(function () {
    $('#tblReferralHistory').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[0, "ASC"]],
        ajax: "/settings/refferalcustomer", fnRowCallback: serialNoCount,
        columns: [
            { data: "name", orderable: false },
            { data: "name" },
            { data: "referral_code" },
            { data: "user_id_count" },
            { data: function (row) { return '₹' + row.referral_points.toFixed(2); } },
            // { data: function (row) { return '₹' + row.referral_paid.toFixed(2); } },
            // {
            //     data: function (row) {
            //         var balance = row.referral_points - row.referral_paid;
            //         return '₹' + balance.toFixed(2);
            //     }
            // },
            { data: "action", orderable: false },
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

// show Bank Info Popup
function updateReferralPaymentPopup(id) {
    $('#hdUserId').val(id);
    $.ajax({
        url: '/settings/referraluserinfo/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var modalContent = '';
            var balance = response.referral_user_info.referral_points - response.referral_user_info.referral_paid;
            modalContent += '<tr>';
            modalContent += '<td>' + response.referral_user_info.name + '</td>';
            modalContent += '<td>' + '₹' + balance.toFixed(0); + '</td>';
            modalContent += '<td><input type="number" name="paidAmount" placeholder="Enter Amount" class="form-control" min="0" required></td>';
            modalContent += '<td><input type="text" name="txtTransactionId" placeholder="Enter Transaction ID" class="form-control" required></td>';
            modalContent += '</tr>';

            $('#tblReferralpayment tbody').html(modalContent);
            $('#updateReferralPaymentPopup').modal('show');
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}