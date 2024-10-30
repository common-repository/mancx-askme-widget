<?php
    function mancx() {
        //echo $_COOKIE['update_msg'];
        //if (isset($_COOKIE['update_msg']))
        //{
            //echo '<div class="updated"><p><strong>'. $_COOKIE['update_msg'] .'</strong></p></div>';
            //setcookie('update_msg', "", time()-3600);
            //unset($_COOKIE['update_msg']);
        //}

        // This url. :)
        $this_url = get_admin_url().'admin.php?page=mancx';
        $widget_host = get_option('mancx_widget_host');

        // Read in existing options from database
        $mancx_username = get_option('mancx_username');
        $mancx_email = get_option('mancx_email');
        $mancx_token = get_option('mancx_token');

        // Fail-safe. Check sanity.
        if($mancx_username == "" || $mancx_email == "" )
        {
            // This menas that there is something fishy with the options. Erase everything.
            update_option('mancx_username', '');
            update_option('mancx_email', '');
            update_option('mancx_token', '');
        }

        // Unregister Form
        if(isset($_POST['action']) && $_POST['action'] == 'unregister') {
            // Unregister. Erase all options
            update_option('mancx_username', '');
            update_option('mancx_email', '');
            update_option('mancx_token', '');

            // Put an settings updated message on the screen
            $msg = "Successfully unregistered the account";
            echo '<div class="updated"><p><strong>'. $msg .'</strong></p></div>';
            //setcookie('update_msg', $msg, time()+3600);
            //$_COOKIE['update_msg'] = "Successfully unregistered the account";
        }

        // Login or signup
        if(isset($_GET['action']))
        {
            if ($_GET['action'] == 'login' || $_GET['action'] == 'signup')
            {
                $success = $_GET['success'];
                if ($success == "True")
                {
                    //Read their posted value
                    $mancx_username = $_GET['username'];
                    $mancx_email = $_GET['email'];
                    $mancx_token = $_GET['sha'];

                    if ($mancx_username != "" && $mancx_email != "" && $mancx_token != "")
                    {
                        // Save the posted value in the database
                        update_option('mancx_username', $mancx_username);
                        update_option('mancx_email', $mancx_email);
                        update_option('mancx_token', $mancx_token);
                        if ($_GET['action'] == 'login')
                        {
                            $msg = "Successfully connected the account";
                        }
                        else if ($_GET['action'] == 'signup')
                        {
                            $msg = "Successfully created the account";
                        }
                    }
                    else
                    {
                        $msg = "An error occured. Please try again later...";
                    }
                }
                else
                {
                    if (isset($_GET['message']))
                    {
                        $msg = $_GET['message'];
                    }
                    else
                    {
                        $msg = "An error occured. Please try again later.";
                    }
                }
            }

            // Put an settings updated message on the screen
            echo '<div class="updated"><p><strong>'. $msg .'</strong></p></div>';
            //setcookie('update_msg', $msg, time()+3600);
            //$_COOKIE['update_msg'] = $msg;
            //echo $_COOKIE['update_msg'];
        }
        // The page is being rendered now.
?>
        <div class="wrap">
            <h2>Mancx AskMe Widget</h2>
<?php
            // Read in existing options from database ... again (freshness is key)
            $mancx_username = get_option('mancx_username');
            $mancx_email = get_option('mancx_email');
            $mancx_token = get_option('mancx_token');

            // Fail-safe. Check sanity.
            if($mancx_username == "" || $mancx_email == "" )
            {
                // This menas that there is something fishy with the options. Erase everything.
                update_option('mancx_username', '');
                update_option('mancx_email', '');
                update_option('mancx_token', '');
            }

            if ($mancx_token != "")
            {
                // Token exists in Database. User is connected.
                // This means that the token is here and valid. Show Unregister form.
?>
                <script type="text/javascript">
                    $(document).ready(
                        function()
                        {
                            // Login Popup
                            $('#unregister_button').click(
                                function()
                                {
                                    $('#unregister_form').submit();
                                }
                            );
                        }
                    );
                </script>

                <h3>Congratulations! Your widget is now ready and you can start using it right away!</h3>

                <p>
                    <span class="left" style="margin-right: 20px;">You are connected as: <b><?php echo $mancx_username; ?></b> / <?php echo $mancx_email; ?></span>
                    <button id="unregister_button" class="button-primary left">Unregister this account</button>
                </p>
                <form id="unregister_form" method="post" action="<?php echo $this_url; ?>">
                    <input type="hidden" name="action" value="unregister">
                    <input type="hidden" name="mancx_token" value="">
                </form>

                <p>Click <a href="<?php echo get_admin_url(); ?>/widgets.php">here</a> manage your Mancx Widget.</p>

<?php
            }
            else
            {
                // Token is missing in Database. User is not yet connected.
                // This means that the token is not present. Present user with login
                // and register options.
?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        // Login Popup
                        $('#login_button').click(
                            function()
                            {
                                $.blockUI(
                                    {
                                        message: $('#login_popup'),
                                        css: { cursor: 'default', width: '430px'}
                                    }
                                );
                            }
                        );

                        // Create account Popup
                        $('#join_button').click(
                            function()
                            {
                                $.blockUI(
                                    {
                                        message: $('#join_popup'),
                                        css: { cursor: 'default', width: '430px'}
                                    }
                                );
                            }
                        );

                        // Global Close Dialog Hooks
                        $('.cancel_button').click(
                            function(event)
                            {
                                event.preventDefault();
                                $.unblockUI();
                            }
                        );
                        $(document).keyup(
                            function(e)
                            {
                                if (e.keyCode == 27)
                                {
                                    $.unblockUI();
                                }
                            }
                        );
                    });
                </script>

                <p>
                    The AskMe widget by Mancx is the ultimate monetizing tool for bloggers and experts.
                </p>
                <p>
                    AskMe enables you to answer questions for money on your blog or website. It provides an excellent opportunity to engage your visitors in real business.
                </p>
                <p>
                    Mancx offer a very simple business model, where you answer the questions and we supply the platform, handle all taxes and administration. You keep 80% of the revenues you generate.
                </p>
                <p>
                    Your visitors think highly of you, and are wiling to pay for your knowledge. Don’t let them down!
                </p>
                <p>
                    AskMe fits web formats like WordPress, Blogger, Joomla, and Drupal etc. All questions generated by the AskMe widget are managed on a specific user account here at mancx.com. The widget is specific for you, and questions asked will not be published elsewhere.
                </p>

                <button id="login_button" class="button-primary">Connect an existing account</button>
                <button id="join_button" class="button-secondary">Create a new account</button>

                <div id="login_popup" style="display:none">

                    <div class="popup_header right">Connect an account</div>
                    <div class="mancx_logo left" style="background-image: url(<?php echo plugins_url().'/mancx-askme-widget/img/mancx_logo_125x50.png'; ?>);"></div>
                    <div class="clear pusher"></div>

                    <form id="login_form" method="post" action="http://<?php echo $widget_host; ?>/widget/login/WordPress/">
                        <input type="hidden" name="callback" value="<?php echo $this_url; ?>">
                        <table class="popup_table">
                            <tr>
                                <td>
                                    <label for="mancx_username">Username / Email:</label>
                                </td>
                                <td>
                                    <input id="mancx_username" type="text" name="mancx_username">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="mancx_password">Password:</label>
                                </td>
                                <td>
                                    <input id="mancx_password" type="password" name="mancx_password">
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" class="button-primary">Connect existing account</button>
                            <button class="button-secondary cancel_button">Cancel</button>
                        </p>
                    </form>
                </div>

                <div id="join_popup" style="display:none">

                    <div class="popup_header right">Create a new account</div>
                    <div class="mancx_logo left" style="background-image: url(<?php echo plugins_url().'/mancx-askme-widget/img/mancx_logo_125x50.png'; ?>);"></div>
                    <div class="clear pusher"></div>

                    <form id="registration_form" method="post" action="http://<?php echo $widget_host; ?>/widget/signup/WordPress/">
                        <input type="hidden" name="callback" value="<?php echo $this_url; ?>">
                        <table class="popup_table">
                            <tr>
                                <td>
                                    <label for="mancx_email">Email address:</label>
                                </td>
                                <td>
                                    <input id="mancx_email" type="text" name="mancx_email">
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" class="button-primary">Create new account</button>
                            <button class="button-secondary cancel_button">Cancel</button>
                        </p>
                    </form>
                </div>
<?php
            }
?>
        </div>
<?php
    }
?>
