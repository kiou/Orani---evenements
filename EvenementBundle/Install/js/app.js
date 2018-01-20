$(function(){

    /* Calendrier */
    $('.calendrierPlus').on('click',function(e){

        e.preventDefault();

        var lien = $(this);
        var mois = parseInt(lien.attr('data-mois')) + 1;
        var annee = parseInt(lien.attr('data-annee'));
        var nav = $('.calendrierNav');

        if(mois > 12){
            mois = 1;
            annee += 1;
        }

        var url = lien.attr('data-url')+'/'+annee+'/'+mois;

        if(!nav.hasClass('current')) {
            nav.prepend('<i class="fa fa-refresh fa-spin"></i>');
            nav.addClass('current');

            $.ajax(url, {
                dataType: "json"
            })
                .done(function (data) {
                    nav.find('.fa-refresh').remove();
                    nav.removeClass('current');

                    /* Calendrier */
                    $('.calendrierNav p').html(data.date);
                    $('.calendrierContent').hide().html(data.contenu).fadeIn('fast');

                    /* Mise à jour des liens de navigation */
                    $('.calendrierPlus').attr('data-mois', mois);
                    $('.calendrierPlus').attr('data-annee', annee);

                    if (mois == 1) {
                        $('.calendrierMoins').attr('data-mois', 1);
                        $('.calendrierMoins').attr('data-annee', annee - 1);
                    } else {
                        $('.calendrierMoins').attr('data-mois', mois);
                        $('.calendrierMoins').attr('data-annee', annee);
                    }

                })
                .fail(function () {
                    alert('Erreur Ajax');
                });

        }

    });

    $('.calendrierMoins').on('click',function(e){

        e.preventDefault();

        var lien = $(this);
        var mois = parseInt(lien.attr('data-mois')) - 1;
        var annee = parseInt(lien.attr('data-annee'));
        var nav = $('.calendrierNav');

        if(mois < 1){
            mois = 12;
            annee -= 1;
        }

        var url = lien.attr('data-url')+'/'+annee+'/'+mois;

        if(!nav.hasClass('current')) {
            nav.prepend('<i class="fa fa-refresh fa-spin"></i>');
            nav.addClass('current');

            $.ajax(url, {
                dataType: "json"
            })
                .done(function (data) {
                    nav.find('.fa-refresh').remove();
                    nav.removeClass('current');

                    /* Calendrier */
                    $('.calendrierNav p').html(data.date);
                    $('.calendrierContent').hide().html(data.contenu).fadeIn('fast');

                    /* Mise à jour des liens de navigation */
                    $('.calendrierMoins').attr('data-mois', mois);
                    $('.calendrierMoins').attr('data-annee', annee);


                    if (mois == 12) {
                        $('.calendrierPlus').attr('data-mois', 1);
                        $('.calendrierPlus').attr('data-annee', annee + 1);
                    } else {
                        $('.calendrierPlus').attr('data-mois', mois);
                        $('.calendrierPlus').attr('data-annee', annee);
                    }

                })
                .fail(function () {
                    alert('Erreur Aajax');
                });

        }

    });

    /* Fermer un popup evenement */
    $(document).on('click','.calendrierEvenement .fa-times',function(){
        $('.calendrierEvenement').removeClass('active');
    });

    /* Popup des événements */
    $(document).on('click', '.calendrierPuce' ,function(){

        var date = $(this);
        var evements = date.next('.calendrierEvenement');

        if(evements.hasClass('active'))
            evements.removeClass('active');
        else{
            $('.calendrierEvenement').removeClass('active');
            evements.addClass('active');

        }
    });

});