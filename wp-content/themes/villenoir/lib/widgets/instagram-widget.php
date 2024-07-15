<?php
function register_villenoir_Instagram_Widget() {
    register_widget( 'villenoir_Instagram_Widget' );
}
add_action( 'widgets_init', 'register_villenoir_Instagram_Widget' );

class villenoir_Instagram_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'gg-instagram-feed',
            esc_html__( 'Instagram', 'villenoir' ),
            array( 'classname' => 'gg-instagram-feed', 'description' => esc_html__( 'Displays your latest Instagram photos', 'villenoir' ) )
        );
    }

    function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        $title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
        $limit = empty( $instance['number'] ) ? 6 : $instance['number'];
        $followers = empty( $instance['followers'] ) ? '' : $instance['followers'];
        $link = empty( $instance['link'] ) ? '' : $instance['link'];
        $username = empty( $instance['username'] ) ? '' : $instance['username'];

        echo wp_kses_post($before_widget);
        ?>
        <div class="media">
        <div class="media-left">
        <?php 
        if ( ! empty( $title ) ) { 
            echo wp_kses_post($before_title . $title . $after_title);
        }
        ?>

        <?php
        if ( $followers != '' ) { ?>
        <p class="followers"><?php echo esc_html($followers); ?>
            <span><?php esc_html_e('Instagram Followers', 'villenoir'); ?></span>
        </p>
        <?php } ?>

        <?php if ( $link && $username ) { ?>
        <a class="btn btn-secondary" href="//instagram.com/<?php echo esc_attr( $username ); ?>" rel="me"><?php echo esc_html($link); ?></a>
        <?php } ?>
        </div> <!-- .media-left -->
        
        <div class="media-body">
        <?php 
        //If Instagram Feed plugin is active, run shortcode
        if ( defined( 'SBIVER' ) ) {
            $cols = $limit/2;
            echo do_shortcode("[instagram-feed num=".$limit." imagepadding=0 cols=".$cols." hoverdisplay=likes showheader=false showbutton=false showfollow=false]");
        } else {
            printf( __('<a href="%s">Instagram Feed plugin</a> is not installed!','villenoir'),
                esc_url( 'https://wordpress.org/plugins/instagram-feed/') );
        }
        ?>
        </div><!-- .media-body -->
        </div><!-- .media -->
        
        <?php
        echo wp_kses_post($after_widget);
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'Instagram', 'villenoir' ), 'username' => '', 'followers' => '', 'link' => esc_html__( 'Follow Us', 'villenoir' ), 'number' => 9 ) );
        $title     = esc_attr( $instance['title'] );
        $number    = absint( $instance['number'] );
        $followers = esc_attr( $instance['followers'] );
        $link      = esc_attr( $instance['link'] );
        $username  = esc_attr( $instance['username'] );
        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'villenoir' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html($title); ?>" /></label></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of photos', 'villenoir' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_html($number); ?>" /></label></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'username' )); ?>"><?php esc_html_e( 'Username', 'villenoir' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr($username); ?>" /></label></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'followers' )); ?>"><?php esc_html_e( 'Number of followers', 'villenoir' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'followers' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'followers' ) ); ?>" type="text" value="<?php echo esc_html($followers); ?>" /></label></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'link' )); ?>"><?php esc_html_e( 'Link text', 'villenoir' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></label></p>
        
        <?php

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['number']    = ! absint( $new_instance['number'] ) ? 6 : $new_instance['number'];
        $instance['followers'] = trim( strip_tags( $new_instance['followers'] ) );
        $instance['link']      = strip_tags( $new_instance['link'] );
        $instance['username']      = strip_tags( $new_instance['username'] );
        return $instance;
    }
}