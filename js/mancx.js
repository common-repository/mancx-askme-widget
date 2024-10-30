function setup_toggle_advanced()
{
    $(".toggle_advanced_controls_button").not("#available-widgets .toggle_advanced_controls_button").click(
        function()
        {
            $(".widget_advanced_controls").not("#available-widgets .widget_advanced_controls").toggle('fast', function(){
                $(".mancx_widget_advanced_expanded").not("#available-widgets .mancx_widget_advanced_expanded").val($(this).is(':visible'));
            });
        }
    );
}

$(document).ajaxSuccess(function(e, xhr, settings) {
    var widget_id_base = 'mancx-askme-widget';

    if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {
        setup_toggle_advanced();
    }
});

$(document).ready(
    function()
    {
        setup_toggle_advanced();
    }
);
