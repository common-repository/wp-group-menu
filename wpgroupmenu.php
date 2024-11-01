<?php
/*
Plugin Name: WP Group Menu
Plugin URI: http://wpgm.vspider.com
Description: Adds a universal topmenu amoung sister websites. Manage menus from one central location and use the client plugin on remaining sites.
Version: 1.0
Author: Kevon Adonis
Author URI: http://www.kevonadonis.com
*/

defined('ABSPATH') or die("ERROR: You do not have permission to access this page");
define('WPGROUPMENU_ACCESS_LEVEL', 'manage_options');
define('WPGROUPMENU_PLUGIN_DIR', dirname(__FILE__).'/');
define('WPGROUPMENU_VERSION', "1.0");
define('WPGROUPMENU_JSON', WPGROUPMENU_PLUGIN_DIR . 'menus.json');
add_action('init', 'wpgroupmenu_functions');
add_action('wp_ajax_submit_site', 'wpgroupmenu_manageSites');
add_action('admin_menu', 'register_wpgroupmenu_menu');
add_action('wp_head', 'wpgroupmenu_front_util');
add_filter('wp_head', 'wpgroupmenu_showmenu');
register_activation_hook(__FILE__,'wpgroupmenu_install');
register_activation_hook(__FILE__,'wpgroupmenu_initialize');


function register_wpgroupmenu_menu(){
    add_menu_page( 'WP Group Menu', 'WP Group Menu', 'manage_options', 'wpgroupmenu', 'wpgroupmenu_dashboard', plugins_url( 'images/icon.png', __FILE__), 99 );
    add_submenu_page( 'wpgroupmenu', 'Manage', 'Manage', 'manage_options', 'admin.php?page=wpgroupmenu&tab=manage' );
    add_submenu_page( 'wpgroupmenu', 'Settings', 'Settings', 'manage_options', 'admin.php?page=wpgroupmenu&tab=settings' );
    add_submenu_page( 'wpgroupmenu', 'Help', 'Help', 'manage_options', 'admin.php?page=wpgroupmenu&tab=help' );
    remove_submenu_page('wpgroupmenu','wpgroupmenu');
}

global $pagenow;
if ('admin.php' == $pagenow && isset($_GET['page']) && ($_GET['page'] == 'wpgroupmenu')){
    add_action('admin_head', 'wpgroupmenu_admin_util');
}

/*
 * Displays the tabs and manages tabs to be displayed
 */
function wpgroupmenu_dashboard() {
    global $pagenow;
    wpgroupmenu_admin_header();

    if (isset($_GET['tab'])){
        wpgroupmenu_admin_tabs($_GET['tab']);
    } else {
        wpgroupmenu_admin_tabs('manage');
    }

    if ($pagenow == 'admin.php' && $_GET['page'] == 'wpgroupmenu' ){
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $tab = 'manage';
        switch ( $tab ){
            case 'manage' :
                include 'wpgroupmenu_manage.php'; break;
            case 'settings' :
                include 'wpgroupmenu_settings.php'; break;
            case 'help' :
                include 'wpgroupmenu_help.php'; break;
            default:
                include 'wpgroupmenu_manage.php';
        }
    }
}

function wpgroupmenu_admin_tabs( $current = 'manage' ) {
    $tabs = array( 'manage' => 'Manage', 'settings' => 'Settings', 'help' => 'Help');
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=wpgroupmenu&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

function wpgroupmenu_admin_header(){ ?>
<div class="wrap">
    <h2>
        <a href="?page=wpgroupmenu&tab=manage"><img src="<?php echo plugins_url('images/admin_logo.png', __FILE__)?>"></a>
        <span style="vertical-align: top; font-size: 11px; color: #777777;"><?php echo "v" . WPGROUPMENU_VERSION; ?></span>
    </h2>
<?php
}

function wpgroupmenu_functions(){
    include( WPGROUPMENU_PLUGIN_DIR . 'wpgroupmenu_functions.php' );
}


function wpgroupmenu_admin_util() {
    global $wp_scripts;
    wp_enqueue_script( 'admin_scripts', plugins_url('js/admin.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script( 'spectrum_script', plugins_url('js/spectrum.js', __FILE__), array( 'jquery' ));
    wp_localize_script( 'ajax_scripts', 'front_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_style( 'style', plugins_url('css/admin.css', __FILE__) );
    wp_enqueue_style( 'menu_style', plugins_url('css/menu.css', __FILE__) );
    wp_enqueue_style( 'spectrum_style', plugins_url('css/spectrum.css', __FILE__) );
    wp_enqueue_script( 'jquery-ui' );
    $ui = $wp_scripts->query('jquery-ui-core');
    $url = "http://code.jquery.com/ui/{$ui->ver}/themes/smoothness/jquery-ui.css";
    wp_enqueue_style('jquery-ui-core', $url, false, $ui->ver);
    wp_enqueue_script( 'jquery-ui-dialog' );
}

function wpgroupmenu_front_util() {
    wp_enqueue_script( 'scripts', plugins_url('js/scripts.js', __FILE__), array( 'jquery' ));
    wp_enqueue_style( 'menu_style', plugins_url('css/menu.css', __FILE__) );
}

function wpgroupmenu_install() {
   global $wpdb;
   require_once(ABSPATH.'wp-admin/includes/upgrade.php');
   $table_name = $wpdb->prefix."wpgroupmenu_sites";
   $sql = "CREATE TABLE ".$table_name." (
        sid int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        siteName varchar(55),
        siteUrl varchar(255),
        siteIcon varchar(55),
        siteId varchar(55),
        siteAlt varchar(255),
        PRIMARY KEY (sid)
        );";
    dbDelta($sql);
    add_option("wpgroupmenu_version", WPGROUPMENU_VERSION);
    add_option("wpgroupmenu_background_color", '#333030');
    add_option("wpgroupmenu_font_color", '#f2f2f2');
    add_option("wpgroupmenu_active_background_color", '#357c99');
    add_option("wpgroupmenu_active_font_color", '#f2f2f2');
    add_option("wpgroupmenu_alignment", 'right');
}

function wpgroupmenu_initialize(){
    global $wpdb;
    $siteName = get_bloginfo('name');
    $siteUrl = home_url();
    $siteId = md5(home_url());
    $siteAlt = get_bloginfo('description');
    $site = array( 'siteName' => $siteName, 'siteUrl' => $siteUrl, 'siteId'=> $siteId, 'siteAlt' => $siteAlt);
    $wpdb->insert($wpdb->prefix.'wpgroupmenu_sites', $site);
}
