<?php
function get_months_en() {
    $monthsfrtoen=[];
    $monthsfrtoen['Janvier']='January';
    $monthsfrtoen['Février']='February';
    $monthsfrtoen['Mars']='March';
    $monthsfrtoen['Avril']='April';
    $monthsfrtoen['Mai']='May';
    $monthsfrtoen['Juin']='June';
    $monthsfrtoen['Juillet']='July';
    $monthsfrtoen['Août']='August';
    $monthsfrtoen['Septembre']='September';
    $monthsfrtoen['Octobre']='October';
    $monthsfrtoen['Novembre']='November';
    $monthsfrtoen['Décembre']='December';

    return $monthsfrtoen;
}

function get_months_nb() {
    $monthsfrtonb=[];
    $monthsfrtonb['Janvier']=1;
    $monthsfrtonb['Février']=2;
    $monthsfrtonb['Mars']=3;
    $monthsfrtonb['Avril']=4;
    $monthsfrtonb['Mai']=5;
    $monthsfrtonb['Juin']=6;
    $monthsfrtonb['Juillet']=7;
    $monthsfrtonb['Août']=8;
    $monthsfrtonb['Septembre']=9;
    $monthsfrtonb['Octobre']=10;
    $monthsfrtonb['Novembre']=11;
    $monthsfrtonb['Décembre']=12;
    
    return $monthsfrtonb;
}

//CPT used in application
function get_cpt_instances() {
    $list_cpt_instances=['commission', 'fcommission','oscommission','cpcommission'];

    return $list_cpt_instances;
}

function get_cpt_messagerie() {
    $list_cpt_messagerie=['ecp_messagerie', 'ecp_fmessagerie','ecp_osmessagerie','ecp_cpmessagerie'];

    return $list_cpt_messagerie;
}

function get_cpt_message() {
    $list_cpt_message=['ecp_message', 'ecp_fmessage','ecp_osmessage','ecp_cpmessage'];

    return $list_cpt_message;
}

function get_cpt_calendrier() {
    $list_cpt_calendrier=['ecp_calendrier', 'ecp_fcalendrier','ecp_oscalendrier','ecp_cpcalendrier'];

    return $list_cpt_calendrier;
}

function get_cpt_event() {
    $list_cpt_event=['ecp_event', 'ecp_fevent','ecp_osevent','ecp_cpevent'];

    return $list_cpt_event;
}

function get_cpt_ged() {
    $list_cpt_ged=['ecp_ged', 'ecp_fged','ecp_osged','ecp_cpged'];

    return $list_cpt_ged;
}

function get_cpt_document() {
    $list_cpt_document=['ecp_document', 'ecp_fdocument','ecp_osdocument','ecp_cpdocument'];

    return $list_cpt_document;
}

function get_cpt_composants() {
    $list_cpt_messagerie=get_cpt_messagerie();
    $list_cpt_message=get_cpt_message();
    $list_cpt_calendrier=get_cpt_calendrier();
    $list_cpt_event=get_cpt_event();
    $list_cpt_ged=get_cpt_ged();
    $list_cpt_document=get_cpt_document();

    $list_cpt_composants=array_merge($list_cpt_messagerie,$list_cpt_message,$list_cpt_calendrier,$list_cpt_event,$list_cpt_ged,$list_cpt_document);

    return $list_cpt_composants;

}

function get_cpt() {
    $list_cpt_instances=get_cpt_instances();
    $list_cpt_composants=get_cpt_composants();

    $list_cpt=array_merge($list_cpt_instances,$list_cpt_composants);

    return $list_cpt;
}

function get_cpt_tax_type_public() {
    $list_cpt_instances=get_cpt_instances();
    $list_cpt_messagerie=get_cpt_messagerie();
    $list_cpt_ged=get_cpt_ged();;

    $list_cpt_tax_type_public=array_merge($list_cpt_instances,$list_cpt_messagerie,$list_cpt_ged);

    return $list_cpt_tax_type_public;
}

function get_cpt_notifications() {
    $list_cpt_message=get_cpt_message();
    $list_cpt_event=get_cpt_event();
    $list_cpt_document=get_cpt_document();

    $list_cpt_notifications=array_merge($list_cpt_message,$list_cpt_event,$list_cpt_document);

    return $list_cpt_notifications;
}

//array usefull to build admin menu
/////////////////////////////////////////
function get_cpt_calendriers_menu() {
    $list_cpt_calendriers_menu=[
        'ecp_calendrier' => 'ecp_event',
        'ecp_fcalendrier' => 'ecp_fevent',
        'ecp_oscalendrier' => 'ecp_osevent',
        'ecp_cpcalendrier' => 'ecp_cpevent'
    ];
    
    return $list_cpt_calendriers_menu;
}

function get_cpt_messageries_menu() {
    $list_cpt_messageries_menu=[
        'ecp_messagerie' => 'ecp_message',
        'ecp_fmessagerie' => 'ecp_fmessage',
        'ecp_osmessagerie' => 'ecp_osmessage',
        'ecp_cpmessagerie' => 'ecp_cpmessage'
    ];
    return $list_cpt_messageries_menu;
}

