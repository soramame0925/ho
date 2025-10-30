<?php
/**
 * Plugin Name: MNO Post Manager
 * Description: Provides structured meta fields and front-end rendering for posts.
 * Version: 1.0.0
 * Author: OpenAI Assistant
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class MNO_Post_Manager {
    const META_PREFIX = '_mpm_';

    public static function init() {
        add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_boxes' ] );
        add_action( 'save_post', [ __CLASS__, 'save_post' ], 10, 2 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_frontend_assets' ] );
    }

    public static function register_meta_boxes() {
        add_meta_box(
            'mno-post-manager',
            __( '投稿管理', 'mno-post-manager' ),
            [ __CLASS__, 'render_meta_box' ],
            'post',
            'normal',
            'high'
        );
    }

    public static function render_meta_box( $post ) {
        wp_nonce_field( 'mno_pm_save_post', 'mno_pm_nonce' );

        $values = self::get_post_values( $post->ID );

        include __DIR__ . '/partials/meta-box.php';
    }

    private static function get_post_values( $post_id ) {
        $defaults = [
            'gallery'        => [],
            'voice_sample'   => '',
            'circle_name'    => '',
            'voice_actors'   => [],
            'illustrators'   => [],
            'normal_price'   => '',
            'sale_price'     => '',
            'sale_end_date'  => '',
            'highlights'     => [],
            'track_list'     => [],
            'sample_lines'   => [],
            'release_date'   => '',
            'genre'          => '',
            'track_duration' => '',
            'buy_url'        => '',
        ];

        $data = [];
        foreach ( $defaults as $key => $default ) {
            $meta_key = self::META_PREFIX . $key;
            $value    = get_post_meta( $post_id, $meta_key, true );
            if ( '' === $value || null === $value ) {
                $value = $default;
            }
            $data[ $key ] = $value;
        }

        $data['gallery']      = is_array( $data['gallery'] ) ? array_map( 'intval', $data['gallery'] ) : [];
        $data['voice_actors'] = is_array( $data['voice_actors'] ) ? array_map( 'sanitize_text_field', $data['voice_actors'] ) : [];
        $data['illustrators'] = is_array( $data['illustrators'] ) ? array_map( 'sanitize_text_field', $data['illustrators'] ) : [];
        $data['highlights']   = is_array( $data['highlights'] ) ? array_map( 'sanitize_textarea_field', $data['highlights'] ) : [];
        $data['track_list']   = is_array( $data['track_list'] ) ? array_map( 'sanitize_text_field', $data['track_list'] ) : [];
        $data['sample_lines'] = is_array( $data['sample_lines'] ) ? array_map( 'sanitize_textarea_field', $data['sample_lines'] ) : [];

        return wp_parse_args( $data, $defaults );
    }

    public static function save_post( $post_id, $post ) {
        if ( ! isset( $_POST['mno_pm_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mno_pm_nonce'] ) ), 'mno_pm_save_post' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( 'post' !== $post->post_type ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = [
            'voice_sample'   => [ __CLASS__, 'sanitize_voice_sample' ],
            'circle_name'    => 'sanitize_text_field',
            'normal_price'   => 'sanitize_text_field',
            'sale_price'     => 'sanitize_text_field',
            'sale_end_date'  => 'sanitize_text_field',
            'release_date'   => 'sanitize_text_field',
            'genre'          => 'sanitize_text_field',
            'track_duration' => 'sanitize_text_field',
            'buy_url'        => 'esc_url_raw',
        ];

        foreach ( $fields as $key => $sanitize_callback ) {
            $raw = isset( $_POST[ 'mno_pm_' . $key ] ) ? wp_unslash( $_POST[ 'mno_pm_' . $key ] ) : '';
            $value = '';
            if ( '' !== $raw ) {
                $value = call_user_func( $sanitize_callback, $raw );
            }
            update_post_meta( $post_id, self::META_PREFIX . $key, $value );
        }

        $gallery_ids = [];
        if ( isset( $_POST['mno_pm_gallery'] ) && is_array( $_POST['mno_pm_gallery'] ) ) {
            $gallery_ids = array_filter( array_map( 'intval', wp_unslash( $_POST['mno_pm_gallery'] ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'gallery', $gallery_ids );

        $voice_actors = [];
        if ( isset( $_POST['mno_pm_voice_actors'] ) && is_array( $_POST['mno_pm_voice_actors'] ) ) {
            $voice_actors = array_values( array_filter( array_map( 'sanitize_text_field', wp_unslash( $_POST['mno_pm_voice_actors'] ) ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'voice_actors', $voice_actors );

        $illustrators = [];
        if ( isset( $_POST['mno_pm_illustrators'] ) && is_array( $_POST['mno_pm_illustrators'] ) ) {
            $illustrators = array_values( array_filter( array_map( 'sanitize_text_field', wp_unslash( $_POST['mno_pm_illustrators'] ) ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'illustrators', $illustrators );

        $highlights = [];
        if ( isset( $_POST['mno_pm_highlights'] ) && is_array( $_POST['mno_pm_highlights'] ) ) {
            $highlights = array_values( array_filter( array_map( 'sanitize_textarea_field', wp_unslash( $_POST['mno_pm_highlights'] ) ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'highlights', $highlights );

        $track_list = [];
        if ( isset( $_POST['mno_pm_track_list'] ) && is_array( $_POST['mno_pm_track_list'] ) ) {
            $track_list = array_values( array_filter( array_map( 'sanitize_text_field', wp_unslash( $_POST['mno_pm_track_list'] ) ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'track_list', $track_list );

        $sample_lines = [];
        if ( isset( $_POST['mno_pm_sample_lines'] ) && is_array( $_POST['mno_pm_sample_lines'] ) ) {
            $sample_lines = array_values( array_filter( array_map( 'sanitize_textarea_field', wp_unslash( $_POST['mno_pm_sample_lines'] ) ) ) );
        }
        update_post_meta( $post_id, self::META_PREFIX . 'sample_lines', $sample_lines );

        $sale_price    = get_post_meta( $post_id, self::META_PREFIX . 'sale_price', true );
        $sale_end_date = get_post_meta( $post_id, self::META_PREFIX . 'sale_end_date', true );

        if ( $sale_price ) {
            $timestamp = $sale_end_date ? strtotime( $sale_end_date . ' 23:59:59' ) : false;
            if ( $timestamp && $timestamp < current_time( 'timestamp' ) ) {
                update_post_meta( $post_id, self::META_PREFIX . 'sale_price', '' );
            }
        }
    }

    public static function get_voice_sample_allowed_tags() {
        $allowed = wp_kses_allowed_html( 'post' );

        $allowed['iframe'] = [
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

        return $allowed;
    }

    private static function sanitize_voice_sample( $value ) {
        return wp_kses( $value, self::get_voice_sample_allowed_tags() );
    }

    public static function enqueue_admin_assets( $hook ) {
        if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'mno-pm-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.css', [], '1.0.0' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'mno-pm-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.js', [ 'jquery', 'jquery-ui-sortable' ], '1.0.0', true );
    }

    public static function enqueue_frontend_assets() {
        if ( ! is_single() ) {
            return;
        }

        wp_enqueue_script( 'mno-pm-frontend', plugin_dir_url( __FILE__ ) . 'assets/frontend.js', [], '1.0.0', true );
        wp_localize_script(
            'mno-pm-frontend',
            'mnoPmSlider',
            [
                'i18n' => [
                    'next'  => __( 'Next', 'mno-post-manager' ),
                    'prev'  => __( 'Previous', 'mno-post-manager' ),
                    'slide' => __( 'Go to slide %d', 'mno-post-manager' ),
                ],
            ]
        );
    }

    public static function get_post_data( $post_id = null ) {
        $post_id = $post_id ?: get_the_ID();
        if ( ! $post_id ) {
            return [];
        }

        return self::get_post_values( $post_id );
    }
}

MNO_Post_Manager::init();

function mno_pm_render_single_template( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    if ( ! $post_id ) {
        return '';
    }

    $data = MNO_Post_Manager::get_post_data( $post_id );

    ob_start();
    include __DIR__ . '/partials/frontend-template.php';
    return ob_get_clean();
}
