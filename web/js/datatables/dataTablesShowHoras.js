$(document).ready(function() {
    var table = $('#table').DataTable({
        columnDefs: [
            { "width": "15%", "targets": 0 }
        ],
        "columns": [
            { "type": "date" },
            { "type": "numeric" },
            { "type": "string"},
            { "type": "string"},
            { "type": "string"},
            { "type": "string"}
        ],
        aaSorting: [],
        sPaginationType: "full_numbers",
        autoFill: true,
        lengthMenu: [[10, 25, 59, -1], [10, 25, 50, "Todo"]],
        colReorder: true,
        dom: 'Blfrtip',
        buttons: [
        {
            extend: 'copy',
            text: 'copiar',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'csv',
            text: 'csv',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'excel',
            text: 'excel',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            text: 'pdf',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'print',
            text: 'imprimir',
            className: 'btn btn-default btn-xs'
        }],
        language: {
            "url": "/Json/Spanish.json"
        }
    });
    // $('a.toggle-vis').on( 'click', function (e) {
    //     console.log('entra');
    //     e.preventDefault();

    //     // Get the column API object
    //     var column = table.column( $(this).attr('data-column') );

    //     // Toggle the visibility
    //     column.visible( ! column.visible() );
    // } );
});

