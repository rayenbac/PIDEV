$(document).ready(function() {
    $('.data-table').DataTable({
        order: [
            [0, "desc"]
        ],
        "responsive": true,
        "language": {
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "lengthMenu": "Afficher _MENU_ éléments",
            "zeroRecords": "Aucun élement correspondant trouvé",
            "info": "Affiche _START_ à  _END_ de _TOTAL_ entrées",
            "infoEmpty": "Aucune donnée disponible dans le tableau",
            "infoFiltered": "(filtré from _MAX_ entrées totales)",
            "sSearch": "Recherche: ",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
        }
    });
    $(document).on('click', '.delete-element', function() {
        var href = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        Swal.fire({
            title: 'étes-vous sur?',
            text: "Vous ne pourrez pas revenir en arrière!",
            type: 'warning',
            cancelButtonClass: 'btn btn-primary',
            confirmButtonClass: 'btn btn-danger',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Non',
            confirmButtonText: 'Oui',
        }).then((result) => {
            if (result.value) {
                //console.log(href + '/' + id)
                //location.href = href + '/' + id;
                location.href = href;
            }
        })
    });

    $('.select2').select2();

    $(".loader").fadeOut("slow");





    $('.datepicker-input').datepicker({
        locale: 'fr',
        format: 'yyyy-mm-dd',
    });



    $('.date-time-picker').datetimepicker({
        locale: 'fr',
        format: "YYYY-MM-DD HH:mm",
    });


    $('.tagsinput').tagsinput({
        typeahead: {
            source: [],
            afterSelect: function() {
                this.$element[0].value = '';
            }
        }
    });

});