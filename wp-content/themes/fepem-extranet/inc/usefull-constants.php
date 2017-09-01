<?php
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

//CPT used in application
$list_cpt_instances=['commission', 'fcommission'];
$list_cpt_messagerie=['ecp_messagerie', 'ecp_fmessagerie'];
$list_cpt_message=['ecp_message', 'ecp_fmessage'];
$list_cpt_calendrier=['ecp_calendrier', 'ecp_fcalendrier'];
$list_cpt_event=['ecp_event', 'ecp_fevent'];
$list_cpt_ged=['ecp_ged', 'ecp_fged'];
$list_cpt_document=['ecp_document', 'ecp_fdocument'];
$list_cpt_composants=array_merge($list_cpt_messagerie,$list_cpt_message,$list_cpt_calendrier,$list_cpt_event,$list_cpt_ged,$list_cpt_document);
$list_cpt=array_merge($list_cpt_instances,$list_cpt_messagerie,$list_cpt_message,$list_cpt_calendrier,$list_cpt_event,$list_cpt_ged,$list_cpt_document);

