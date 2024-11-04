//Seral No Count
function serialNoCount(nRow, aData, iDisplayIndex) {
    var api = this.api();
    var currentPage = api.page.info().page;
    var index = currentPage * api.page.info().length + (iDisplayIndex + 1);

    $("td:first", nRow).html(index);

    return nRow;
}

var baseUrl = window.location.origin;

// 