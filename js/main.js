$(function () {
  $(".opsi_radio").click(function () {
    let name = $(this).prop("name");
    let val = parseInt($(this).val());

    $("#input_" + name).prop("disabled", !val);
    if (!val) {
      $("#input_" + name).val("-");
      // $("#input_" + name).slideUp();
      $("#input_" + name).fadeOut();
    } else {
      // $("#input_" + name).slideDown();
      $("#input_" + name).fadeIn();
    }

    console.log(name, val);
  });
});
