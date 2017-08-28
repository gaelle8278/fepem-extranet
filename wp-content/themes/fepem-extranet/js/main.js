jQuery(document).ready( function($) {
    // Popup persos
    //////////////////////////
    //popup d'infos statique
    var popup = (function() {

        function initPopup() {
            var overlay = $('.overlay');
            $('.popup-button').each(function(i, el) {
                var modal = $('#' + $(el).attr('data-modal'));

                // fonction qui enleve la class .show de la popup et la fait disparaitre
                function removeModal() {
                    modal.removeClass('popup-show');
                }

                // evenement qui appelle la fonction removeModal()
                function removeModalHandler() {
                    removeModal();
                }

                // au clic sur le bouton on ajoute la class .show a la div de la popup qui permet au CSS3 de prendre le relai
                $(el).click(function(event) {
                    event.preventDefault();
                    modal.addClass('popup-show');
                    overlay.unbind("click");
                    // on ajoute sur l'overlay la fonction qui permet de fermer la popup
                    overlay.bind("click", removeModalHandler);
                });

                // en cliquant sur le bouton close on ferme tout et on arrête les fonctions
                $('.popup-close').click(function(event) {
                    event.stopPropagation();
                    removeModalHandler();
                });

            });

        }
        initPopup();
    })();
    
    // Popup d'infos dynamique 
    $(".custom-popup .close_button").click(function(event) {
          event.preventDefault();
          var popup = $(this).parents('.custom-popup');
          hidePopup(popup);
    });
    $(".custom-popup .ok_button").click(function(event) {
          event.preventDefault();
          var popup = $(this).parents('.custom-popup');
          hidePopup(popup);
    });
    function showPopup(popup) {
         $(popup).before('<div id="gray-overlay"></div>');
         $("#gray-overlay ").css('opacity', 0).fadeTo(300, 0.5, function () { $(popup).fadeIn(500); });
    }
    function hidePopup(popup) {
        // on fait disparaitre le gris de fond rapidement
        $("#gray-overlay").fadeOut('fast', function () { $(this).remove() });
        // on fait disparaitre le popup à la même vitesse
        $(popup).fadeOut('fast', function () { $(this).hide() });
    }
        // appel de la popup
        // détail d'une annonce
    $("a.custom_popup-button").click(function(event){
        event.preventDefault();
        var popup=$(this).next('.custom-popup');
        showPopup(popup);
    });
    
    
    // onglets
    ///////////////////
    $( "#tdb-tabs" ).tabs();
    
    
    

});

