<?php

/**
Plugin Name: Education FlashCards
Plugin URI: https://hedomi.com/plugins/flashcards/
Description: Plugin creates cool flashcards in custom post type and then renders it by category
Version: 1.0.0
Author: Hedomi
Author URI: https://hedomi.com/
License: GPLv2 or later
Text Domain: flashcards
Domain Path: /lang
 */

if (!defined('ABSPATH')) {
  die;
}

define('FLASHCARDS_PATH', plugin_dir_path(__FILE__));

if (!class_exists('FlashcardsCpt')) {
  require FLASHCARDS_PATH . 'inc/cpt.php';
  require FLASHCARDS_PATH . 'inc/shortcode.php';
}


class FlashCards
{
  function register()
  {
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
    add_filter('enter_title_here', [$this, 'my_title_placeholder']);
    add_action('init', [$this, 'load_text_domain']);
  }



  public function my_title_placeholder($title)
  {
    global $post;
    if ($post->post_type == 'flashcards') {
      $my_title = esc_html__('Enter a Term', 'flashcards');
      return $my_title;
    }

    return $title;
  }


  public function enqueue_admin()
  {
    wp_enqueue_script('flashcards_script', plugins_url('/assets/js/admin/main.js', __FILE__), array('jquery'), true);
    wp_enqueue_style('flashcards_script', plugins_url('/assets/css/admin/styles.css', __FILE__));
  }

  public function enqueue_front()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_style('flashcards_style_front', plugins_url('/assets/css/front/styles.css', __FILE__));
    wp_enqueue_script('flashcards_script_front', plugins_url('/assets/js/front/main.js', __FILE__), true);
    wp_enqueue_script('flashcards_howler', 'https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.js');
  }

  // translation files

  function load_text_domain()
  {
    load_plugin_textdomain('flashcards', false, dirname(plugin_basename(__FILE__)) . '/lang');
  }
}


if (class_exists('FlashCards')) {
  $FlashCards = new FlashCards();
  $FlashCards->register();
}
