<?php

function wpgroupmenu_manageSites() {
    global $wpdb;
    if($_POST){
        if(isset($_POST['task']) && ($_POST['task'] == 'new' || $_POST['task'] == 'edit')){
            $task = sanitize_text_field($_POST['task']);
            $siteName = trim(sanitize_text_field($_POST['wpgm_siteName']));
            $siteUrl = wpgroupmenu_formatURL(trim(sanitize_text_field($_POST['wpgm_siteUrl'])));
            $siteId = wpgroupmenu_generateKey($siteUrl);
            $siteAlt = trim(sanitize_text_field($_POST['wpgm_siteAlt']));
            $wpdb->show_errors();
            $site = array( 'siteName' => $siteName, 'siteUrl' => $siteUrl, 'siteId'=>$siteId, 'siteAlt'=>$siteAlt);

            switch($task){
               case 'new':
                    $wpdb->insert($wpdb->prefix.'wpgroupmenu_sites', $site);
                    die();
                    break;
               case 'edit':
                   $sid = intval($_POST['sid']);
                   $where = array('sid' => $sid);
                   $wpdb->update($wpdb->prefix.'wpgroupmenu_sites', $site, $where);
                   die();
                   break;
            }
        }
        if(isset($_POST['task']) && $_POST['task'] == 'load'){
            $sid = $_POST['sid'];
            $siteInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."wpgroupmenu_sites WHERE sid=".$sid);
            echo json_encode($siteInfo);
            die();
        }
    }
}

function wpgroupmenu_getmenus(){
    global $wpdb;
    $sql = "SELECT * FROM ".$wpdb->prefix."wpgroupmenu_sites ORDER BY sid ASC";
    $menus = $wpdb->get_results($sql);
    return $menus;
}

function wpgroupmenu_sitecheck($siteID){
    global $wpdb;
    $sql = "SELECT * FROM ".$wpdb->prefix."wpgroupmenu_sites WHERE siteId='$siteID'";
    $results = $wpdb->get_results($sql);
    $found = ($results) ? true : false;
    return $found;
}

function wpgroupmenu_clientRequest(){
    if(isset($_POST['siteID']) && $_POST['action'] == 'requestMenus'){
        if(wpgroupmenu_sitecheck($_POST['siteID'])){
            $results = array('auth' => 1, 'menus' => wpgroupmenu_getmenus());
        }
        else{
            $results = array('auth' => 0);
        }
        echo json_encode($results);
    }
    die();
}


/*
 * Use function if to much DB calls is an issue
 * Update a json file and read the file for menu calls instead of DB calls
 */
function wpgroupmenu_updateJson(){
    $menus = wpgroupmenu_getmenus();
    $menu_file = WPGROUPMENU_PLUGIN_DIR . "menus.json";
    file_put_contents($menu_file, json_encode($menus));
}

function wpgroupmenu_formatURL($url){
    $regex = "@(https?://)*(www\.)*[a-zA-Z0-9-]{1,61}[\.a-zA-Z]+[\/a-zA-Z0-9-]*@";
    preg_match($regex, $url, $matches);
    if(!$matches[1]){
        $url = "http://" . $url;
    }
    if(substr($url, -1) == "/"){
        $url = substr($url, 0, -1);
    }
    return $url;
}


function wpgroupmenu_showmenu(){
    $menus = wpgroupmenu_getmenus();
    $back_color = get_option('wpgroupmenu_background_color');
    $font_color = get_option('wpgroupmenu_font_color');
    $active_back_color = get_option('wpgroupmenu_active_background_color');
    $active_font_color = get_option('wpgroupmenu_active_font_color');
    $alignment = get_option('wpgroupmenu_alignment');
    $siteID = wpgroupmenu_generateKey(get_option('siteurl'));?>
    <div class="wp-group-menu">
        <ul style="background-color: <?php echo $back_color; ?>;">
            <?php foreach($menus as $menu){
                if($menu->siteId == $siteID){ ?>
                    <li style="float: <?php echo $alignment; ?>; background-color: <?php echo $active_back_color; ?>;">
                        <a href="<?php echo $menu->siteUrl; ?>">
                            <span style="color:<?php echo $active_font_color; ?>;"><?php echo $menu->siteName; ?></span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li style="float: <?php echo $alignment; ?>;">
                        <a href="<?php echo $menu->siteUrl; ?>">
                            <span style="color:<?php echo $font_color; ?>;"><?php echo $menu->siteName; ?></span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
   <?php
}

function wpgroupmenu_generateKey($url){
    return md5($url);
}

function wpgroupmenu_redirect($tab){ ?>
    <script type="text/javascript">
        window.location = '<?php echo $tab; ?>';
    </script>
<?php
}

function wpgroupmenu_message($message){ ?>
    <div id="message" class="updated"><p><strong><?php echo $message; ?></strong></p></div>
    <?php
}

