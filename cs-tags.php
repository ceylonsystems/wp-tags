<?php

/*
  Plugin Name: CS Tags
  Plugin URI: http://plugins.ceylonsystems.com
  Description:
  Version: 1.0
  Author: Ceylon Systems
  Author URI: http://www.ceylonsystems.com
  License: GPLv2 or later
 */

function footag_func($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Posts by Tag',
        'taxonomies' => 'post_tag',
        'hide_empty' => '0'
            ), $atts);

    ob_start();
    $tags = get_terms(explode(',', $atts['taxonomies']), array('hide_empty' => ($atts['hide_empty'] ? true : false)));
    $data = array();
    foreach ($tags as $tag) {
        $first_char = strtolower(substr($tag->name, 0, 1));
        if (isset($data[$first_char])) {
            $saved = $data[$first_char];
            $saved[] = $tag;
            $data[$first_char] = $saved;
        } else {
            $array_to_save = array($tag);
            $data[$first_char] = $array_to_save;
        }
    }

    ksort($data);

    echo "<h2 class='cstags-header' id='cstags-header'>{$atts['title']}</h2>";

    echo "<ul class='cstags-tagmap'>";
    foreach ($data as $k => $dd) {
        echo "<li><a href='#cstag-letter-$k'>" . strtoupper($k) . "</a></li>";
    }
    echo "</ul>";

    foreach ($data as $k => $dd) {
        echo "<div class='clearfix'></div>";
        echo "<a id='cstag-letter-$k'></a>";
        echo "<h4 class='cstags-tagline'>" . strtoupper($k) . " <em><a href='#cstags-header'>[back to top]</em></h4>";
        echo "<ul class='cstags-tags'>";
        foreach ($dd as $tag) {
            echo "<li><a href='" . get_term_link($tag) . "'>{$tag->name}</a> ({$tag->count})</li>";
        }
        echo "</ul>";
    }
    ?>




    <?php

    return ob_get_clean();
}

add_shortcode('cstags', 'footag_func');

function cstags_enqueue_scripts() {
    wp_enqueue_style('cstags', plugin_dir_url(__FILE__) . 'includes/main.css');
}

add_action('wp_enqueue_scripts', 'cstags_enqueue_scripts');
