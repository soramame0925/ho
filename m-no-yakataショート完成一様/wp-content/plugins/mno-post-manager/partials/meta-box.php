<?php
/** @var array $values */
?>
<div class="mno-pm-meta">
    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( 'ギャラリー画像', 'mno-post-manager' ); ?></h3>
        <p class="description"><?php esc_html_e( '複数の画像を追加し、ドラッグで並べ替えできます。', 'mno-post-manager' ); ?></p>
        <div id="mno-pm-gallery-list" class="mno-pm-gallery">
            <?php if ( ! empty( $values['gallery'] ) ) : ?>
                <?php foreach ( $values['gallery'] as $attachment_id ) :
                    $thumb = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                    ?>
                    <div class="mno-pm-gallery__item">
                        <span class="mno-pm-gallery__handle dashicons dashicons-move" aria-hidden="true"></span>
                        <div class="mno-pm-gallery__preview">
                            <?php echo $thumb ? $thumb : esc_html__( '画像が見つかりません', 'mno-post-manager' ); ?>
                        </div>
                        <input type="hidden" name="mno_pm_gallery[]" value="<?php echo esc_attr( $attachment_id ); ?>" />
                        <button type="button" class="button mno-pm-gallery__remove" aria-label="<?php esc_attr_e( '画像を削除', 'mno-post-manager' ); ?>">&times;</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="button button-secondary" id="mno-pm-add-gallery"><?php esc_html_e( '画像を追加', 'mno-post-manager' ); ?></button>
        <script type="text/template" id="mno-pm-gallery-template">
            <div class="mno-pm-gallery__item">
                <span class="mno-pm-gallery__handle dashicons dashicons-move" aria-hidden="true"></span>
                <div class="mno-pm-gallery__preview">{{image}}</div>
                <input type="hidden" name="mno_pm_gallery[]" value="{{id}}" />
                <button type="button" class="button mno-pm-gallery__remove" aria-label="<?php esc_attr_e( '画像を削除', 'mno-post-manager' ); ?>">&times;</button>
            </div>
        </script>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( '音声サンプル', 'mno-post-manager' ); ?></h3>
        <textarea name="mno_pm_voice_sample" rows="3" class="widefat" placeholder="<?php esc_attr_e( 'URL または埋め込みコードを入力してください', 'mno-post-manager' ); ?>"><?php echo esc_textarea( $values['voice_sample'] ); ?></textarea>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( 'サークル情報', 'mno-post-manager' ); ?></h3>
        <p>
            <label>
                <?php esc_html_e( 'サークル名', 'mno-post-manager' ); ?><br />
                <input type="text" name="mno_pm_circle_name" class="widefat" value="<?php echo esc_attr( $values['circle_name'] ); ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php esc_html_e( '発売日', 'mno-post-manager' ); ?><br />
                <input type="date" name="mno_pm_release_date" value="<?php echo esc_attr( $values['release_date'] ); ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php esc_html_e( 'ジャンル', 'mno-post-manager' ); ?><br />
                <input type="text" name="mno_pm_genre" class="widefat" value="<?php echo esc_attr( $values['genre'] ); ?>" />
            </label>
        </p>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( '出演声優', 'mno-post-manager' ); ?></h3>
        <div class="mno-pm-repeater" data-name="mno_pm_voice_actors">
            <div class="mno-pm-repeater__rows">
                <?php if ( ! empty( $values['voice_actors'] ) ) : ?>
                    <?php foreach ( $values['voice_actors'] as $voice_actor ) : ?>
                        <div class="mno-pm-repeater__row">
                            <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                            <input type="text" name="mno_pm_voice_actors[]" class="widefat" value="<?php echo esc_attr( $voice_actor ); ?>" />
                            <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <script type="text/template" class="mno-pm-repeater__template">
                <div class="mno-pm-repeater__row">
                    <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                    <input type="text" name="mno_pm_voice_actors[]" class="widefat" value="" />
                    <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                </div>
            </script>
            <button type="button" class="button mno-pm-repeater__add"><?php esc_html_e( '声優を追加', 'mno-post-manager' ); ?></button>
        </div>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( 'イラストレーター', 'mno-post-manager' ); ?></h3>
        <div class="mno-pm-repeater" data-name="mno_pm_illustrators">
            <div class="mno-pm-repeater__rows">
                <?php if ( ! empty( $values['illustrators'] ) ) : ?>
                    <?php foreach ( $values['illustrators'] as $illustrator ) : ?>
                        <div class="mno-pm-repeater__row">
                            <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                            <input type="text" name="mno_pm_illustrators[]" class="widefat" value="<?php echo esc_attr( $illustrator ); ?>" />
                            <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <script type="text/template" class="mno-pm-repeater__template">
                <div class="mno-pm-repeater__row">
                    <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                    <input type="text" name="mno_pm_illustrators[]" class="widefat" value="" />
                    <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                </div>
            </script>
            <button type="button" class="button mno-pm-repeater__add"><?php esc_html_e( 'イラストレーターを追加', 'mno-post-manager' ); ?></button>
        </div>
    </section>

    <section class="mno-pm-meta__section mno-pm-meta__section--grid">
        <div>
            <h3><?php esc_html_e( '通常価格', 'mno-post-manager' ); ?></h3>
            <input type="text" name="mno_pm_normal_price" class="widefat" value="<?php echo esc_attr( $values['normal_price'] ); ?>" placeholder="<?php esc_attr_e( '例：1,320円', 'mno-post-manager' ); ?>" />
        </div>
        <div>
            <h3><?php esc_html_e( 'セール価格', 'mno-post-manager' ); ?></h3>
            <input type="text" name="mno_pm_sale_price" class="widefat" value="<?php echo esc_attr( $values['sale_price'] ); ?>" placeholder="<?php esc_attr_e( 'セールがない場合は空欄のままにしてください', 'mno-post-manager' ); ?>" />
        </div>
        <div>
            <h3><?php esc_html_e( 'セール終了日', 'mno-post-manager' ); ?></h3>
            <input type="date" name="mno_pm_sale_end_date" value="<?php echo esc_attr( $values['sale_end_date'] ); ?>" />
            <p class="description"><?php esc_html_e( 'この日付以降は自動的に通常価格に戻ります。', 'mno-post-manager' ); ?></p>
        </div>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( '作品のみどころ', 'mno-post-manager' ); ?></h3>
        <div class="mno-pm-repeater mno-pm-repeater--textarea" data-name="mno_pm_highlights">
            <div class="mno-pm-repeater__rows">
                <?php if ( ! empty( $values['highlights'] ) ) : ?>
                    <?php foreach ( $values['highlights'] as $highlight ) : ?>
                        <div class="mno-pm-repeater__row">
                            <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                            <textarea name="mno_pm_highlights[]" class="widefat" rows="3"><?php echo esc_textarea( $highlight ); ?></textarea>
                            <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <script type="text/template" class="mno-pm-repeater__template">
                <div class="mno-pm-repeater__row">
                    <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                    <textarea name="mno_pm_highlights[]" class="widefat" rows="3"></textarea>
                    <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                </div>
            </script>
            <button type="button" class="button mno-pm-repeater__add"><?php esc_html_e( 'みどころを追加', 'mno-post-manager' ); ?></button>
        </div>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( 'トラックリスト', 'mno-post-manager' ); ?></h3>
        <div class="mno-pm-repeater" data-name="mno_pm_track_list">
            <div class="mno-pm-repeater__rows">
                <?php if ( ! empty( $values['track_list'] ) ) : ?>
                    <?php foreach ( $values['track_list'] as $track ) : ?>
                        <div class="mno-pm-repeater__row">
                            <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                            <input type="text" name="mno_pm_track_list[]" class="widefat" value="<?php echo esc_attr( $track ); ?>" />
                            <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <script type="text/template" class="mno-pm-repeater__template">
                <div class="mno-pm-repeater__row">
                    <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                    <input type="text" name="mno_pm_track_list[]" class="widefat" value="" />
                    <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                </div>
            </script>
            <button type="button" class="button mno-pm-repeater__add"><?php esc_html_e( 'トラックを追加', 'mno-post-manager' ); ?></button>
        </div>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( '一部セリフ抜粋', 'mno-post-manager' ); ?></h3>
        <div class="mno-pm-repeater mno-pm-repeater--textarea" data-name="mno_pm_sample_lines">
            <div class="mno-pm-repeater__rows">
                <?php if ( ! empty( $values['sample_lines'] ) ) : ?>
                    <?php foreach ( $values['sample_lines'] as $sample_line ) : ?>
                        <div class="mno-pm-repeater__row">
                            <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                            <textarea name="mno_pm_sample_lines[]" class="widefat" rows="3"><?php echo esc_textarea( $sample_line ); ?></textarea>
                            <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <script type="text/template" class="mno-pm-repeater__template">
                <div class="mno-pm-repeater__row">
                    <span class="dashicons dashicons-move mno-pm-repeater__handle" aria-hidden="true"></span>
                    <textarea name="mno_pm_sample_lines[]" class="widefat" rows="3"></textarea>
                    <button type="button" class="button mno-pm-repeater__remove" aria-label="<?php esc_attr_e( '削除', 'mno-post-manager' ); ?>">&minus;</button>
                </div>
            </script>
            <button type="button" class="button mno-pm-repeater__add"><?php esc_html_e( 'セリフを追加', 'mno-post-manager' ); ?></button>
        </div>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( 'トラック概要', 'mno-post-manager' ); ?></h3>
        <p>
            <label>
                <?php esc_html_e( 'トラック総時間', 'mno-post-manager' ); ?><br />
                <input type="text" name="mno_pm_track_duration" class="widefat" value="<?php echo esc_attr( $values['track_duration'] ); ?>" placeholder="<?php esc_attr_e( '例：45分20秒', 'mno-post-manager' ); ?>" />
            </label>
        </p>
    </section>

    <section class="mno-pm-meta__section">
        <h3><?php esc_html_e( '購入ボタンのURL', 'mno-post-manager' ); ?></h3>
        <input type="url" name="mno_pm_buy_url" class="widefat" value="<?php echo esc_attr( $values['buy_url'] ); ?>" placeholder="<?php esc_attr_e( 'https://www.dlsite.com/...', 'mno-post-manager' ); ?>" />
    </section>
</div>
