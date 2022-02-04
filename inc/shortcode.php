<?php

class flaschCards_Shortcode
{
  public function register()
  {
    add_action('init', [$this, 'register_shortcode']);
  }

  public function register_shortcode()
  {
    add_shortcode('flashcards', [$this, 'filter_shortcode']);
  }

  public function filter_shortcode($atts = array())
  {

    $params = shortcode_atts(array(
      'category' => '',
      'font-size' => '16px',
      'card-width' => '200px',
      'text-color' => '#1d1d1d',
    ), $atts);



    ob_start();

?>
    <style>
      .<?php echo esc_html($params['category']); ?>.theword {
        font-size: <?php echo esc_html($params['font-size']);  ?> !important;
        color: <?php echo esc_html($params['text-color']);  ?> !important;
        font-weight: bold;
      }

      .<?php echo esc_html($params['category']); ?>.card {
        width: <?php echo esc_html($params['card-width']);  ?> !important;


      }
    </style>
    <div class="cards <?php echo esc_html($params['category']); ?>">

      <?php
      $query = new WP_Query(array(
        'post_type' => 'flashcards',
        'orderby' => 'title',
        'order' => 'ASC',
        'category_name' =>  $params['category'],
        'posts_per_page' => -1,

      ));
      global $post;
      while ($query->have_posts()) : $query->the_post();
        setup_postdata($post);
        $audio = get_post_meta($post->ID, 'fc-audio', true);
        $color = get_post_meta($post->ID, 'fc-color', true);
        $css = get_post_meta($post->ID, 'fc-css', true);
      ?>


        <style>

        </style>
        <a class="card <?php if ($audio) : ?>card-audio<?php endif; ?>" <?php if ($audio) : ?> rel="<?php echo esc_html($audio);
                                                                                                  endif; ?>" style="width:<?php echo esc_html($params['card-width']); ?>;background:<?php echo esc_html($color); ?>;<?php if ($css) echo esc_html($css); ?>">
          <div class="word"><?php echo '<p class="theword" style="font-size:' . esc_html($params['font-size']) . '" >' . get_the_title() . '</p>'; ?></div>

          <div class="example"><?php echo '<i>' . get_the_content() . '</i>'; ?></div>
          <?php if ($audio) : ?><img class="card-image" src="https://img.icons8.com/metro/26/000000/audio-file.png" /><?php endif; ?>
        </a>
      <?php
      endwhile;
      wp_reset_postdata();

      ?>
      <script>
        jQuery(document).ready(function($) {
          jQuery('.card').on('click', function(e) {
            e.preventDefault();
            var audio = jQuery(this).attr('rel');
            var sound = new Howl({
              src: [audio],
              volume: 0.5,
              buffer: true
            }).play();


          });
        });
      </script>


    </div>

<?php

    $output = ob_get_contents(); // всё, что вывели, окажется внутри $output
    ob_end_clean();

    return $output;

    // $table = require FLASHCARDS_PATH . 'inc/templates/table.php';
    // return $table;
  }
}
$flaschCards = new flaschCards_Shortcode();
$flaschCards->register();
