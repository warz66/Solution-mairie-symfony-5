
$('.btnTrash').click(function(e) {
    var id = $(this).data('id');
    var msgdependencies = $(this).closest('tr').data('msgdependencies');
    if (typeof msgdependencies === "undefined") {
        var msg = "Êtes-vous sûr de vouloir envoyer la galerie <strong>"+$(this).data('title')+"</strong>"+" à la corbeille ?";
    } else {
        var msg = "Attention, vous êtes sur le point d'envoyer à la corbeille du contenu dont certains objets dépendent :<br>"+msgdependencies+"<br><br>Êtes-vous sûr de vouloir continuer ?"
    }
    bootbox.dialog({
        size: 'lg',
        message: msg,
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
                        window.location = '/admin/galerie/'+id+'/trash';
                    }
                }
            }
        }
    });
});

$('.statutCheckbox').click(function(e) {
    var id = $(this).data('id');
    var statut = $(this).prop('checked');
    var msgdependencies = $(this).closest('tr').data('msgdependencies');
    if (statut || typeof msgdependencies === "undefined") {
        $.ajax({
            type:'POST',
            dataType:'JSON',
            url: '/admin/galerie/'+id+'/statut',
            data:'statut='+statut,
            success: function(result) {
                $('#statut'+id).prop('checked',result);  
        }});
    } else {  
        e.preventDefault();
        bootbox.dialog({
            size: 'lg',
            message: "Attention, vous êtes sur le point de dépublier du contenu dont certains objets dépendent :<br>"+msgdependencies+"<br><br>Êtes-vous sûr de vouloir dépublier ce contenu ?",
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
                            $.ajax({
                                type:'POST',
                                dataType:'JSON',
                                url: '/admin/galerie/'+id+'/statut',
                                data:'statut='+statut,
                                success: function(result) {
                                    $('#statut'+id).prop('checked',result);  
                            }}); 
                        }
                    }
                }
            }
        });
    }
});
