/* 
 * Javascript utilisé dans le BO
 */
jQuery(document).ready(function($) {
    /* datepicker des événements */
    $(".ecpdate").datepicker({
        dateFormat: "d MM yy",
        showOn: 'both',
        buttonImage: '/wp-content/themes/fepem-extranet/images/icon-datepicker.png',
        buttonImageOnly: true,
        buttonText: "Calendrier",
        showAnim: "slideDown"
    });
    
    /* vérification des champs requis dans les posts et cpt */
    $('#post').submit(function(e) { 
        
        $('.required').each(function(){
            $(this).prev(".error-field-required").hide();
            if( !$(this).val() || $(this).val() == '0' || $(this).val() == '' ) { 
                //insert error handling here. eg $(this).addClass('error');
                $(this).prev(".error-field-required").show();
                e.preventDefault(); 
            }
        });
    });
});


