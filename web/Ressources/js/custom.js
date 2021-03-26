$(document).ready(function () {

    var date = new Date();
    var currentDate = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();

    $("#checkboxDescription").click(function () {
        if ($('input[name=checkboxDescription]').is(':checked')) {
            $('.date').before('<input name="description" placeholder="Description" id="description" type="text"   class="boutton-recherche col-sm-12 inputdate form-control">');
        }

        var refreshId = setTimeout(function () {
            if (!$('input[name=checkboxDescription]').is(':checked')) {
                $('.inputdate').remove();
            }
        }, 50);
        $.ajaxSetup(
            {
                cache: false
            });

    });

    $("#checkboxDateDepart").click(function () {
        if ($('input[name=checkboxDateDepart]').is(':checked')) {
            $('.date').before('<label class="inputdate">Date de départ </label>');
            $('.date').before('<input name="dateDepart" placeholder="Date de départ" type="date" min=' + currentDate + '  class="col-sm-12 boutton-recherche inputdate form-control">');
        }

        var refreshId = setTimeout(function () {
            if (!$('input[name=checkboxDateDepart]').is(':checked')) {
                $('.inputdate').remove();
            }
        }, 50);
        $.ajaxSetup(
            {
                cache: false
            });

    });

    var refreshId = setTimeout(function () {

        $('#FlashMessageSuccess').fadeOut('fast');

    }, 5000);

    $.ajaxSetup({

        cache: false
    });

    var refreshId = setTimeout(function () {

        $('#FlashMessageDanger').fadeOut('fast');

    }, 5000);

    $.ajaxSetup({

        cache: false
    });

    $("#buttonSearchInResult").click(function () {
        $(".searchInResult").show();
    });

    //jeu de champs pour modification et affichage du nom

    $("#editNom").click(function () {
        $("#nom").show();
        $('.viewNom').hide();

        $("#prenom").hide();
        $('.viewPrenom').show();

        $("#username").hide();
        $('.viewusername').show();

    });

    $("#AnnulerNom").click(function () {
        $("#nom").hide();
        $('.viewNom').show();
    });

    //jeu de champs pour modification et affichage du username


    $("#editUsername").click(function () {
        $("#username").show();
        $('.viewusername').hide();

        $("#prenom").hide();
        $('.viewPrenom').show();

        $("#nom").hide();
        $('.viewNom').show();


    });

    $("#AnnulerUsername").click(function () {
        $("#username").hide();
        $('.viewusername').show();
    });

    //jeu de champs pour modification et affichage du prenom


    $("#editPrenom").click(function () {
        $("#prenom").show();
        $('.viewPrenom').hide();


        $("#nom").hide();
        $('.viewNom').show();

        $("#username").hide();
        $('.viewusername').show();

    });


    $("#AnnulerPrenom").click(function () {
        $("#prenom").hide();
        $('.viewPrenom').show();
    });



    //jeu de champs pour modification et affichage de la modification de la politique de confidentialité

    $("#editPolicy").click(function () {
        $("#policy").show();
        $('.viewPolicy').hide();

    });

    $("#AnnulerPolicy").click(function () {
        $("#policy").hide();
        $('.viewPolicy').show();
    });

    //jeu de champs pour l'affichage et la masquage du plan d'abonnement

    $("#selectAbonnement").click(function () {
        $('#formAbonnement').show();

    });

    $("#AnnulerformAbonnement").click(function () {
        $("#formAbonnement").hide();
    });


    $("#Pay").click(function () {
        $(this).attr('disable', true);
    });


    $("#buttonViewPlan").click(function () {

        $("#parametre").modal('hide');

        setTimeout(function () { 
        $(".statutAbonnement").hide();
        $(".plan").show();
        }, 2000)
        



    });

    $(".closePlan").click(function () {

        setTimeout(function () { 
            $(".statutAbonnement").show();
            $(".plan").hide();
            }, 2000)
       
    });


    $('#endplan').click( function(){

    $('#parametre').modal('hide');

});


$('#aaa').click( function(){

   alert('fff');
});


});
