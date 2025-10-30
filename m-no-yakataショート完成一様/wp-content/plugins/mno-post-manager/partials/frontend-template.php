<?php
/** @var array $data */
$gallery        = ! empty( $data['gallery'] ) ? $data['gallery'] : [];
$voice_sample   = isset( $data['voice_sample'] ) ? $data['voice_sample'] : '';
$circle_name    = isset( $data['circle_name'] ) ? $data['circle_name'] : '';
$voice_actors   = ! empty( $data['voice_actors'] ) ? $data['voice_actors'] : [];
$illustrators   = ! empty( $data['illustrators'] ) ? $data['illustrators'] : [];
$normal_price   = isset( $data['normal_price'] ) ? $data['normal_price'] : '';
$sale_price     = isset( $data['sale_price'] ) ? $data['sale_price'] : '';
$sale_end_date  = isset( $data['sale_end_date'] ) ? $data['sale_end_date'] : '';
$highlights     = ! empty( $data['highlights'] ) ? $data['highlights'] : [];
$track_list     = ! empty( $data['track_list'] ) ? $data['track_list'] : [];
$sample_lines   = ! empty( $data['sample_lines'] ) ? $data['sample_lines'] : [];
$release_date   = isset( $data['release_date'] ) ? $data['release_date'] : '';
$genre          = isset( $data['genre'] ) ? $data['genre'] : '';
$track_duration = isset( $data['track_duration'] ) ? $data['track_duration'] : '';
$buy_url        = isset( $data['buy_url'] ) ? $data['buy_url'] : '';

$now_timestamp = current_time( 'timestamp' );
$sale_active   = $sale_price && ( ! $sale_end_date || ( $sale_end_date && strtotime( $sale_end_date . ' 23:59:59' ) >= $now_timestamp ) );

$price_markup = '';
if ( $normal_price || $sale_price ) {
    $price_markup .= '<div class="mno-pm-price">';
    if ( $sale_active && $sale_price ) {
        $price_markup .= '<p class="mno-pm-price__sale"><span class="mno-pm-price__label">' . esc_html__( 'Sale', 'mno-post-manager' ) . '</span>' . esc_html( $sale_price ) . '</p>';
        if ( $normal_price ) {
            $price_markup .= '<p class="mno-pm-price__normal">' . esc_html( $normal_price ) . '</p>';
        }
        if ( $sale_end_date ) {
            $price_markup .= '<p class="mno-pm-price__end">' . sprintf( esc_html__( 'Until %s', 'mno-post-manager' ), esc_html( $sale_end_date ) ) . '</p>';
        }
    } elseif ( $normal_price ) {
        $price_markup .= '<p class="mno-pm-price__normal mno-pm-price__normal--only">' . esc_html( $normal_price ) . '</p>';
    }
    $price_markup .= '</div>';
}

$voice_sample_markup = '';
if ( $voice_sample ) {
    if ( filter_var( $voice_sample, FILTER_VALIDATE_URL ) ) {
        $embed = wp_oembed_get( $voice_sample );
        if ( ! $embed && preg_match( '/\.mp3$|\.wav$|\.m4a$/i', $voice_sample ) ) {
            $embed = '<audio controls preload="none" class="mno-pm-voice-sample__audio"><source src="' . esc_url( $voice_sample ) . '" /></audio>';
        }
        if ( ! $embed && strpos( $voice_sample, 'chobit' ) !== false ) {
            $embed = '<iframe class="mno-pm-voice-sample__iframe" src="' . esc_url( $voice_sample ) . '" loading="lazy" allow="autoplay"></iframe>';
        }
        if ( ! $embed ) {
            $embed = '<a class="mno-pm-voice-sample__link" href="' . esc_url( $voice_sample ) . '" target="_blank" rel="noopener">' . esc_html__( 'Open voice sample', 'mno-post-manager' ) . '</a>';
        }
        $voice_sample_markup = $embed;
    } else {
        $voice_sample_markup = wp_kses( $voice_sample, MNO_Post_Manager::get_voice_sample_allowed_tags() );
    }
}

