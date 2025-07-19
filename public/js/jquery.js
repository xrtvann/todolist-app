$(document).ready(function () {
  $("[data-search='livesearch']").on("keyup", function () {
    const page = $(this).data("page");
    const keyword = $(this).val();
    $("#table-body").load(
      "ajax/liveSearch.php?page=" +
        encodeURIComponent(page) +
        "&keyword=" +
        encodeURIComponent(keyword)
    );
  });
});
