<?php
/*
Plugin Name: Random Quote Generator
Plugin URI: /#
Description: Display a random quote on your WordPress website.
Version: 1.0.0
Author: Kafayat Faniran
Author URI: https://www.linkedin.com/in/kafayatfaniran
License: GPL2
*/

if( ! defined( 'ABSPATH' )) {
  exit('Get out!');
}

// Plugin activation and deactivation hooks
register_activation_hook(__FILE__, 'random_quote_generator_activate');
register_deactivation_hook(__FILE__, 'random_quote_generator_deactivate');

function random_quote_generator_activate() {
    // !
}

function random_quote_generator_deactivate() {
    // No deactivation tasks
}

class RandomQuoteGenerator {

    public function __construct() {
        // Registering  hooks and filters
        add_action('init', array($this, 'init'));
        add_shortcode('random_quote', array($this, 'shortcode_random_quote'));
    }

    public function init() {
        // Registering the custom post type for quotes so it can display a menu on dashboard
        $this->register_quote_post_type();
    }

    // Registering the custom post type for quotes
    private function register_quote_post_type() {
        $labels = array(
            'name'               => __('Quotes', 'random-quote-generator'),
            'singular_name'      => __('Quote', 'random-quote-generator'),
            'menu_name'          => __('Quotes', 'random-quote-generator'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'has_archive'         => true,
        );

        register_post_type('quote', $args);
    }

    // Shortcode function to display a random quote
    public function shortcode_random_quote() {
        $args = array(
            'post_type'         => 'quote',
            'posts_per_page'    => 1,
            'orderby'           => 'rand',
        );

        $quote_query = new WP_Query($args);

        if ($quote_query->have_posts()) {
            $quote_query->the_post();
            $quote_content = get_the_content();
            wp_reset_postdata();

            // Generating and returning the random quote HTML output
            $quote_output = '<div class="random-quote">';
            $quote_output .= '<blockquote>' . $quote_content . '</blockquote>';
            $quote_output .= '</div>';

            return $quote_output;
        }

        return 'No quotes found.';
    }
}

// Finally instantiate the plugin class
new RandomQuoteGenerator();
