
$('.btnDelete').click(function(e) {
    var id = $(this).data('id');
    bootbox.dialog({
        message: "Êtes-vous sûr de vouloir supprimer la galerie <strong>"+$(this).data('title')+"</strong>",
        buttons: {
            annuler: {
                label: 'Annuler',
                className: 'btn-secondary',
            },
            valider: {
                label: 'Valider',
                className: 'btn-danger',
                callback: function(result){
                    if (result) {
                        window.location = '/admin/galerie/'+id+'/delete';
                    }
                }
            }
        }
    });
});

$(".statutCheckbox").click(function(e) {
    e.preventDefault(); // à tester
    var id = $(this).data('id');
    var statut = $(this).prop('checked');
    $.ajax({
        type:'POST',
        dataType:'JSON',
        url: '/admin/galerie/'+id+'/statut',
        data:'statut='+statut,
        success: function(result) {
            $('#statut'+id).prop('checked',result);  
    }});
});
