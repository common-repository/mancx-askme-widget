<?php
function mancx_widget()
{
    $widget_host = get_option('mancx_widget_host');
    $mancx_username = get_option('mancx_username');
    $mancx_email = get_option('mancx_email');
    $mancx_token = get_option('mancx_token');
    $platform = get_option('mancx_widget_platform');
    $version = get_option('mancx_widget_version');

    if ($mancx_token != "" && $mancx_username != "" && $mancx_email != "")
    {
        // Ok, so all the options seems to be there. \\o/

        // Now get some variables. :)
        $widget_title = get_option('mancx_widget_title');
        $widget_opts = array(
            'size' => 'medium',
            'theme' => '1',
            'currency' => 'USD',
            'platform' => 'wp',
            'version' => 'unknown',
            'copy0' => 'AskMe anything!',
            'copy1' => 'Ask Me about anything that you would like to know and let me know how much you would like to offer for it!',
            'copy2' => 'Your email',
            'copy3' => 'Your offer',
            'token' => str_replace('.', '', microtime(true))
        );

        // Make sure to override the default options
        //echo get_option('mancx_widget_'.$key);
        foreach ($widget_opts as $key => $val)
        {
            $saved_val = get_option('mancx_widget_'.$key);
            //echo $key.": " . $saved_val . "<br />";
            if ($saved_val != "" && $saved_val != $val)
            {
                $widget_opts[$key] = $saved_val;
            }
        }
        $widget_url = "http://". $widget_host ."/widget/show/?sha=".$mancx_token;
        $get_opts = "";
        foreach ($widget_opts as $key => $val) {
            $get_opts .= "&".$key."=".$val;
        }
        $widget_url .= $get_opts;
        //echo "<pre>";
        //echo print_r($widget_opts);
        //echo "</pre>";
        if ($widget_host != "www.mancx.com")
        {
            $widget_url .= "&host=". $widget_host;
        }

        // Print Welcome.
        echo $before_widget.$before_title.$mancx_widget_title.$after_title;
        if ($widget_title)
        {
            echo "<h2>". $widget_title ."</h2>";
        }

        if ($widget_opts['size'] == "small")
        {
            $iframe_w = "180";
            $iframe_h = "300";
        }
        elseif ($widget_opts['size'] == "large")
        {
            $iframe_w = "250";
            $iframe_h = "350";
        }
        else
        {
            $iframe_w = "200";
            $iframe_h = "350";
        }

?>
        <iframe
            id="mancx-widget-<?php echo $mancx_token; ?>"
            scrolling="no"
            frameborder="0"
            style="
                border-style:none;
                overflow:hidden;
                width:<?php echo $iframe_w; ?>px;
                height:<?php echo $iframe_h; ?>px;
                margin: 10px 0px 20px;"
            src="<?php echo $widget_url;?>">
        </iframe>
<?php
        echo $after_widget;
    }
}

