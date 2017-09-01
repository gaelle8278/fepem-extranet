<?php
/** 
 * Content of template for CPT calendrier : agenda view
 */
?>
<div>
    <div id='ecp-calendrier'>
    </div>

    <script>
        jQuery(document).ready( function($) {
            $('#ecp-calendrier').fullCalendar({
                header: {
                    left: 'prev today next',
                    center: 'title',
                    right: 'month agendaWeek agendaDay'
                },
                timezone: 'local',
                locale: 'fr',
                timeFormat: 'H:mm',
                displayEventEnd : true,
                events: {
                    url: '/wp-content/themes/fepem-extranet/inc/feed-calendar-events.php',
                    type: 'POST',
                    data: {
                        instance_id: <?php echo $instance->ID ?>
                    },
                    error: function() {
                        alert('Il ya eu une erreur dans la récupération des événements.');
                    },
                    allDayDefault: false
                },
                eventRender: function(event, element, view) {
                    if(view.name == 'month') {
                        element.find('.fc-time').after('<br/>');
                        }
                    }

            });
        });
    </script>
</div>
