<?php

/**
 * The Class for the Widget
 *
 */
class PayPalDonations_Widget extends WP_Widget {
    /**
    * Constructor
    *
    */
    function PayPalDonations_Widget() {
        // Widget settings.
        $widget_ops = array ( 'classname' => 'widget_paypal_donations', 'description' => __('PayPal Donation Button', 'paypal-donations') );

        // Widget control settings.
        $control_ops = array( 'id_base' => 'paypal_donations' );

        // Create the Widget
        $this->WP_Widget( 'paypal_donations', 'PayPal Donations', $widget_ops );
    }

    /**
    * Output the Widget
    *
    */
    function widget( $args, $instance ) {
        extract( $args );
        // global $paypal_donations;
        $paypal_donations = PayPalDonations::getInstance();

        // Get the settings
        $title = apply_filters('widget_title', $instance['title'] );
        $text = $instance['text'];
        $purpose = $instance['purpose'];
        $reference = $instance['reference'];

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
        if ( $text )
            echo wpautop( $text );
        echo $paypal_donations->generateHtml( $purpose, $reference );
        echo $after_widget;
    }
    
    /**
      * Saves the widgets settings.
      *
      */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['text'] = $new_instance['text'];
        $instance['purpose'] = strip_tags(stripslashes($new_instance['purpose']));
        $instance['reference'] = strip_tags(stripslashes($new_instance['reference']));

        return $instance;
    }

    /**
    * The Form in the Widget Admin Screen
    *
    */
    function form( $instance ) {
        // Default Widget Settings
        $defaults = array( 'title' => __('Donate', 'paypal-donations'), 'text' => '', 'purpose' => '', 'reference' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'paypal-donations'); ?> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($instance['text']); ?></textarea>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('purpose'); ?>"><?php _e('Purpose:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('purpose'); ?>" name="<?php echo $this->get_field_name('purpose'); ?>" type="text" value="<?php echo esc_attr($instance['purpose']); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('reference'); ?>"><?php _e('Reference:', 'paypal-donations'); ?> 
            <input class="widefat" id="<?php echo $this->get_field_id('reference'); ?>" name="<?php echo $this->get_field_name('reference'); ?>" type="text" value="<?php echo esc_attr($instance['reference']); ?>" />
            </label>
        </p>
        <?php 
    }
}
