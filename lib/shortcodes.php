<?php

add_shortcode( 'pitchpro', 'pitchpro_shortcode' );
add_shortcode( 'pitchpro_campaign', 'pitchpro_campaign_shortcode' );
add_shortcode( 'pitchpro_pitch', 'pitchpro_pitch_shortcode' );

function pitchpro_shortcode($atts){

    $properties = shortcode_atts( array(
        'type' => 'default',
        'limit' => 3
    ), $atts, 'pitchpro' );

    ob_start();

    switch( $properties['type'] ){
        case 'send-pitch-button':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/button-send-pitch.php';
            break;
        case 'send-pitch':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/action-send-pitch.php';
            break;
        case 'my-campaigns':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE,
                'posts_per_page' => $properties['limit']
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/list-my-campaigns.php';
            break;
        case 'my-pitches':
            $shortcode_args = array(
                'post_type' => PitchPro_Pitch::POSTTYPE,
                'post_status' => array('publish','draft','expire', 'sent'),
                'posts_per_page' => $properties['limit']
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/list-my-pitches.php';
            break;
        default:
            $shortcode_args = array();
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/default.php';
            break;
    }

    global $wp_query;
    $shortcode_wp_query = new WP_Query($shortcode_args);
    $wp_query = $shortcode_wp_query;
    include $shortcode_template;
    wp_reset_query();

    $html = ob_get_clean();

    return $html;
}

function pitchpro_campaign_shortcode( $atts ){
    $properties = shortcode_atts( array(
        "type" => 'my-campaigns',
        "limit" => 3
    ), $atts, 'pitchpro_campaign' );
    return pitchpro_shortcode($properties);
}

function pitchpro_pitch_shortcode( $atts ){
    $properties = shortcode_atts( array(
        "type" => 'my-pitches',
        "limit" => 3
    ), $atts, 'pitchpro_pitch' );
    return pitchpro_shortcode($properties);
}
