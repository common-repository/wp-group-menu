<?php
if(!class_exists('WPGroupMenu_List')){
    require_once( WPGROUPMENU_PLUGIN_DIR . 'wpgroupmenu_class.php' );
}
if(is_admin() && isset($_GET['tab']) && $_GET['tab']=="manage"){
    if(isset($_GET["task"])){
        $task = sanitize_text_field($_GET["task"]);
        switch($task){
            case 'delete':
                wpgroupmenu_deleteSite($_GET['id'], true);
                wpgroupmenu_showSites();
                break;
        }
    }else{
        wpgroupmenu_showSites();
    }
}

function wpgroupmenu_deleteSite($id, $message){
    global $wpdb;
    $wpdb->show_errors();
    $sid = intval($id);
    if(current_user_can(WPGROUPMENU_ACCESS_LEVEL)){
        $siteName = $wpdb->get_row("SELECT siteName FROM ".$wpdb->prefix."wpgroupmenu_sites WHERE sid=".$sid);
        $wpdb->query("delete FROM ".$wpdb->prefix."wpgroupmenu_sites WHERE sid=".$sid);
    }else{
        "You do not have permission to delete this entry";
    }
    if($message){
        wpgroupmenu_message( $siteName->siteName . " was successfully deleted");
    }
}

function wpgroupmenu_showSites(){ ?>
    <h2>Sites   <input type="button" onclick="wpgroupmenu_addSite()" class="button-primary" id="wpgm_addSite" value="Add Site"></h2>
    <div id="dialog-form" class="hidden">
        <p class="validateTips">Enter the name and URL of the site.</p>
        <form>
            <fieldset>
                <label for="siteName">Site Name</label>
                <input type="text" name="siteName" id="siteName" class="text ui-widget-content ui-corner-all">
                <label for="siteUrl">Site URL</label>
                <input type="text" name="siteUrl" id="siteUrl" class="text ui-widget-content ui-corner-all">
                <label for="siteAlt">Site Alt</label>
                <input type="text" name="siteAlt" id="siteAlt" class="text ui-widget-content ui-corner-all">
            </fieldset>
        </form>
    </div>
    <form id="showSites" method="get">
        <input type="hidden" name="page" value="wpgroupmenu" />
        <input type="hidden" name="tab" value="manage" />
           <?php
                $showSites = new WPGroupMenu_List();
                $showSites->prepare_items();
                $showSites->display();
            ?>
    </form>
    <div class="ajax_loader"></div>
    <?php
}