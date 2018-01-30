<?php
/*
    Plugin name: Cadot Site Link
    Version: 1.1
    Description: Cadot Sitelink plugin form, menu and Widget display
    Author: dohaibac@gmail.com
    Author URI: http://cadot.vn
    Plugin URI: http://cadot.vn
*/

$cd_split1 = "|cd|";
$cd_split2 = "=>";
$cadotSitelink = get_option("cadot_sitelink");

include 'cadot_widget.php';
if ( is_admin() ){ // admin actions
    add_action("admin_menu", "cadot_SiteLinkPluginMenu");
    add_action('admin_init', 'cadot_register_mysettings' );
}

function cadot_register_mysettings() {
    register_setting( 'cadot', 'cadot_sitelink', '' ); 
} 

function cadot_SiteLinkPluginMenu(){
    $appName = "Cadot SiteLink";
    $appID = "cadot-sitelink";
    
    add_menu_page($appName, $appName, 'administrator', $appID . '', 'cadot_SitelinkAdminScreen');
}

/* ==============================================
 * function Control and display site link in admin screen
 */
function cadot_SitelinkAdminScreen(){
    global $cadotSitelink;
    global $cd_split1;
    global $cd_split2;
    
    if(isset($_POST["submit"]) && isset($_POST["link_name"]) && isset($_POST["site_link"])) {
        $link_name = sanitize_text_field( $_POST['link_name'] );
        $site_link = sanitize_text_field( esc_url_raw( $_POST['site_link'] ) );
        $target = sanitize_text_field( $_POST['site_link_target'] );
        
        cadot_sitelink_add($link_name, $site_link, $target);
    }
    
    $cd_array = explode($cd_split1, $cadotSitelink);
    
    if (isset($_POST["remove_link"]) && isset($_POST["list_link"]) && sizeof($_POST["list_link"]) > 0) {
        cadot_sitelink_remove($_POST["list_link"], $cd_array);
        $cd_array = explode($cd_split1, $cadotSitelink);
    }
    
    echo "<h1>Cadot SiteLink Plugin</h1>";
    ?>
    <div class="wrap">
        <form name="sitelink_form" method="post" action="">
            <table border="0"><tr>
            <td>Link Name </td><td><input type="text" name="link_name" value="" size="20"> ex: Cadot website</td></tr>
            <td>Site Link </td><td><input type="text" name="site_link" value="" size="20"> ex: cadot.vn</td></tr>
            <td>Target </td><td>
                <select name="site_link_target" size="1">
                    <option value="_blank">_blank</option>
                    <option value="_self">_self</option>
                </select>
            </td></tr>
            <tr><td colspan="2"><?php submit_button(); ?></td></tr>
            </table>
        </form>
    </div>
    
    <div>
        <form action="" method="post" name="sitelink-list">
        <table border="1" style="border-collapse: collapse; border-style: solid; width:80%;">
            <tr bgcolor="lightgray">
                <td>&nbsp;</td>
                <td>Site Name</td>
                <td>Link</td>
                <td>Target</td>
            </tr>
            <?php
            for ($x = 0; $x < sizeof($cd_array); $x++) {
                $cd_link = explode($cd_split2, $cd_array[$x]);
                if ($cd_link[0] != '') {
                    if (empty($cd_link[2]) || $cd_link[2] == ''){
                        $cd_link[2] = "_blank";
                    }
            ?>
            <tr>
                <td><input type="checkbox" name="list_link[]" value="<?php echo $x; ?>"> </td>
                <td><?php echo $cd_link[0]; ?></td>
                <td><?php echo $cd_link[1]; ?></td>
                <td><?php echo $cd_link[2]; ?></td>
            </tr>
            <?php }
            } 
            if (sizeof($cd_array) > 1) {
            ?>
            <tr>
                <td colspan="4" align="left"><input type="submit" value="Delete" name="remove_link" class="button button-primary"></td>
            </tr>
            <?php } ?>
        </table>
        </form>
    </div>
<?php }

/* ==============================================
 * function remove new site link from database
 */
function cadot_sitelink_remove($indexs, $cd_array){
    _e ("list link remove: " . implode(", ", $indexs));
    if (sizeof($indexs) > 0) {
        rsort($indexs);
        for ($x = 0; $x < sizeof($indexs); $x++) {
            $index = intval($indexs[$x]);
            if ($index < sizeof($cd_array)){
                unset($cd_array[$index]);
            }
        }
        
        global $cadotSitelink;
        global $cd_split1;
        $cadotSitelink = implode($cd_split1,$cd_array);
        update_option("cadot_sitelink", $cadotSitelink);
        _e(' Removed');
    }
}

/* ==============================================
 * function add new site link to database
 */
function cadot_sitelink_add ($link_name, $site_link, $target){
    global $cd_split1;
    global $cd_split2;
    global $cadotSitelink;
    
    if ($site_link != ''){
        if (substr($site_link, 0, 7) != "http://" && substr($site_link , 0, 8) != "https://") {
            $site_link = "http://" . $site_link;
        }
    }else{
        _e('Please set the link to target site');
    }
    if ($link_name != ''){
        $cadotSitelink = $cadotSitelink . $cd_split1 . $link_name . $cd_split2 . $site_link . $cd_split2 . $target;
        update_option("cadot_sitelink", $cadotSitelink);
    }else{
        _e('Please set the link name');
    }
}
?>
