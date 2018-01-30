<?php
class cadot_site_link extends WP_Widget {
    function __construct(){
        parent::__construct(false, $name =  __("Cadot Site Link"));
        parent::__construct(
        // Base ID of your widget
        'cadot_site_link', 

        // Widget name will appear in UI
        __('Cadot SiteLink Widget', 'cadot.vn'), 

        // Widget description
        array( 'description' => __( 'Display all site link from Cadot SiteLink', 'cadot.vn' ), ) 
        );
    }
    
/* ==============================================
 * function widget, display widget in theme
 */
    function widget($args, $instance){
        global $cadotSitelink;
        global $cd_split1;
        global $cd_split2;

        $title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        echo "<ul>";
        $cd_array = explode($cd_split1, $cadotSitelink);
        foreach($cd_array as $cdname) {
            $cd_link = explode($cd_split2, $cdname);
            if ($cd_link[0] != '') {
                if (empty($cd_link[2]) || $cd_link[2] == ''){
                    $cd_link[2] = "_blank";
                }
        ?>
            <li> <a href="<?php echo esc_url($cd_link[1]); ?>" target="<?php echo $cd_link[2]; ?>"><?php echo $cd_link[0]; ?></a> <br />
            <?php if($instance['hr'] == "yes"){echo "<hr />";} ?>
            </li>
        <?php }
        }
        echo "</ul>";
        
        echo $args['after_widget'];
    }

/* ==============================================
 * function Widget Backend, create widget form
 */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Site Links', 'cadot.vn' );
        }
        
        if ( isset( $instance[ 'hr' ] ) ) {
            $hr = $instance[ 'hr' ];
        }
        else {
            $hr = __( 'yes', 'cadot.vn' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'hr' ); ?>"><?php _e( 'Horizontal Line: ' ); ?></label> &nbsp;
        <input class="widefat" id="<?php echo $this->get_field_id( 'hr' ); ?>" name="<?php echo $this->get_field_name( 'hr' ); ?>" type="radio" value="yes" <?php if($hr == "yes"){echo "checked";} ?> /> <?php _e( 'Yes' ); ?> &nbsp;
        <input class="widefat" id="<?php echo $this->get_field_id( 'hr' ); ?>" name="<?php echo $this->get_field_name( 'hr' ); ?>" type="radio" value="no" <?php if($hr == "no"){echo "checked";} ?>/> <?php _e( 'No' ); ?>
        </p>
    <?php 
    }

/* ==============================================
 * function Widget update
 * Updating widget replacing old instances with new
 */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( sanitize_text_field($new_instance['title'] )) : '';
        $instance['hr'] = ( ! empty( $new_instance['hr'] ) ) ? $new_instance['hr'] : 'no';
        return $instance;
    }
}

function cadot_register(){
    register_widget("cadot_site_link");
}

add_action('widgets_init', 'cadot_register');
?>
