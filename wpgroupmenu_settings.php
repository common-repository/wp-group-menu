<?php
defined('ABSPATH') or die("ERROR: You do not have permission to access this page");

function wpgroupmenu_save_option($option) {
    if ($_POST['options'][$option]) {
        if (get_option($option)) {
            update_option($option, $_POST['options'][$option]);
        } else {
            add_option($option, $_POST['options'][$option]);
        }
    }
}

if ($_POST) {
    foreach ($_POST['options'] as $option => $value) {
        wpgroupmenu_save_option($option);
    }
    ?>
        <div id="message" class="updated fade"><p><strong>Configurations saved</strong></p></div>
    <?php
}
?>

<div class="metabox-holder">
    <div id="post-body">
        <div id="post-body-content">
            <div class="postarea">
                <div class="postbox">
                    <h3>Settings</h3>
                    <div class="inside">
                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <table class="form-table">
                                <tr>
                                    <?php 
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
                                </tr>
                                <tr>
                                    <th><label for="options[wpgroupmenu_background_color]">Background Color</label></th>
                                    <td>
                                    <div class="wpgroupmenu_color_select" id="wpgroupmenu_background_color" style="background-color:<?php echo $back_color;?>">
                                        <input type="hidden" name="options[wpgroupmenu_background_color]" value="<?php echo $back_color;?>">
                                    </div>
                                    <script>
                                        jQuery(function(){
                                            jQuery("#wpgroupmenu_background_color").spectrum({
                                                preferredFormat: "hex",
                                                hideAfterPaletteSelect:true,
                                                showInput: true,
                                                chooseText: 'Select',
                                                move: function (color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_background_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_background_color]"]').val(color.toHexString());
                                                },
                                                change: function(color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_background_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_background_color]"]').val(color.toHexString());
                                                }
                                            });
                                            jQuery("#wpgroupmenu_background_color").spectrum("set", "<?php echo $back_color;?>");
                                        });
                                    </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="options[wpgroupmenu_font_color]">Font Color</label></th>
                                    <td>
                                    <div class="wpgroupmenu_color_select" id="wpgroupmenu_font_color" style="background-color:<?php echo $font_color;?>">
                                        <input type="hidden" name="options[wpgroupmenu_font_color]" value="<?php echo $font_color;?>">
                                    </div>
                                    <script>
                                        jQuery(function(){
                                            jQuery("#wpgroupmenu_font_color").spectrum({
                                                preferredFormat: "hex",
                                                hideAfterPaletteSelect:true,
                                                showInput: true,
                                                chooseText: 'Select',
                                                move: function (color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_font_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_font_color]"]').val(color.toHexString());
                                                },
                                                change: function(color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_font_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_font_color]"]').val(color.toHexString());
                                                }
                                            });
                                            jQuery("#wpgroupmenu_font_color").spectrum("set", "<?php echo $font_color;?>");
                                        });
                                    </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="options[wpgroupmenu_active_background_color]">Active Background Color</label></th>
                                    <td>
                                    <div class="wpgroupmenu_color_select" id="wpgroupmenu_active_background_color" style="background-color:<?php echo $active_back_color;?>">
                                        <input type="hidden" name="options[wpgroupmenu_active_background_color]" value="<?php echo $active_back_color;?>">
                                    </div>
                                    <script>
                                        jQuery(function(){
                                            jQuery("#wpgroupmenu_active_background_color").spectrum({
                                                preferredFormat: "hex",
                                                hideAfterPaletteSelect:true,
                                                showInput: true,
                                                chooseText: 'Select',
                                                move: function (color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_active_background_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_active_background_color]"]').val(color.toHexString());
                                                },
                                                change: function(color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_active_background_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_active_background_color]"]').val(color.toHexString());
                                                }
                                            });
                                            jQuery("#wpgroupmenu_active_background_color").spectrum("set", "<?php echo $active_back_color;?>");
                                        });
                                    </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="options[wpgroupmenu_active_font_color]">Active Font Color</label></th>
                                    <td>
                                    <div class="wpgroupmenu_color_select" id="wpgroupmenu_active_font_color" style="background-color:<?php echo $active_font_color;?>">
                                        <input type="hidden" name="options[wpgroupmenu_active_font_color]" value="<?php echo $active_font_color;?>">
                                    </div>
                                    <script>
                                        jQuery(function(){
                                            jQuery("#wpgroupmenu_active_font_color").spectrum({
                                                preferredFormat: "hex",
                                                hideAfterPaletteSelect:true,
                                                showInput: true,
                                                chooseText: 'Select',
                                                move: function (color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_active_font_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_active_font_color]"]').val(color.toHexString());
                                                },
                                                change: function(color) {
                                                    var background = {
                                                        'background': color.toHexString()
                                                    };
                                                    jQuery('#wpgroupmenu_active_font_color').css(background);
                                                    jQuery('input[name="options[wpgroupmenu_active_font_color]"]').val(color.toHexString());
                                                }
                                            });
                                            jQuery("#wpgroupmenu_active_font_color").spectrum("set", "<?php echo $active_font_color;?>");
                                        });
                                    </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="options[wpgroupmenu_alignment]">Alignment</label></th>
                                    <td>
                                        <select name="options[wpgroupmenu_alignment]">
                                            <option value="left" <?php selected($alignment, 'left'); ?>>Left</option>
                                            <option value="right" <?php selected($alignment, 'right'); ?>>Right</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <input type="submit" name="Submit" class="button-primary" value="Save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>