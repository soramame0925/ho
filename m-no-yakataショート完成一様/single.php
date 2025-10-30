<?php get_header(); ?>

<main class="mno-container">
  <?php
  if (have_posts()) :
    while (have_posts()) : the_post(); ?>

      <article <?php post_class('mno-single'); ?>>
        <?php
        $title_markup = '<h1 class="mno-single-title">' . esc_html( get_the_title() ) . '</h1>';

        if ( function_exists( 'mno_pm_render_single_template' ) ) {
          $single_content = mno_pm_render_single_template( get_the_ID() );

          if ( false !== strpos( $single_content, 'mno-pm-article__gallery' ) ) {
            $original_content = $single_content;

            $single_content = preg_replace(
              '/(<section\s+class="mno-pm-article__section\s+mno-pm-article__gallery"[^>]*>.*?<\/section>)/s',
              '$1' . $title_markup,
              $single_content,
              1
            );

            if ( null === $single_content ) {
              $single_content = $title_markup . $original_content;
            }
          } else {
            $single_content = $title_markup . $single_content;
          }

          echo $single_content;
        } else {
          echo $title_markup;
          $voice_sample_value = get_post_meta( get_the_ID(), '_mpm_voice_sample', true );
          if ( $voice_sample_value ) {
            $allowed_tags = wp_kses_allowed_html( 'post' );
            $allowed_tags['iframe'] = [
              'src'             => true,
              'width'           => true,
              'height'          => true,
              'frameborder'     => true,
              'allow'           => true,
              'allowfullscreen' => true,
              'loading'         => true,
              'title'           => true,
              'referrerpolicy'  => true,
            ];

            $voice_sample_html = wp_kses( $voice_sample_value, $allowed_tags );

            if ( $voice_sample_html ) {
              echo '<div class="mno-voice-sample">' . $voice_sample_html . '</div>';
            }
          }
          echo '<div class="mno-single__content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';
        }
        ?>
      </article>

    <?php endwhile;
  else : ?>
    <p>記事が見つかりませんでした。</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
