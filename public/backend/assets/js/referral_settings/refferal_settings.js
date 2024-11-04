$(document).ready(function () {
    //image tag hide
    if ($("#previewImage1").attr("src") === "") {
        $("#previewImage1").hide();
    }
    $("#fileReferral_banner").on("change", function () {
        $("#previewImage1").show();
    });
});

// jquery Validation
$(function () {
    $("form[name='referral_settings']").validate({
        rules: {
            txtpointsforEachcoin: "required",
            txtpointsForeachreferral: "required",
        },
        messages: {
            required: "This field is required",
        },
    });
});

function cancel() {
    $("#referral_content").val("");
    $("#earnpoints_per_referral").val("");
    $("#referral_content").focus();
}

//Image Preview
const inputFiles = document.querySelectorAll('input[type="file"]');
const previewImages = document.querySelectorAll('img[id^="previewImage"]');

inputFiles.forEach(function (inputFile, index) {
    inputFile.addEventListener("change", function () {
        const file = this.files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            previewImages[index].setAttribute("src", this.result);
        });

        if (file) {
            reader.readAsDataURL(file);
        }
    });
});
