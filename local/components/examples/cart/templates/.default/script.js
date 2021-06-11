$(document).ready(function () {
    $('body').on('change', 'input[type="number"]', function () {
        var quantity = $(this).val(),
            id = $(this).parents('tr').attr('id'),
            type = 'quantity';
        $.ajax({
            type: "POST",
            data: { 'id': id, 'type': type, 'quantity': quantity },
            success: function (html) {
                $('#table').html(html);
            }
        });
    });


    $('body').on('click', '.delete', function () {
        console.log('del');
        var id = $(this).parents('tr').attr('id'),
            type = 'delete';
        $.ajax({
            type: "POST",
            data: { 'id': id, 'type': type },
            success: function (html) {
                $('#table').html(html);
            }
        });
    });
});