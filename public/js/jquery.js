$(document).ready(function () {
  $("#searchInputCategory").on("keyup", function () {
    $("#table-body").load(
      "ajax/liveSearch.php?keyword=" + $("#searchInputCategory").val()
    );
  });
});
