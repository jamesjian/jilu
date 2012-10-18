<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">        
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $keyword; ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo HTML_ROOT . 'css/site.css'; ?>" />            
        <link rel="stylesheet" type="text/css" href="<?php echo HTML_ROOT . 'css/mem.css'; ?>" />            
        <!--[if IE]>
            <link  rel="stylesheet" type="text/css" href="/css/admin_ie.css" />    
        <![endif]-->            
        <link rel="shortcut icon" href="<?php echo HTML_ROOT . 'image/icon/favicon.ico?v3'; ?>" />
    </head>
    <body>
        <?php
        if (\App\Transaction\User::user_has_loggedin()) {
            ?>
            <a href="<?php echo MEM_HTML_ROOT . 'user/logout'; ?>">Logout</a>
            <?php
            $menu_arr = array('Book' => 'book',
                'Chapter' => 'chapter',
                'Section' => 'section',
            );
            ?>
            <nav>
                <ul>
                    <?php
                    $current_l1_menu = \App\Transaction\Session::get_mem_current_l1_menu();
                    foreach ($menu_arr as $menu_name => $controller_name) {
                        if ($current_l1_menu == $menu_name) {
                            $active_class= ' class="zx-mem-active-menu"';
                        } else {
                            $active_class = '';
                        }
                        ?>
                        <li>
                            <?php $link = MEM_HTML_ROOT . $controller_name . '/retrieve'; ?>
                            <a href="<?php echo $link; ?>" <?php echo $active_class;?>><?php echo $menu_name; ?></a>	
                        </li>
                        <?php
                    }
                    ?>
                        <li><a href="<?php echo MEM_HTML_ROOT . 'user/profile';?>">Profile</a></li>
                </ul>
            </nav>
            <?php
        } //if logged in
        ?>
        <?php
        echo $content;
        ?>

        <script type="text/javascript" src="<?php echo HTML_ROOT . 'js/jquery/jquery-1.8.1.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo HTML_ROOT . 'js/mem.js'; ?>"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35557322-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>        
    </body>
</html>