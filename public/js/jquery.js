$(document).ready(function () {
  $("#searchInputCategory").on("keyup", function () {
    $("#table-container").load(
      "../ajax/liveSearch.php?keyword=" + $("#searchInputCategory").val()
    );
  });
});