$buy_button = '';
$button_label = esc_html__( 'DLsiteで購入', 'mno-post-manager' );
if ( $buy_url ) {
    $buy_button = '<a class="mno-pm-buy-button" href="' . esc_url( $buy_url ) . '" target="_blank" rel="noopener noreferrer">' . $button_label . '</a>';
} else {
    $buy_button = '<span class="mno-pm-buy-button mno-pm-buy-button--disabled" aria-disabled="true">' . $button_label . '</span>';
}
?>
<div class="mno-pm-article">
    <?php if ( $gallery ) : ?>
        <section class="mno-pm-article__section mno-pm-article__gallery" aria-label="<?php esc_attr_e( 'Gallery', 'mno-post-manager' ); ?>">
            <div class="mno-pm-slider" data-mno-pm-slider>
                <div class="mno-pm-slider__track">
                    <?php foreach ( $gallery as $image_id ) :
                        $image_html = wp_get_attachment_image( $image_id, 'large', false, [ 'class' => 'mno-pm-slider__image' ] );
                        if ( ! $image_html ) {
                            continue;
                        }
                        ?>
                        <figure class="mno-pm-slider__slide">
                            <?php echo $image_html; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="mno-pm-slider__nav mno-pm-slider__nav--prev" aria-label="<?php esc_attr_e( 'Previous', 'mno-post-manager' ); ?>">&#10094;</button>
                <button type="button" class="mno-pm-slider__nav mno-pm-slider__nav--next" aria-label="<?php esc_attr_e( 'Next', 'mno-post-manager' ); ?>">&#10095;</button>
                <div class="mno-pm-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'Gallery navigation', 'mno-post-manager' ); ?>"></div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( $voice_sample_markup ) : ?>
        <section class="mno-pm-article__section mno-pm-article__voice">
            <div class="mno-voice-sample">
                <?php echo $voice_sample_markup; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( $price_markup || $buy_button ) : ?>
        <section class="mno-pm-article__section mno-pm-article__purchase">
            <?php echo $price_markup; ?>
            <?php echo $buy_button; ?>
        </section>
    <?php endif; ?>

    <section class="mno-pm-article__section">
        <h2>サークル情報</h2>
        <ul class="mno-pm-list">
            <li><span>サークル名：</span><?php echo $circle_name ? esc_html( $circle_name ) : '&mdash;'; ?></li>
            <li><span>声優：</span><?php echo $voice_actors ? esc_html( implode( ' / ', $voice_actors ) ) : '&mdash;'; ?></li>
            <li><span>価格：</span><?php echo $sale_active && $sale_price ? esc_html( $sale_price ) : ( $normal_price ? esc_html( $normal_price ) : '&mdash;' ); ?></li>
            <li><span>イラスト：</span><?php echo $illustrators ? esc_html( implode( ' / ', $illustrators ) ) : '&mdash;'; ?></li>
            <li><span>発売日：</span><?php echo $release_date ? esc_html( $release_date ) : '&mdash;'; ?></li>
            <li><span>ジャンル：</span><?php echo $genre ? esc_html( $genre ) : '&mdash;'; ?></li>
        </ul>
    </section>

    <section class="mno-pm-article__section">
        <h2>作品のみどころ</h2>
        <?php if ( $highlights ) : ?>
            <ul class="mno-pm-list mno-pm-list--bullets">
                <?php foreach ( $highlights as $highlight ) : ?>
                    <li><?php echo esc_html( $highlight ); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>&mdash;</p>
        <?php endif; ?>
    </section>

    <section class="mno-pm-article__section">
        <h2>トラックリスト</h2>
        <?php if ( $track_list ) : ?>
            <ul class="mno-pm-list mno-pm-list--numbered">
                <?php foreach ( $track_list as $index => $track ) : ?>
                    <li><span><?php printf( 'トラック%d：', $index + 1 ); ?></span><?php echo esc_html( $track ); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>&mdash;</p>
        <?php endif; ?>
    </section>

    <section class="mno-pm-article__section">
        <h2>一部セリフ抜粋</h2>
        <?php if ( $sample_lines ) : ?>
            <blockquote class="mno-pm-quote">
                <?php foreach ( $sample_lines as $line ) : ?>
                    <p><?php echo nl2br( esc_html( $line ) ); ?></p>
                <?php endforeach; ?>
            </blockquote>
        <?php else : ?>
            <p>&mdash;</p>
        <?php endif; ?>
    </section>

    <section class="mno-pm-article__section">
        <h2>まとめ</h2>
        <ul class="mno-pm-list">
            <li><span>トラック時間：</span><?php echo $track_duration ? esc_html( $track_duration ) : '&mdash;'; ?></li>
            <li><span>声優：</span><?php echo $voice_actors ? esc_html( implode( ' / ', $voice_actors ) ) : '&mdash;'; ?></li>
            <li><span>ジャンル：</span><?php echo $genre ? esc_html( $genre ) : '&mdash;'; ?></li>
            <li><span>サークル名：</span><?php echo $circle_name ? esc_html( $circle_name ) : '&mdash;'; ?></li>
        </ul>
        <?php echo $buy_button; ?>
    </section>
</div>