function get_cpt_geds_menu() {
    $list_cpt_geds_menu=[
        'ecp_ged' => 'ecp_document',
        'ecp_fged' => 'ecp_fdocument',
        'ecp_osged' => 'ecp_osdocument',
        'ecp_cpged' => 'ecp_cpdocument'
    ];
    
    return $list_cpt_geds_menu;
}

//array usefull to build metaboxes
//////////////////////////////////////:
function get_cpt_calendrier_of_instance() {
    $list_cpt_calendrier_of_instance = [
        'commission' => 'ecp_calendrier',
        'fcommission' => 'ecp_fcalendrier',
        'oscommission' => 'ecp_oscalendrier',
        'cpcommission' => 'ecp_cpcalendrier'
    ];
    
    return $list_cpt_calendrier_of_instance;
}

function get_cpt_messagerie_of_instance() {
    $list_cpt_messagerie_of_instance = [
        'commission' => 'ecp_messagerie',
        'fcommission' => 'ecp_fmessagerie',
        'oscommission' => 'ecp_osmessagerie',
        'cpcommission' => 'ecp_cpmessagerie'
    ];
    
    return $list_cpt_messagerie_of_instance;

}

function get_cpt_ged_of_instance() {
    $list_cpt_ged_of_instance = [
        'commission' => 'ecp_ged',
        'fcommission' => 'ecp_fged',
        'oscommission' => 'ecp_osged',
        'cpcommission' => 'ecp_cpged'
    ];
    
    return $list_cpt_ged_of_instance;
}

function get_cpt_calendrier_of_event() {
    $list_cpt_calendrier_of_event =  [
        'ecp_event' => 'ecp_calendrier',
        'ecp_fevent' => 'ecp_fcalendrier',
        'ecp_osevent' => 'ecp_oscalendrier',
        'ecp_cpevent' => 'ecp_cpcalendrier'
    ];
    
    return $list_cpt_calendrier_of_event;
}

function get_cpt_messagerie_of_message() {
    $list_cpt_messagerie_of_message =  [
        'ecp_message' => 'ecp_messagerie',
        'ecp_fmessage' => 'ecp_fmessagerie',
        'ecp_osmessage' => 'ecp_osmessagerie',
        'ecp_cpmessage' => 'ecp_cpmessagerie'
    ];
    return $list_cpt_messagerie_of_message;
}

function get_cpt_ged_of_document() {
    $list_cpt_ged_of_document =  [
        'ecp_document' => 'ecp_ged',
        'ecp_fdocument' => 'ecp_fged',
        'ecp_osdocument' => 'ecp_osged',
        'ecp_cpdocument' => 'ecp_cpged'
    ];
    
    return $list_cpt_ged_of_document;
}

//usefull to BO customization
//////////////////////////////////
function get_types_for_admin_comment_filter() {
    $post_types_for_admin_comment_filter=[
        [
            'label'=>"Messages",
            'slug'=>"ecp_message"
        ],
        [
            'label'=>"Messages Fepem",
            'slug'=>"ecp_fmessage"
        ],
        [
            'label'=>"Messages OS",
            'slug'=>"ecp_osmessage"
        ],
        [
            'label'=>"Messages CP",
            'slug'=>"ecp_cpmessage"
        ],
    ];
    
    return $post_types_for_admin_comment_filter;
}

function get_message_type_by_role() {
    $message_type_by_role = [
        'admin-fepem-exec' => 'ecp_message',
        'admin-fepem' => 'ecp_fmessage',
        'admin-os' => 'ecp_osmessage',
        'admin-cp' => 'ecp_cpmessage'
    ];
    
    return $message_type_by_role;
}

/**
 * Helper function get getting roles that the user is allowed to create/edit.
 *
 * @param   WP_User $user
 * @return  array
 */
function extranetcp_get_allowed_assign_roles( $user ) {

    $allowed = [];

    if ( in_array( 'admin-fepem-exec', $user->roles ) ) { 
        $allowed = ['admin-fepem-exec','member-extranet'];
    } elseif ( in_array( 'admin-fepem', $user->roles ) ) {
        $allowed = ['admin-fepem','member-extranet'];
    } elseif ( in_array( 'admin-os', $user->roles ) ) {
        $allowed = ['admin-os','member-extranet'];
    }  elseif ( in_array( 'admin-cp', $user->roles ) ) {
        $allowed = ['admin-cp','member-extranet'];
    }

    return $allowed;
}

/**
 * Helper function get getting roles that the user is allowed to delete.
 *
 * @param   WP_User $user
 * @return  array
 */
function extranetcp_get_allowed_delete_roles( $user ) {

    $allowed = [];

    if ( in_array( 'admin-fepem-exec', $user->roles ) ) { 
        $allowed = ['admin-fepem-exec'];
    } elseif ( in_array( 'admin-fepem', $user->roles ) ) {
        $allowed = ['admin-fepem'];
    } elseif ( in_array( 'admin-os', $user->roles ) ) {
        $allowed = ['admin-os'];
    }  elseif ( in_array( 'admin-cp', $user->roles ) ) {
        $allowed = ['admin-cp'];
    }

    return $allowed;
}