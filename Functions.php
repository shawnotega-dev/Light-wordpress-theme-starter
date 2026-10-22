<?php
/**
 * Advanced Production-Ready WordPress Starter Theme
 *
 * @package Custom_Starter
 * @author Oghenetega Shawn Abiyere
 * @version 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* ==========================================================================
   1. CORE THEME SETUP & ENQUEUEING
   ========================================================================== */

function custom_starter_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'custom-starter' ),
        'footer'  => __( 'Footer Menu', 'custom-starter' ),
    ) );
}
add_action( 'after_setup_theme', 'custom_starter_setup' );

function custom_starter_scripts() {
    wp_enqueue_style( 'custom-starter-style', get_stylesheet_uri(), array(), '1.2.0' );
}
add_action( 'wp_enqueue_scripts', 'custom_starter_scripts' );


/* ==========================================================================
   2. CUSTOM POST TYPE: CASE STUDIES / PORTFOLIO
   (Demonstrates custom data modeling without relying on plugins)
   ========================================================================== */

function custom_register_case_studies_cpt() {
    $labels = array(
        'name'               => _x( 'Case Studies', 'post type general name', 'custom-starter' ),
        'singular_name'      => _x( 'Case Study', 'post type singular name', 'custom-starter' ),
        'menu_name'          => _x( 'Case Studies', 'admin menu', 'custom-starter' ),
        'add_new_item'       => __( 'Add New Case Study', 'custom-starter' ),
        'edit_item'          => __( 'Edit Case Study', 'custom-starter' ),
        'all_items'          => __( 'All Case Studies', 'custom-starter' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'case-studies' ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true, // Enables Gutenberg editor support
    );

    register_post_type( 'case_study', $args );
}
add_action( 'init', 'custom_register_case_studies_cpt' );


/* ==========================================================================
   3. HEAD CLEANUP & PERFORMANCE OPTIMIZATION
   (Removes default WordPress head bloat to boost PageSpeed)
   ========================================================================== */

function custom_cleanup_wp_head() {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'start_post_rel_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link' );
    
    // Disable Emojis for speed
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'custom_cleanup_wp_head' );


/* ==========================================================================
   4. TECHNICAL SEO: AUTOMATED JSON-LD SCHEMA MARKUP
   (Injects structured data for Google & Search Engines)
   ========================================================================== */

function custom_add_schema_json_ld() {
    if ( is_single() || is_page() ) {
        global $post;
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => is_single() ? 'Article' : 'WebPage',
            'mainEntityOfPage' => get_permalink( $post->ID ),
            'headline'      => get_the_title( $post->ID ),
            'datePublished' => get_the_date( 'c', $post->ID ),
            'dateModified'  => get_the_modified_date( 'c', $post->ID ),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
            ),
        );

        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'custom_add_schema_json_ld' );