function mancx_widget_control()
{
    if (
        isset($_POST['mancx_widget_title']) &&
        isset($_POST['mancx_widget_size']) &&
        isset($_POST['mancx_widget_theme']) &&
        isset($_POST['mancx_widget_copy0']) &&
        isset($_POST['mancx_widget_copy1']) &&
        isset($_POST['mancx_widget_copy2']) &&
        isset($_POST['mancx_widget_copy3']) &&
        isset($_POST['mancx_widget_currency'])
    )
    {
        // Basic Options
        $title = stripslashes(trim($_POST['mancx_widget_title']));
        update_option('mancx_widget_title', $title);
        update_option('mancx_widget_size', $_POST['mancx_widget_size']);
        update_option('mancx_widget_theme', $_POST['mancx_widget_theme']);

        // Advanced Options
        $copy0 = stripslashes(trim($_POST['mancx_widget_copy0']));
        update_option('mancx_widget_copy0', $copy0);

        $copy1 = stripslashes(trim($_POST['mancx_widget_copy1']));
        update_option('mancx_widget_copy1', $copy1);

        $copy2 = stripslashes(trim($_POST['mancx_widget_copy2']));
        update_option('mancx_widget_copy2', $copy2);

        $copy3 = stripslashes(trim($_POST['mancx_widget_copy3']));
        update_option('mancx_widget_copy3', $copy3);

        update_option('mancx_widget_currency', $_POST['mancx_widget_currency']);

        $was_advanced = $_POST['mancx_widget_advanced_expanded'];
?>
        <script type="text/javascript">
            $(document).ready(
                function()
                {
                    if ('<?php echo $was_advanced; ?>' === 'true')
                    {
                        $(".widget_advanced_controls").not("#available-widgets .widget_advanced_controls").show();//function(){
                        $(".mancx_widget_advanced_expanded").not("#available-widgets .mancx_widget_advanced_expanded").val($(this).is(':visible'));
                    }
                }
            );
        </script>
<?php

    }
    $widget_token = get_option('mancx_token');
    $widget_title = get_option('mancx_widget_title');
    $widget_size = get_option('mancx_widget_size');
    $widget_theme = get_option('mancx_widget_theme');
    $widget_copy0 = get_option('mancx_widget_copy0');
    $widget_copy1 = get_option('mancx_widget_copy1');
    $widget_copy2 = get_option('mancx_widget_copy2');
    $widget_copy3 = get_option('mancx_widget_copy3');
    $widget_currency = get_option('mancx_widget_currency');

    if ($widget_token == "")
    {
        //echo 'widget token: '.$widget_token.'<br />';
        echo '<br /><br /><center>';
        echo 'This widget is not yet configured.<br />';
        echo 'Click <a href="'. get_admin_url() .'admin.php?page=mancx">here</a> to configure this widget.';
        echo '</center><br /><br />';
    }
    else
    {
?>
        <label>Title: <input class="widefat" id="mancx_widget_title" name="mancx_widget_title" type="text" value="<?php echo $widget_title; ?>"></label>
        <table id="widget_control">
            <tr>
                <td>
                    <label for="mancx_widget_size">Size:</label>
                </td>
                <td>
                    <select id="mancx_widget_size" name="mancx_widget_size">
                        <option value="small"<?php echo selected('small', $widget_size); ?>>Small (180 x 300 px)</option>
                        <option value="medium"<?php echo selected('medium', $widget_size); ?>>Medium (200 x 350 px)</option>
                        <option value="large"<?php echo selected('large', $widget_size); ?>>Large (250 x 350 px)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="mancx_widget_theme">Theme:</label>
                </td>
                <td>
                    <select id="mancx_widget_theme" name="mancx_widget_theme">
                        <option value="1"<?php echo selected('1', $widget_theme); ?>>Mancx Green (Default)</option>
                        <option value="2"<?php echo selected('2', $widget_theme); ?>>Orange</option>
                        <option value="3"<?php echo selected('3', $widget_theme); ?>>Dark Gray</option>
                        <option value="4"<?php echo selected('4', $widget_theme); ?>>Light Gray</option>
                        <option value="5"<?php echo selected('5', $widget_theme); ?>>Blue</option>
                        <option value="6"<?php echo selected('6', $widget_theme); ?>>Red</option>
                    </select>
                </td>
            </tr>
        </table>

        <br />




        <div class="button-secondary toggle_advanced_controls_button pusher"><div class="button_text">Toggle advanced options</div></div>
        <div class="widget_advanced_controls" style="display:none;">
            <input class="mancx_widget_advanced_expanded" name="mancx_widget_advanced_expanded" type="hidden" value="" />

            <label for="widget-copy0">Widget tagline <i>(optional)</i>:</label><br />
            <input id="widget-copy0" class="pusher clear wide" name="mancx_widget_copy0" type="text" value="<?php echo $widget_copy0; ?>" /><br />

            <label for="widget-copy1">Question textarea label <i>(optional)</i>:</label><br />
            <input id="widget-copy1" class="pusher clear wide" name="mancx_widget_copy1" type="text" value="<?php echo $widget_copy1; ?>" /><br />

            <label for="widget-copy2">Email input label <i>(optional)</i>:</label><br />
            <input id="widget-copy2" class="pusher clear wide" name="mancx_widget_copy2" type="text" value="<?php echo $widget_copy2; ?>" /><br />

            <label for="widget-copy3">Offer input babel <i>(optional)</i>:</label><br />
            <input id="widget-copy3" class="pusher clear wide" name="mancx_widget_copy3" type="text" value="<?php echo $widget_copy3; ?>" /><br />

            <label for="mancx_widget_currency">Widget currency:</label><br />
            <select id="mancx_widget_currency" name="mancx_widget_currency">
                <option value="USD"<?php echo selected('USD', $widget_currency); ?>>USD</option>
                <option value="EUR"<?php echo selected('EUR', $widget_currency); ?>>EUR</option>
                <option value="GBP"<?php echo selected('GBP', $widget_currency); ?>>GPB</option>
                <option value="SEK"<?php echo selected('SEK', $widget_currency); ?>>SEK</option>
            </select>


        </div>

<?php
    }
}

function init_mancx_widget()
{
    register_sidebar_widget("Mancx AskMe Widget", "mancx_widget");
    register_widget_control("Mancx AskMe Widget", "mancx_widget_control");
}

add_action("plugins_loaded", "init_mancx_widget");
?>
