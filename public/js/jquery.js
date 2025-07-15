$(document).ready(function () {
    $('#searchInput').on('keyup', function () {
        $('#table-container').load();
    })
})