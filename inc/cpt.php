<?php
class FlashcardsCpt
{
  public function register()
  {
    add_action('init', array($this, 'custom_post_type'));
    add_action('add_meta_boxes', [$this, 'add_meta_box_flashcards']);
    add_action('save_post', [$this, 'save_metabox'], 10, 2);
  }

  public function add_meta_box_flashcards()
  {
    add_meta_box(
      'flashcards_audio',
      esc_html__('Flashcard Audio', 'flashcards'),
      [$this, 'metabox_flashcards_html'],
      'flashcards',
      'normal',
      'default'
    );

    add_meta_box(
      'flashcards_bg_color',
      esc_html__('Flashcard Background Color', 'flashcards'),
      [$this, 'metabox_flashcards_color_html'],
      'flashcards',
      'normal',
      'default'
    );

    add_meta_box(
      'flashcards_additional_css',
      esc_html__('Flashcard Additional CSS', 'flashcards'),
      [$this, 'metabox_flashcards_css_html'],
      'flashcards',
      'normal',
      'default'
    );
  }

  public function metabox_flashcards_color_html($post)
  {
    $color = get_post_meta($post->ID, 'fc-color', true);
    echo '<p><label for="color">' . esc_html('Add flashcard background color') . '</label>
    <input id="fc-color" type="text" size="36" name="fc-color" value="' . esc_html($color) . '" />';

    if ($color) : ?>
      <div style="width:40px; height:40px;background:<?php echo $color; ?>"></div>
    <?php
    endif;
  }

  public function metabox_flashcards_html($post)
  {
    $audio = get_post_meta($post->ID, 'fc-audio', true);


    echo '
     <p>
   <label for="fc-audio">' . esc_html('Add audio', 'flashcards') . '</label>
   <input id="upload_image" type="text" size="36" name="fc-audio" value="' . esc_html($audio) . '" />
   <input id="upload_image_button" type="button" value="Upload Audio" />
   </p>';
    if ($audio) :  ?>
      <audio controls>
        <source src="<?php echo $audio; ?>" type="audio/ogg">
        <source src="<?php echo $audio; ?>" type="audio/mpeg">
        Your browser does not support the audio tag.
      </audio>

<?php endif;
  }

  public function metabox_flashcards_css_html($post)
  {
    $css = get_post_meta($post->ID, 'fc-css', true);
    echo '<p><label for="add_css">' . esc_html('Add flashcard additional css') . '</label><br>
    <textarea id="fc-css" type="text" rows="6" name="fc-css" value="' . esc_html($css) . '" /></textarea>';
  }


  public function save_metabox($post_id, $post)
  {
    if (isset($_POST['fc-audio'])) :
      if (is_null($_POST['fc-audio'])) {
        delete_post_meta($post_id, 'fc-audio');
      } else {
        update_post_meta($post_id, 'fc-audio', sanitize_text_field($_POST['fc-audio']));
      }
    endif;
    if (isset($_POST['fc-color'])) :
      if (is_null($_POST['fc-color'])) {
        delete_post_meta($post_id, 'fc-color');
      } else {
        update_post_meta($post_id, 'fc-color', sanitize_text_field($_POST['fc-color']));
      }
    endif;

    if (isset($_POST['fc-css'])) :
      if (is_null($_POST['fc-css'])) {
        delete_post_meta($post_id, 'fc-css');
      } else {
        update_post_meta($post_id, 'fc-css', sanitize_text_field($_POST['fc-css']));
      }
    endif;
    return $post_id;
  }

  public function custom_post_type()
  {
    register_post_type('flashcards', array(
      'labels'             => array(
        'name'               => esc_html__('flashcards', 'flashcards'),
        'singular_name'      => esc_html__('Flashcards', 'flashcards'),
        'add_new'            => esc_html__('Add new term', 'flashcards'),
        'add_new_item'       => esc_html__('Add new term', 'flashcards'),
        'edit_item'          => esc_html__('Edit term', 'flashcards'),
        'new_item'           => esc_html__('New term', 'flashcards'),
        'view_item'          => esc_html__('See term', 'flashcards'),
        'search_items'       => esc_html__('Search term', 'flashcards'),
        'not_found'          => esc_html__('Not found', 'flashcards'),
        'not_found_in_trash' => esc_html__('Not foind', 'flashcards'),
        'parent_item_colon'  => '',
        'menu_name'          => esc_html__('Terms flashcards', 'flashcards')

      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'flashcards'),
      'label' => esc_html__('Flashcards', 'flashcards'),
      'menu_icon'           => 'dashicons-translation',
      'supports' => ['title', 'editor'],
      'taxonomies'  => array('category'),
    ));
  }
}
if (class_exists('FlashcardsCpt')) {
  $flashcards = new FlashcardsCpt();
  $flashcards->register();
}
