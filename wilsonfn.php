<?php
require get_template_directory() . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
include('functions-two.php');
function wilsonhotwater_theme_setup()
{
    // Add support for various theme features
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');

    // Add support for Gutenberg
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'wilsonhotwater_theme_setup');
function add_svg_to_upload_mimes($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'add_svg_to_upload_mimes');

function wilsonhotwater_theme_scripts()
{
    wp_enqueue_style('wilsonhotwater-theme-style', get_stylesheet_uri());
    wp_enqueue_script('jquerymin', get_template_directory_uri() . '/assets/js/jquery-3.7.1.min.js', '20130509', true);
    wp_enqueue_style('wilsonhotwater-owl-carousel-css', get_template_directory_uri() . '/assets/css/owl/owl.carousel.min.css', time(), true);
    wp_enqueue_style('wilsonhotwater-owl-theme-css', get_template_directory_uri() . '/assets/css/owl/owl.theme.default.min.css', time(), true);
    wp_enqueue_style('wilsonhotwater-css', get_template_directory_uri() . '/assets/css/style.css', time(), true);
    wp_enqueue_script('wilsonhotwater-owl-carousel', get_template_directory_uri() . '/assets/js/owl/owl.carousel.min.js', array('jquerymin'), '20130508', true);
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('wilsonhotwater-customizer', get_template_directory_uri() . '/assets/js/custom.js', array('wilsonhotwater-owl-carousel'), '20130508', true);
    wp_localize_script('wilsonhotwater-customizer', 'wilsonhotwater_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_script('wilsonhotwater-customizer-dev', get_template_directory_uri() . '/assets/js/custom-dev.js', array('wilsonhotwater-owl-carousel'), '20130508', true);
    wp_localize_script('wilsonhotwater-customizer-dev', 'wilsonhotwater_ajax_object_dev', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'wilsonhotwater_theme_scripts');


function wilsonhotwater_theme_enqueue_block_editor_assets()
{
    wp_enqueue_script(
        'wilsonhotwater-theme-block',
        get_template_directory_uri() . '/assets/js/block.js',
        array('wp-blocks', 'wp-element')
    );
}
add_action('enqueue_block_editor_assets', 'wilsonhotwater_theme_enqueue_block_editor_assets');
function register_my_menus()
{
    register_nav_menus(
        array(
            'header-menu' => __('Header Menu'),
            'wilson-hot-water-menu' => __('Wilson Hot Water Menu'),
            'wilson-heat-pumps-menu' => __('Wilson Heat Pumps Menu'),
            'wilson-hot-water-header-menu' => __('Wilson Hot Water Header Menu'),
            'wilson-heat-pumps-header-menu' => __('Wilson Heat Pumps Header Menu'),
            'footer-3-menu' => __('Footer3 Menu'),
        )
    );
}
add_action('init', 'register_my_menus');

/*
*
*Footer widget section
*/
function wilsonhotwater_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Header Menu Widget Area', 'wilsonhotwater'),
        'id'            => 'headermenu-widget-area',
        'description'   => __('Widgets added here will appear in the header menu area.', 'wilsonhotwater'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Banner Wilson Hot Water', 'wilsonhotwater'),
        'id'            => 'footer-widget-wilsonhotwater-area',
        'description'   => __('Footer CTA for Wilson Hot Water', 'wilsonhotwater'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Banner Wilson Heat Pumps', 'wilsonhotwater'),
        'id'            => 'footer-widget-wilsonheatpumps-area',
        'description'   => __('Footer CTA for Wilson Hot Water', 'wilsonhotwater'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'wilsonhotwater'),
        'id'            => 'footer-widget-area',
        'description'   => __('Widgets added here will appear in the footer area.', 'wilsonhotwater'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => __('Mobile Menu', 'wilsonhotwater'),
        'id'            => 'mobile-menu-area',
        'description'   => __('Widgets added here will appear in the mobile menu.', 'wilsonhotwater'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'wilsonhotwater_widgets_init');

/*
*
*Testimonial CPT Code
*/
function create_testimonial_cpt()
{
    $labels = array(
        'name' => _x('Testimonials', 'Post Type General Name', 'wilsonhotwater'),
        'singular_name' => _x('Testimonial', 'Post Type Singular Name', 'wilsonhotwater'),
        'menu_name' => _x('Testimonials', 'Admin Menu text', 'wilsonhotwater'),
        'name_admin_bar' => _x('Testimonial', 'Add New on Toolbar', 'wilsonhotwater'),
        'archives' => __('Testimonial Archives', 'wilsonhotwater'),
        'attributes' => __('Testimonial Attributes', 'wilsonhotwater'),
        'parent_item_colon' => __('Parent Testimonial:', 'wilsonhotwater'),
        'all_items' => __('All Testimonials', 'wilsonhotwater'),
        'add_new_item' => __('Add New Testimonial', 'wilsonhotwater'),
        'add_new' => __('Add New', 'wilsonhotwater'),
        'new_item' => __('New Testimonial', 'wilsonhotwater'),
        'edit_item' => __('Edit Testimonial', 'wilsonhotwater'),
        'update_item' => __('Update Testimonial', 'wilsonhotwater'),
        'view_item' => __('View Testimonial', 'wilsonhotwater'),
        'view_items' => __('View Testimonials', 'wilsonhotwater'),
        'search_items' => __('Search Testimonial', 'wilsonhotwater'),
        'not_found' => __('Not found', 'wilsonhotwater'),
        'not_found_in_trash' => __('Not found in Trash', 'wilsonhotwater'),
        'featured_image' => __('Featured Image', 'wilsonhotwater'),
        'set_featured_image' => __('Set featured image', 'wilsonhotwater'),
        'remove_featured_image' => __('Remove featured image', 'wilsonhotwater'),
        'use_featured_image' => __('Use as featured image', 'wilsonhotwater'),
        'insert_into_item' => __('Insert into testimonial', 'wilsonhotwater'),
        'uploaded_to_this_item' => __('Uploaded to this testimonial', 'wilsonhotwater'),
        'items_list' => __('Testimonials list', 'wilsonhotwater'),
        'items_list_navigation' => __('Testimonials list navigation', 'wilsonhotwater'),
        'filter_items_list' => __('Filter testimonials list', 'wilsonhotwater'),
    );
    $args = array(
        'label' => __('Testimonial', 'wilsonhotwater'),
        'description' => __('Custom post type for testimonials', 'wilsonhotwater'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-testimonial',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('testimonial', $args);


    $labels = array(
        'name' => _x('Product', 'Post Type General Name', 'wilsonhotwater'),
        'singular_name' => _x('Product', 'Post Type Singular Name', 'wilsonhotwater'),
        'menu_name' => _x('Product', 'Admin Menu text', 'wilsonhotwater'),
        'name_admin_bar' => _x('Product', 'Add New on Toolbar', 'wilsonhotwater'),
        'archives' => __('Product Archives', 'wilsonhotwater'),
        'attributes' => __('Product Attributes', 'wilsonhotwater'),
        'parent_item_colon' => __('Parent Product:', 'wilsonhotwater'),
        'all_items' => __('All Products', 'wilsonhotwater'),
        'add_new_item' => __('Add New Product', 'wilsonhotwater'),
        'add_new' => __('Add New', 'wilsonhotwater'),
        'new_item' => __('New Product', 'wilsonhotwater'),
        'edit_item' => __('Edit Product', 'wilsonhotwater'),
        'update_item' => __('Update Product', 'wilsonhotwater'),
        'view_item' => __('View Product', 'wilsonhotwater'),
        'view_items' => __('View Product', 'wilsonhotwater'),
        'search_items' => __('Search Product', 'wilsonhotwater'),
        'not_found' => __('Not found', 'wilsonhotwater'),
        'not_found_in_trash' => __('Not found in Trash', 'wilsonhotwater'),
        'featured_image' => __('Featured Image', 'wilsonhotwater'),
        'set_featured_image' => __('Set featured image', 'wilsonhotwater'),
        'remove_featured_image' => __('Remove featured image', 'wilsonhotwater'),
        'use_featured_image' => __('Use as featured image', 'wilsonhotwater'),
        'insert_into_item' => __('Insert into Product', 'wilsonhotwater'),
        'uploaded_to_this_item' => __('Uploaded to this Product', 'wilsonhotwater'),
        'items_list' => __('Products list', 'wilsonhotwater'),
        'items_list_navigation' => __('Products list navigation', 'wilsonhotwater'),
        'filter_items_list' => __('Filter products list', 'wilsonhotwater'),
    );
    $args = array(
        'label' => __('Products', 'wilsonhotwater'),
        'description' => __('Custom post type for products', 'wilsonhotwater'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-products',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array('product_range'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'product'),
    );
    register_post_type('product', $args);
    $labels = array(
        'name' => _x('Slider', 'Post Type General Name', 'wilsonhotwater'),
        'singular_name' => _x('Slider', 'Post Type Singular Name', 'wilsonhotwater'),
        'menu_name' => _x('Slider', 'Admin Menu text', 'wilsonhotwater'),
        'name_admin_bar' => _x('Slider', 'Add New on Toolbar', 'wilsonhotwater'),
        'archives' => __('Slider Archives', 'wilsonhotwater'),
        'attributes' => __('Slider Attributes', 'wilsonhotwater'),
        'parent_item_colon' => __('Parent Slider:', 'wilsonhotwater'),
        'all_items' => __('All Slider', 'wilsonhotwater'),
        'add_new_item' => __('Add New Slider', 'wilsonhotwater'),
        'add_new' => __('Add New', 'wilsonhotwater'),
        'new_item' => __('New Slider', 'wilsonhotwater'),
        'edit_item' => __('Edit Slider', 'wilsonhotwater'),
        'update_item' => __('Update Slider', 'wilsonhotwater'),
        'view_item' => __('View Slider', 'wilsonhotwater'),
        'view_items' => __('View Slider', 'wilsonhotwater'),
        'search_items' => __('Search Slider', 'wilsonhotwater'),
        'not_found' => __('Not found', 'wilsonhotwater'),
        'not_found_in_trash' => __('Not found in Trash', 'wilsonhotwater'),
        'featured_image' => __('Featured Image', 'wilsonhotwater'),
        'set_featured_image' => __('Set featured image', 'wilsonhotwater'),
        'remove_featured_image' => __('Remove featured image', 'wilsonhotwater'),
        'use_featured_image' => __('Use as featured image', 'wilsonhotwater'),
        'insert_into_item' => __('Insert into slider', 'wilsonhotwater'),
        'uploaded_to_this_item' => __('Uploaded to this slider', 'wilsonhotwater'),
        'items_list' => __('Slider list', 'wilsonhotwater'),
        'items_list_navigation' => __('Slider list navigation', 'wilsonhotwater'),
        'filter_items_list' => __('Filter slider list', 'wilsonhotwater'),
    );
    $args = array(
        'label' => __('Slider', 'wilsonhotwater'),
        'description' => __('Custom post type for slider', 'wilsonhotwater'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-slides',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',

    );
    register_post_type('slider', $args);

    $labels = array(
        'name' => _x('Location', 'Post Type General Name', 'wilsonhotwater'),
        'singular_name' => _x('Location', 'Post Type Singular Name', 'wilsonhotwater'),
        'menu_name' => _x('Location', 'Admin Menu text', 'wilsonhotwater'),
        'name_admin_bar' => _x('Location', 'Add New on Toolbar', 'wilsonhotwater'),
        'archives' => __('Location Archives', 'wilsonhotwater'),
        'attributes' => __('Location Attributes', 'wilsonhotwater'),
        'parent_item_colon' => __('Location:', 'wilsonhotwater'),
        'all_items' => __('All Locations', 'wilsonhotwater'),
        'add_new_item' => __('Add New Location', 'wilsonhotwater'),
        'add_new' => __('Add New', 'wilsonhotwater'),
        'new_item' => __('New Location', 'wilsonhotwater'),
        'edit_item' => __('Edit Location', 'wilsonhotwater'),
        'update_item' => __('Update Location', 'wilsonhotwater'),
        'view_item' => __('View Location', 'wilsonhotwater'),
        'view_items' => __('View Location', 'wilsonhotwater'),
        'search_items' => __('Search Location', 'wilsonhotwater'),
        'not_found' => __('Not found', 'wilsonhotwater'),
        'not_found_in_trash' => __('Not found in Trash', 'wilsonhotwater'),
        'featured_image' => __('Featured Image', 'wilsonhotwater'),
        'set_featured_image' => __('Set featured image', 'wilsonhotwater'),
        'remove_featured_image' => __('Remove featured image', 'wilsonhotwater'),
        'use_featured_image' => __('Use as featured image', 'wilsonhotwater'),
        'insert_into_item' => __('Insert into Location', 'wilsonhotwater'),
        'uploaded_to_this_item' => __('Uploaded to this Location', 'wilsonhotwater'),
        'items_list' => __('Location list', 'wilsonhotwater'),
        'items_list_navigation' => __('Location list navigation', 'wilsonhotwater'),
        'filter_items_list' => __('Filter Location list', 'wilsonhotwater'),
    );
    $args = array(
        'label' => __('Locations', 'wilsonhotwater'),
        'description' => __('Custom post type for locations', 'wilsonhotwater'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-location',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'location'),
    );
    register_post_type('location', $args);
}
add_action('init', 'create_testimonial_cpt', 0);

// Register Custom Taxonomy
function register_product_range_taxonomy()
{
    $labels = array(
        'name' => _x('Product Range', 'taxonomy general name', 'wilsonhotwater'),
        'singular_name' => _x('Product Range', 'taxonomy singular name', 'wilsonhotwater'),
        'search_items' => __('Search Product Range', 'wilsonhotwater'),
        'all_items' => __('All Product Ranges', 'wilsonhotwater'),
        'parent_item' => __('Parent Product Range', 'wilsonhotwater'),
        'parent_item_colon' => __('Parent Product Range:', 'wilsonhotwater'),
        'edit_item' => __('Edit Product Range', 'wilsonhotwater'),
        'update_item' => __('Update Product Range', 'wilsonhotwater'),
        'add_new_item' => __('Add New Product Range', 'wilsonhotwater'),
        'new_item_name' => __('New Product Range Name', 'wilsonhotwater'),
        'menu_name' => __('Product Ranges', 'wilsonhotwater'),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
        'rewrite'     => array('slug' => 'product-range'),
    );
    register_taxonomy('product_range', array('product'), $args);
}
add_action('init', 'register_product_range_taxonomy', 0);

/*
*Custom taxonomy pincodes
* 
*/
function create_pincode_taxonomy()
{
    $labels = array(
        'name'              => _x('Pincodes', 'taxonomy general name', 'wilsonhotwater'),
        'singular_name'     => _x('Pincode', 'taxonomy singular name', 'wilsonhotwater'),
        'search_items'      => __('Search Pincodes', 'wilsonhotwater'),
        'all_items'         => __('All Pincodes', 'wilsonhotwater'),
        'parent_item'       => __('Parent Pincode', 'wilsonhotwater'),
        'parent_item_colon' => __('Parent Pincode:', 'wilsonhotwater'),
        'edit_item'         => __('Edit Pincode', 'wilsonhotwater'),
        'update_item'       => __('Update Pincode', 'wilsonhotwater'),
        'add_new_item'      => __('Add New Pincode', 'wilsonhotwater'),
        'new_item_name'     => __('New Pincode Name', 'wilsonhotwater'),
        'menu_name'         => __('Pincode', 'wilsonhotwater'),
    );

    $args = array(
        'hierarchical'      => true, // True for category-like behavior
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'pincode'),
    );

    register_taxonomy('pincode', array('location'), $args);
}
add_action('init', 'create_pincode_taxonomy', 0);



/*
* Get custom post type Testimonials
*/
function get_testimonials()
{
    ob_start();
    $testimonials = "";
    $args = array(
        'post_type' => 'testimonial', // Replace with your custom post type
        'posts_per_page' => 10,
    );
    $custom_query = new WP_Query($args);
    $testimonials .= "<div class='owl-carousel owl-theme main-testimonial slider-light-theme'>";
    if ($custom_query->have_posts()) :
        while ($custom_query->have_posts()) : $custom_query->the_post();
            $companyName = get_field('company_name', get_the_ID());
            $companyNameHtml = !empty($companyName) ? "<span class='companyname'>" . esc_html($companyName) . "</span>" : "";
            $content = get_the_content();
            $testimonials .= '<div class="teatimonial-wrap">';
            $testimonials .= "<div>" . wp_kses_post($content) . "</div>"; // Use wp_kses_post to ensure safe HTML
            $testimonials .= '<h4 class="testimonial-name">' . get_the_title() . '</h4>';
            $testimonials .= "<h4>" . $companyNameHtml . "</h4>";
            $testimonials .= '</div>';
        endwhile;
        wp_reset_postdata();
    else:
        $testimonials .= "<p>" . esc_html__('No posts found.', 'wilsonhotwater') . "</p>";
    endif;

    $testimonials .= "</div>";
    ob_get_clean();
    return $testimonials;
}
add_shortcode("testimonials", "get_testimonials");


/*
* Get data for product specifications
*/
add_shortcode("specification_table", "get_specificationData");
function get_specificationData()
{
    ob_start();
    $specificationData = "";
    $file = get_field('specifications_file', get_the_ID());
    $specificationDes = get_field('specification_description', get_the_ID());
    $specificationData .= "<div id='specification_main' class='specification_main'>";
    // if(!empty($file)):
    $specificationData .= "<div  class='table_space'>";
    if (!empty($file) || $specificationDes != "") {
        $specificationData .= "<h4 class='specification_title b-title'>Specifications</h4>";
    }
    if ($specificationDes != "") {

        $specificationData .= "<div class='specification_description'><p>" . $specificationDes . "</p></div>";
    }
    if (!empty($file)):
        try {
            if (!empty($file)):
                $file_path = get_attached_file($file['ID']);
                $spreadsheet = IOFactory::load($file_path);
                $specificationData .= '<div class="table-wrap">';
                // $sheet = $spreadsheet->getActiveSheet(); 
                // $data = $sheet->toArray(null, true, true, true);
                //     if(!empty($data)){
                //         $specificationData .= '<table>';
                //         foreach ($data as $rowIndex => $row) {
                //             $specificationData .= '<tr>';
                //             foreach ($row as $cell) {
                //                 if ($rowIndex == 1) {
                //                     $specificationData .= '<th>' . esc_html($cell) . '</th>';
                //                 } else {
                //                     $specificationData .= '<td>' . esc_html($cell) . '</td>';
                //                 }
                //         }
                //         $specificationData .= '</tr>';
                //     }
                //     $specificationData .= '</table>';
                //     $specificationData .= '</div>';
                // }  
                foreach ($spreadsheet->getAllSheets() as $sheet) {
                    // $sheetName = $sheet->getTitle();
                    $sheetData = $sheet->toArray(); // Convert sheet data to an array
                    $specificationData .= "<p>$sheetName</p>";
                    $specificationData .= "<table>";
                    foreach ($sheetData as $row) {
                        $specificationData .= "<tr>";
                        foreach ($row as $cell) {
                            if ($cell != "") {
                                $specificationData .= "<td>" . htmlspecialchars($cell) . "</td>";
                            }
                        }
                        $specificationData .= "</tr>";
                    }
                    $specificationData .= "</table>";
                }
                $specificationData .= "</div>";

            endif;
        } catch (Exception $e) {
            wp_send_json_error('Error loading file: ' . $e->getMessage());
        }
        $specificationData .= '</div>';
    endif;
    $specificationData .= '</div>';
    // endif;
    ob_get_clean();
    return $specificationData;
}


/*
* Get data for product Technical Data
*/
add_shortcode("technical_table", "get_technicalData");
function get_technicalData()
{
    ob_start();
    $technicalData = "";
    $file = get_field('technical_data_file', get_the_ID());
    $technicalImg = get_field('technical_data_image', get_the_ID());
    // if(!empty($file)):
    if (!empty($file) || !empty($technicalImg)) {
        $technicalData .= "<div id='technical_data_main' class='technical_data_main'>";
        $technicalData .= "<div class='table_space'>";
        $technicalData .= "<h4 class='technical_title b-title'>Technical Data</h4>";
    }
    $technicalData .= "<div class='table-wih-img-wrap'>";
    if (!empty($file)) {
        try {
            if (!empty($file)) {
                $technicalData .= "<div class='table-wrap'>";
                $file_path = get_attached_file($file['ID']);
                $spreadsheet = IOFactory::load($file_path);
                foreach ($spreadsheet->getAllSheets() as $sheet) {
                    $sheetName = $sheet->getTitle();
                    $sheetData = $sheet->toArray(); // Convert sheet data to an array
    
                    $technicalData .= "<table>";
                    foreach ($sheetData as $row) {
                        $technicalData .= "<tr>";
                        $colspan = 1; // Initialize colspan counter to 1 (minimum span for a cell)
                        $prevCell = null; // Track the previous cell
    
                        foreach ($row as $cell) {
                            if ($cell === "" || is_null($cell)) {
                                // Increment colspan for blank cells
                                $colspan++;
                            } else {
                                // If there was a previous cell, output it with the colspan
                                if ($prevCell !== null) {
                                    $technicalData .= "<td" . ($colspan > 1 ? " colspan='" . $colspan . "'" : "") . ">" . htmlspecialchars($prevCell) . "</td>";
                                }
                                // Reset colspan and set the current cell as the new previous cell
                                $colspan = 1;
                                $prevCell = $cell;
                            }
                        }
    
                        // Output the last cell in the row
                        if ($prevCell !== null) {
                            $technicalData .= "<td" . ($colspan > 1 ? " colspan='" . $colspan . "'" : "") . ">" . htmlspecialchars($prevCell) . "</td>";
                        }
    
                        $technicalData .= "</tr>";
                    }
    
                    $technicalData .= "</table>";
                }
                $technicalData .= "</div>";
            }
        } catch (Exception $e) {
            wp_send_json_error('Error loading file: ' . $e->getMessage());
        }
    }
    if (!empty($technicalImg)) {
        $technicalData .= "<div class='technical_img'><img src='" . $technicalImg['url'] . "' alt='" . $technicalImg['title'] . "'/></div>";
    }
    // endif;  
    $technicalData .= "</div>";
    $technicalData .= '</div>';
    $technicalData .= '</div>';
    ob_get_clean();
    return $technicalData;
}
/*
* Get data for product Customisations
*/
add_shortcode('customisations_description', 'get_customisations');
function get_customisations()
{
    ob_start();
    $customisations = get_field('customisations', get_the_ID());
    $customisationData = "";
    if ($customisations != ""):
        $customisationData .= "<div id='customisationdata_main'  class='customisationdata_main'>";
        if (!empty($file) || !empty($customisations)) {
            $customisationData .= "<div class='table_space'>";
            $customisationData .= "<h4 class='customisationdata_title b-title'>Customisations</h4>";
        }
        $customisationData .= "<div class='customisation_data'>" . $customisations . "</div>";
        $customisationData .= "</div>";
        $customisationData .= "</div>";
    endif;
    ob_get_clean();
    return $customisationData;
}

/*
* Get data for product Other wilson products
*/
add_shortcode('otherwilsonproduct', 'get_otherWilsonProducts');
function get_otherWilsonProducts()
{
    ob_start();
    $otherwilsonproducts = "";
    $otherwilsonproducts .= "<div class='otherwilsonproducts_main table_space table_space_bottom'>";
    if (!empty($file) || !empty($otherwilsonproducts)) {
        $otherwilsonproducts .= "<h4 class='otherwilsonproducts_title b-title'>Other Wilson products</h4>";
    }
    $terms = wp_get_post_terms(get_the_ID(), 'product_range');
    if (!empty($terms)):
        $category_slug = $terms[0]->slug;
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'       => 'date',
            'order'         => 'DESC',
            'post__not_in' => array(get_the_ID()),
            'tax_query'     => array(
                array(
                    /*'taxonomy' => 'product_category',*/
                    'taxonomy'  => 'product_range',
                    'field'    => 'slug',
                    'terms'    => $category_slug,
                    'operator' => 'IN',
                ),
            ),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            // Start the Loop
            $otherwilsonproducts .= '<div class="owl-carousel owl-theme other-products-wrap slider-light-theme">';
            while ($query->have_posts()) : $query->the_post();
                $otherwilsonproducts .= '<div class="other-products-list">';
                if (has_post_thumbnail()) {
                    $otherwilsonproducts .= '<a href="' . get_the_permalink() . '" class="thumbnail_link"><div class="post-thumbnail">';
                    $otherwilsonproducts .= get_the_post_thumbnail(get_the_ID(), 'medium');
                    $otherwilsonproducts .= '</div></a>';
                }
                $otherwilsonproducts .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
                $otherwilsonproducts .= '</div>';
            endwhile;
            $otherwilsonproducts .= '</div>';
            // Restore original Post Data
            wp_reset_postdata();
        else :
            $otherwilsonproducts .= "<span>No Products found</span>";
        endif;
    endif;
    $otherwilsonproducts .= "</div>";
    ob_get_clean();
    return $otherwilsonproducts;
}
/*
* Product page sidebar
* Condition to show sidebar Specifications
* Condition to show technical Data
* Condition to show customisations
*/
add_shortcode("product_sidebar", "product_sidebar");
function product_sidebar()
{
    ob_start();
    $stockists = get_field('stockists', get_the_ID());
    $file = get_field('specifications_file', get_the_ID());
    $specificationDes = get_field('specification_description', get_the_ID());
    $file_technicaldata = get_field('technical_data_file', get_the_ID());
    $technicalImg = get_field('technical_data_image', get_the_ID());
    $customisations = get_field('customisations', get_the_ID());
    $downloadBrochure = get_field('brochure', get_the_ID());
    $html = "";
    $html .= "<div class='product_sidebar_main'>";
    $html .= "<ul class='product_sidebar_main_list'>";
    if (!empty($file) || $specificationDes != ""):
        $html .= "<li class='product_specification product_sidebar_link'><a href='#specification_main'>" . __('Specifications', 'wilsonhotwater') . " <span></span></a></li>";
    endif;
    if (!empty($file_technicaldata) || !empty($technicalImg)):
        $html .= "<li class='product_technical product_sidebar_link'><a href='#technical_data_main'>" . __('Technical Data', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($customisations) || $customisations != ""):
        $html .= "<li class='customisations product_sidebar_link'><a href='#customisationdata_main'>" . __('Customisations', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($stockists) || $stockists != ""):
        $html .= "<li class='stockists product_sidebar_link'><a href='#stockistdata_main'>" . __('Stockists', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($downloadBrochure)):
        $html .= "<li class='download_brochure download-btn-blue btn'><a href='" . $downloadBrochure['url'] . "' target='_blank'>Download Brochure PDF<span></span></a></li>";
    endif;
    $html .= "</ul>";
    $html .= "</div>";
    ob_get_clean();
    return $html;
}



/*
* Remove Description field from taxonomy
*/
function remove_taxonomy_description_field()
{
    global $current_screen;

    if (isset($current_screen->id) && strpos($current_screen->id, '_edit') !== false) {
?>
        <style>
            .term-description-wrap {
                display: none;
            }
        </style>
    <?php
    }
}
add_action('admin_head', 'remove_taxonomy_description_field');

function remove_taxonomy_description_field_from_add_form()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.term-description-wrap').remove();
        });
    </script>
<?php
}
add_action('admin_footer', 'remove_taxonomy_description_field_from_add_form');

function wpdocs_my_search_form($form)
{

    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
                <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search" />
                <button type="button" id="searchsubmit" value="' . esc_attr__('Search') . '" class="customsearch"/>
                <img src="' . get_site_url() . '/wp-content/uploads/2024/07/search-icon.svg" /></button>
            </form>';
    return $form;
}
add_filter('get_search_form', 'wpdocs_my_search_form');

/*
*shortcode of Home Page main slider
*
*/
add_shortcode("main_slider", "main_slider");
function main_slider()
{
    $args = array(
        'post_type'      => 'slider',
        'posts_per_page' => -1,


    );
    $query = new WP_Query($args);
    ob_start();
    $slider = "";
    if ($query->have_posts()) :
        $slider .=  '<div class="owl-carousel owl-theme mainslider hero-slider">';
        $i = 0;
        while ($query->have_posts()) : $query->the_post();
            $bg_image_style = "";
            $main_title = get_field('main_title', get_the_ID());
            $main_title = $main_title != "" ? "<h2>" . $main_title . "</h2>" : "";
            $content = get_field('content', get_the_ID());
            $content = $content != "" ? '<div class="img-wrap">' . $content . '</div>' : "";
            $button_link = get_field('button_link', get_the_ID());
            $button_txt = $button_link != "" ? "<button class='hero-banner-link'>" . __("Find Out More", "wilsonhotwater") . "</button>" : "";
            $right_image = "";
            $right_image = get_field('right_image', get_the_ID());
            if (!empty($right_image)):
                $right_image = "<img src='" . $right_image['url'] . "' alt='" . $right_image['alt'] . "'/>";
            endif;
            $bg_class = get_field("background_class", get_the_ID()) != "" ? " " . get_field("background_class", get_the_ID()) : "";
            $bg_image = get_field('background_image', get_the_ID());
            if (!empty($bg_image)):
                $bg_image = $bg_image['url'];
                $bg_image_style = 'style="background-image: url(' . $bg_image . ');"' . " ";
            else:
                $bg_image = "";
            endif;
            $color = get_field('color', get_the_ID());
            $slider .= '<a href="' . $button_link . '">
                <div ' . $bg_image_style . 'class="item custom-container' . $bg_class . '">
                    <div class="hero-slider-wrap">
                        <div class="hero-content">
                            ' . $main_title . '
                            <div>
                                ' . $content . '
                            </div>
                            ' . $button_txt . '
                        </div>
                        <div class="hero-img">
                            ' . $right_image . '
                        </div>
                    </div>
                </div>
            </a>';
        endwhile;
        $slider .=  '</div>';
    endif;
    ob_get_clean();
    return $slider;
}

/*
* Get Stockist data for brand templates
*/
add_shortcode("stockist", "get_stockists");
function get_stockists()
{
    $stokists = get_field('stockists', get_the_ID());
    ob_start();
    $stockist = "";
    if ($stokists != "") {
        $stockist .= "<div id='stockistdata_main' class='stockistdata_main table_space_bottom'>";
        $stockist .= '<h4 class="stockist_title b-title">Stockists</h4>';
        $stockist .= "<div class='stokists'>" . $stokists . "</div>";
        $stockist .= "</div>";
    }
    ob_get_clean();
    return $stockist;
}
/*
* Brand speciality page sidebar
* Condition to show sidebar Specifications
* Condition to show technical Data
* Condition to show stokists
*/
add_shortcode("brandspeciality_sidebar", "brandspeciality_sidebar");
function brandspeciality_sidebar()
{
    ob_start();
    $file = get_field('specifications_file', get_the_ID());
    $specificationDes = get_field('specification_description', get_the_ID());
    $file_technicaldata = get_field('technical_data_file', get_the_ID());
    $technicalImg = get_field('technical_data_image', get_the_ID());
    $customisations = get_field('stockists', get_the_ID());
    $downloadBrochure = get_field('brochure', get_the_ID());
    $html = "";
    $html .= "<div class='product_sidebar_main'>";
    $html .= "<ul class='product_sidebar_main_list'>";
    if (!empty($file) || $specificationDes != ""):
        $html .= "<li class='product_specification product_sidebar_link'><a href='#specification_main'>" . __('Specifications', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($file_technicaldata) || !empty($technicalImg)):
        $html .= "<li class='product_technical product_sidebar_link'><a href='#technical_data_main'>" . __('Technical Data', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($customisations) || $customisations != ""):
        $html .= "<li class='customisations product_sidebar_link'><a href='#stockistdata_main'>" . __('Stockists', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($downloadBrochure)):
        $html .= "<li class='download_brochure download-btn-blue btn'><a href='" . $downloadBrochure['url'] . "' target='_blank'>" . __('Download Brochure PDF', 'wilsonhotwater') . "</a></li>";
    endif;
    $html .= "</ul>";
    $html .= "</div>";
    ob_get_clean();
    return $html;
}

/*
* Get content of Aqualux Heat Pump data for Aqualux templates
*/
add_shortcode("get_aqualux_heat_pump", "get_aqualux_heat_pump");
function get_aqualux_heat_pump()
{
    $aqualux_heat_pump = get_field('aqualux_heat_pump', get_the_ID());
    ob_start();
    $aqualux_content = "";
    if ($aqualux_heat_pump != "") {
        $aqualux_content .= "<div class='aqualux_heat_pump_content'>" . $aqualux_heat_pump . "</div>";
    }
    ob_get_clean();
    return $aqualux_content;
}
/*
* Brand speciality page sidebar
* Condition to show sidebar Specifications
* Condition to show technical Data
* Condition to show stokists
*/
add_shortcode("aqualux_sidebar", "aqualux_sidebar");
function aqualux_sidebar()
{
    ob_start();
    $file = get_field('specifications_file', get_the_ID());
    $specificationDes = get_field('specification_description', get_the_ID());
    $file_technicaldata = get_field('technical_data_file', get_the_ID());
    $technicalImg = get_field('technical_data_image', get_the_ID());
    $customisations = get_field('aqualux_heat_pump', get_the_ID());
    $downloadBrochure = get_field('brochure', get_the_ID());
    $user_manual = get_field('user_manual', get_the_ID());
    $html = "";
    $html .= "<div class='product_sidebar_main'>";
    $html .= "<ul>";
    // if(!empty($customisations) || $customisations != ""):
    //     $html .= "<li class='customisations product_sidebar_link'><a href='#'>".__('Aqualux Heat Pump Specs','wilsonhotwater')."</a></li>";
    // endif;
    if (!empty($file) || $specificationDes != ""):
        $html .= "<li class='customisations product_sidebar_link'><a href='#'>" . __('Aqualux Heat Pump Specs', 'wilsonhotwater') . "</a></li>";
    endif;
    if ((!empty($file_technicaldata) || !empty($technicalImg))):
        $html .= "<li class='product_specification product_sidebar_link'><a href='#'>" . __('Storage Tank Specs', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($user_manual)):
        $html .= "<li class='download_brochure download-btn-blue btn'><a href='$user_manual' target='_blank'>" . __('Heat Pumps User Manual', 'wilsonhotwater') . "</a></li>";
    endif;
    if (!empty($downloadBrochure)):
        $html .= "<li class='download_brochure download-btn-blue btn'><a href='" . $downloadBrochure['url'] . "' target='_blank'>" . __('Download Brochure PDF', 'wilsonhotwater') . "</a></li>";
    endif;
    $html .= "</ul>";
    $html .= "</div>";
    ob_get_clean();
    return $html;
}
/*
*
*BreadCrumbs Custom code
*/
// Function to generate breadcrumbs
function custom_breadcrumbs1()
{
    global $post;
    // Settings
    $separator = ' &gt; '; // Separator between breadcrumbs
    $home_bread  = "";
    $classIs = "";
    $pageIs = "";
    if (!empty($post)) {
        $pageIs = get_field("Select_Image_for_CTA", $post->ID);
    } else {
        return;
    }
    if ($pageIs == 'wilson-hot-water') {
        $home_bread = get_the_title(7); // Home text
    } else {
        $home_bread = get_the_title(227);
    }

    if (get_field("breadcrumbs", $post->ID) == "breadcrumbs_with_bg") {
        $classIs = " breadcrumbs-with-bg";
    } else {
        $classIs = " breadcrumbs-without-bg";
    }
    if (is_page('227')) {
        return;
    }

    // Breadcrumbs container
    $breadcrumbs = '<div class="breadcrumbs-wrap' . $classIs . '">';
    $breadcrumbs .= '<nav class="breadcrumbs container">';

    // Home link
    $breadcrumbs .= '<a href="' . home_url() . '">' . $home_bread . '</a>';
    global $post, $wp_query;

    // Check if not the homepage
    if (!is_front_page()) {
        // Add separator
        $breadcrumbs .= $separator;

        // If it is a single post
        if (is_single()) {
            $post_type_object = get_post_type_object(get_post_type());
            if ($post_type_object) {
                $breadcrumbs .= '<a href="' . get_post_type_archive_link(get_post_type()) . '">' . $post_type_object->labels->singular_name . '</a>';
                $breadcrumbs .= $separator;
            }
            $breadcrumbs .= get_the_title();
        }
        // If it is a category archive
        elseif (is_category()) {
            $breadcrumbs .= single_cat_title('', false);
        }
        // If it is a tag archive
        elseif (is_tag()) {
            $breadcrumbs .= single_tag_title('', false);
        }
        // If it is a custom taxonomy archive
        elseif (is_tax()) {
            $taxonomy = get_queried_object();
            // die();
            if ($taxonomy) {
                if (strpos($taxonomy->taxonomy, '_') !== false):
                    $taxonomyMain = ucwords(str_replace('_', ' ', $taxonomy->taxonomy));
                endif;
                $breadcrumbs .= '<a href="' . get_term_link($taxonomy) . '">' .  $taxonomyMain . '</a>';
                $breadcrumbs .= $separator;
                $breadcrumbs .= '<a href="' . get_term_link($taxonomy) . '">' . $taxonomy->name . '</a>';
            }
        }
        // If it is a page
        elseif (is_page()) {
            if ($post->post_parent) {
                $parent_id = $post->post_parent;
                $parents = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $parents[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $parents = array_reverse($parents);
                $breadcrumbs .= implode(' ' . $separator . ' ', $parents);
                $breadcrumbs .= ' ' . $separator . ' ';
            }
            $breadcrumbs .= get_the_title();
        }
    }

    $breadcrumbs .= '</nav>';
    $breadcrumbs .= '</div>';
    return $breadcrumbs;
}
function custom_breadcrumbs()
{
    global $post;
    $separator = ' &gt; ';
    $home_bread  = "";
    $extra = "";
    if (!empty($post)) {
        $pageIs = get_field("Select_Image_for_CTA", $post->ID);
        $term = get_queried_object();
        $taxonomy_id = $term->term_id;
        if (!empty($term)) {
            $extra = $term->name;
            if ($extra == "") {
                $extra = get_the_title();
            }
        }
    }
    if ($pageIs == 'wilson-hot-water') {
        $home_bread = get_the_title(7); // Home text
    } else {
        $home_bread = get_the_title(227);
    }
    $template_slug = get_page_template_slug(get_queried_object_id());

    if (is_tax('product_range')) {
        $template_slug = "template-specialitybrands.php";
    }
    switch ($template_slug) {
        case $template_slug == "template-specialitybrands.php":
            echo $home_bread . '' . $separator . '<a href="' . site_url() . '/#product-range">Product Range</a>' . $separator . ' ' . $extra;
            break;
        case is_page(12):
            echo "<a href='" . get_permalink(7) . "'>Commercial & Residential Wilson Hot Water</a>" . '' . $separator . get_the_title();
            break;
        case is_page(30):
            echo "<a href='" . get_permalink(227) . "'>Domestic Wilson Heat Pumps</a>" . '' . $separator . get_the_title();
            break;
        case $template_slug == "template-aqualuxproduct.php":
            echo "<a href='" . get_permalink(227) . "'>Domestic Wilson Heat Pumps</a>" . '' . $separator . get_the_title();
            break;
        default:
            # code...
            break;
    }
}
add_shortcode('breadcrumbs', 'custom_breadcrumbs');


/*
* Search functionality
* Product and pages 
*/
function custom_search_query($where, $wp_query)
{
    global $wpdb;

    if (! is_admin() && $wp_query->is_search() && $wp_query->is_main_query()) {
        // Sanitize the search term
        $search_term = esc_sql($wp_query->get('s'));

        // Define meta keys to search in
        $meta_keys = array(
            'specification_description',
            'customisations',
            'stockists',
            'aqualux_heat_pump'
        );

        // Build the WHERE clause for each meta key
        $meta_where_clauses = array();
        foreach ($meta_keys as $meta_key) {
            $meta_where_clauses[] = $wpdb->prepare(
                "EXISTS (
                    SELECT 1
                    FROM {$wpdb->postmeta}
                    WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                    AND {$wpdb->postmeta}.meta_key = %s
                    AND {$wpdb->postmeta}.meta_value LIKE %s
                )",
                $meta_key,
                '%' . $wpdb->esc_like($search_term) . '%'
            );
        }

        // Append all meta WHERE clauses to the main WHERE clause
        if (! empty($meta_where_clauses)) {
            $where .= ' OR (' . implode(' OR ', $meta_where_clauses) . ')';
        }

        // Ensure only published posts are included
        $where .= " AND {$wpdb->posts}.post_status = 'publish'";

        // Ensure distinct posts
        $where .= " GROUP BY {$wpdb->posts}.ID";
    }

    return $where;
}
add_filter('posts_where', 'custom_search_query', 10, 2);
function filter_search_query($query)
{
    // Check if it's a search query and not in the admin area
    if ($query->is_search() && !is_admin()) {
        // Ensure this modification only applies to the main query
        if ($query->is_main_query()) {
            // Modify the query to include specific post types
            $query->set('post_type', ['post', 'page', 'product']);
            // Optionally modify other query parameters
            $query->set('posts_per_page', 10); // Limit posts per page
        }
    }
}

// Hook the filter function to pre_get_posts
add_action('pre_get_posts', 'filter_search_query');

// Contact pg hot water shortcode

function contact_hot_water()
{
    ob_start();
    $head_ofc = get_field('head_office');
    $mail = get_field('mail');
    $ofc_hrs = get_field('office_hours');
    $ph_num_txt = get_field('phone_number');
    $ph_num_link = get_field('phone_number_link');
    $html = "";
    $html .= "<div class='contact-wrapper'>";
    $html .= "<div class='head-title'>";
    $html .= "<p><b>Head Office</b></p><p class='Head-ofc'>" . $head_ofc . "</p>";
    $html .= "</div>";
    $html .= "<div class='mail'>";
    $html .= "<p class='Head-ofc-mail'><a href='mailto:$mail'>" . $mail . "</a></p><p><a href='tel:$ph_num_link'>" . $ph_num_txt . "</a></p>";
    $html .= "</div>";
    $html .= "<div class='office-hrs-wrap'>";
    $html .= "<p><b>Office Hours</b></p><p class='working-hrs'>" . $ofc_hrs . "</p>";
    $html .= "</div>";
    $html .= "</div>";
    ob_get_clean();
    return $html;
}
add_shortcode('hot_water', 'contact_hot_water');

// Contact pg heat pumps shortcode

function contact_heat_pumps()
{
    ob_start();
    $head_office = get_field('head_ofc');
    $email = get_field('email');
    $html = "";
    $html .= "<div class='contact-wrapper'>";
    $html .= "<div class='head-title'>";
    $html .= "<p><b>Head Office</b></p><p class='Head-ofc'>" . $head_office . "</p>";
    $html .= "</div>";
    $html .= "<div class='mail'>";
    $html .= "<p class='Head-ofc-mail'><b><a href='mailto:$email'>" . $email . "</a></b></p>";
    $html .= "</div>";
    $html .= "</div>";
    ob_get_clean();
    return $html;
}
add_shortcode('heat_pumps', 'contact_heat_pumps');

function mytheme_customize_register($wp_customize)
{
    
    // Email Field
    $wp_customize->add_setting('my_custom_homepage_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email', // Ensures valid email format
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_email_control', array(
        'label'       => __('Contactpg Hot Water Email', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_email',
        'type'        => 'email',

    ));

    // Text Field 1
    $wp_customize->add_setting('my_custom_homepage_text1', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text1_control', array(
        'label'       => __('Contactpg Hot Water Title', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text1',
        'type'        => 'text',

    ));

    // Text Field 2
    $wp_customize->add_setting('my_custom_homepage_text2', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text2_control', array(
        'label'       => __('Contactpg Hot Water phone no.', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text2',
        'type'        => 'text',

    ));
    // Text Field 3
    $wp_customize->add_setting('my_custom_homepage_text3', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text3_control', array(
        'label'       => __('Contactpg Hot Water phone no. Link', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text3',
        'type'        => 'text',

    ));



    $wp_customize->add_setting('my_custom_homepage_email_heatpump', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email', // Ensures valid email format
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_email_heatpump_control', array(
        'label'       => __('Contactpg HeatPumps Email', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_email_heatpump',
        'type'        => 'email',

    ));

    // Text Field 1
    $wp_customize->add_setting('my_custom_homepage_text1_heatpump', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text1_heatpump_control', array(
        'label'       => __('Contactpg HeatPumps Title', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text1_heatpump',
        'type'        => 'text',

    ));

    // Text Field 2
    $wp_customize->add_setting('my_custom_homepage_text2_heatpump', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text2_heatpump_control', array(
        'label'       => __('Contactpg HeatPumps phone no.', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text2_heatpump',
        'type'        => 'text',

    ));
    // Text Field 3
    $wp_customize->add_setting('my_custom_homepage_text3_heatpump', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('my_custom_homepage_text3_heatpump_control', array(
        'label'       => __('Contactpg HeatPumps phone no. Link', 'wilsonhotwater'),
        'section'     => 'static_front_page',
        'settings'    => 'my_custom_homepage_text3_heatpump',
        'type'        => 'text',

    ));

    // STC price
    // $wp_customize->add_setting('my_custom_homepage_stc', array(
    //     'default'           => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    //     'capability'        => 'edit_theme_options',
    // ));
    // $wp_customize->add_control('my_custom_homepage_stc_control', array(
    //     'label'       => __('STC Price', 'wilsonhotwater'),
    //     'section'     => 'static_front_page',
    //     'settings'    => 'my_custom_homepage_stc',
    //     'type'        => 'number',

    // ));
}
add_action('customize_register', 'mytheme_customize_register');

function header_icons_title()
{
    ob_start();
    $homepage_email = get_theme_mod('my_custom_homepage_email', '');
    $homepage_text1 = get_theme_mod('my_custom_homepage_text1', '');
    $homepage_text2 = get_theme_mod('my_custom_homepage_text2', '');
    $homepage_text3 = get_theme_mod('my_custom_homepage_text3', '');
?>
    <div class="speak-now-form-wrap space-top-bottom bg-offwhite">
        <div class="container">

            <div class="product-page-title">
                <h4><?php echo $homepage_text1 ?></h4>
            </div>
            <div class="product-form-with-details">
                <div class="product-page-form">
                    <?php echo do_shortcode('[contact-form-7 id="235660a" title="Product Page Contact Form"]');  ?>
                </div>
                <div class="contact-details">
                    <h5> <a href="tel:<?php echo $homepage_text3 ?>"><?php echo $homepage_text2 ?></a></h5>
                    <h5 class="contact-mail"><a href="mailto:<?php echo $homepage_email ?>"><?php echo $homepage_email ?></a></h5>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('header_icons', 'header_icons_title');

// Get installer on basis of location randomly
//https://www.australiapostcodes.com/distance-between-postcodes to check exact distance between to pincodes

function findInstallerShortcode()
{
    ob_start();
    $html = "";
    $html .= "<div class='form-result-main'>";
    $html .= '<form id="locationlocator">
                            <div class="formfield">
                                <div class="loader"></div>
                                <label for="postcode">Enter your postcode</label>
                                <div class="postalcode-wrap">
                                    <input type="text" id="postcode" name="enterpostcode" class="postcode"/>
                                    <p style="display:none;">Please enter a valid 4-digit Australian postcode.</p>
                                </div>
                            </div>
                        <div class="btn outline-btn-blue"><button type="submit">Go</button></div>
                     </form>';
    $html .= "</div>";
    ob_get_clean();
    return $html;
}
add_shortcode("find-installer", "findInstallerShortcode");
add_action('wp_ajax_get_data_postcode', 'get_data_postcode');
add_action('wp_ajax_nopriv_get_data_postcode', 'get_data_postcode');
function get_data_postcode()
{
    $postcode = isset($_POST['postcode']) && $_POST['postcode'] != "" ? $_POST['postcode'] : 0;
    $html = "";
    $args = array(
        'post_type' => 'location', // Specify your custom post type
        'posts_per_page' => $posts_per_page, // -1 means all posts
        'orderby' => 'rand',
        'tax_query' => [
            [
                'taxonomy' => 'pincode', // Your custom taxonomy name
                'field' => 'slug', // Field to filter by (could also be 'term_id')
                'terms' => $postcode, // The term you want to filter by
            ],
        ],
    );
    $query = new WP_Query($args);
    $html .= '<div class="main-location-div">';
    $html .= "<span>Installer(s) near postcode " . $postcode . "</span>";
    $html .= '<ul class="ul-location-main">';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $address = get_post_meta(get_the_ID(), 'address', true);
            $phone = get_post_meta(get_the_ID(), 'phone', true);
            $fax = get_post_meta(get_the_ID(), 'fax', true);
            $email = get_post_meta(get_the_ID(), 'email', true);
            $website = get_post_meta(get_the_ID(), 'website', true);
			$slug = get_post_field('post_name', get_the_ID());
            $html .= '<li class="inner-location">';            
			$html .= '<p class="location-title">' . get_the_title() . '</p>';
            $html .= $address != "" ? '<p>' . $address . '</p>' : "";
            $html .= $phone != "" ? '<p><a href="tel:' . $phone . '">' . $phone . '</a></p>' : "";
            $html .= $fax != "" ? '<p><span>Fax</span>' . $fax . '</p>' : "";
           // $html .= $email != "" ? '<p><a href="mailto:' . $email . '">' . $email . '</a></p>' : "";
            $html .= $website != "" ? '<p><a href="' . $website . '" target="_blank">' . $website . '</a></p>' : "";
			$html .= '<a href="' . site_url('/store/' . $slug) . '" class="ul-location-main-btn ul-location-main-btn wp-block-button__link wp-element-button">Enquiry Now</a>';
            $html .= '</li>';
        }
        wp_reset_postdata();
    } else {
        $html .= "<p>Please contact Wilson Heat Pumps on <a href='tel:0397517788'>03 9751 7788</a> for your closest Dealer.</p>";
    }
    $html .= '</ul>';
    $html .= '</div>';
    $response['html'] = $html;
    wp_send_json_success($response);
    wp_die();
}

function is_mobile_device()
{
    return preg_match('/(android|iphone|ipad|ipod|blackberry|bb|playbook|windows phone|webos|opera mini|kindle|silk|mobile|palm)/i', $_SERVER['HTTP_USER_AGENT']);
}

//add_action("wp_footer",'rebate_calculator');
add_shortcode('rebate_calculator','rebate_calculator');
function rebate_calculator()
{
    
    ob_start();
    $html = "";
    $html.= '<div class="main_calculator">';
        $html .= '<div class="loader"></div>';
        $html.=  '<h3 class="calculator-title">Calculate your federal government rebate</h3>';
        $html.=  '<form name="rebatecalculator" class="rebatecalculator" method="post">';
    $html.=  '<div>
                <label for="postcode">Postcode:</label>
                <span>
                    <input type="text" id="postcode" name="postcode" required>
                    <p style="display:none;">Please enter a valid Australian postcode.</p>
                </span>    
            </div>';
            $html.=  '<div>
                    <label for="aqualuxSystem">Select the Aqualux Heat Pumps System you are thinking of installing:</label>
                    <select id="aqualuxSystem" name="aqualuxSystem" required>
                        <option value="">-- Please Select --</option>
                        <option value="WHPS-160A">WHPS-160A</option>
                        <option value="WHPS-250A">WHPS-250A</option>
                        <option value="WHPS-315A">WHPS-315A</option>
                    </select>
            </div>';
             $html .= '<div><label for="datepicker">What is the expected installation date of your system (dd/mm/yyyy)? </label><input type="text" id="datepicker" name="installationYear"></div>';
             $html.= '<div class="desclaimer-wrap"> <div><input type="checkbox" checked="checked" class="disclaimer-checkbox" value="1" id="rebate-disclaimer-chk" name="rebate-disclaimer">';
             $html .= '<label class="form-check-label" for="rebate-disclaimer"> I have read and understand the <a href="javascript:void(0)" class="disclaimer-cls">disclaimer</a>
                         </label></div><span class="dis-err"></span>';
             $html .= '</div>';
             $html.=  '<div class="calculator-btn-wrap">
                <input type="hidden" name="action" value="rebate_calculation">
                <button type="submit">Calculate</button>
            </div>';

            $html.=  '</form>';
            $html.=  '</div>';
            $html.="<div class='getstcdata_main'><h3>Rebate Results</h3><span class='getstcdata'></span></div>";
    ob_get_clean();
    return $html;
}
//rebate_calculation
add_action('wp_ajax_rebate_calculation', 'rebate_calculation');
add_action('wp_ajax_nopriv_rebate_calculation', 'rebate_calculation');
function rebate_calculation(){
    if(isset($_POST['postcode']) && $_POST['postcode']){
        $page_id = get_queried_object_id();
        $aqualuxSystem = strtolower($_POST['aqualuxSystem']);
        $postcode = $_POST['postcode'];
        $installDate = $_POST['installationYear'];
        $installDate = str_replace('/', '-', $installDate);
        $year = date('Y', strtotime($installDate));
        $getZone = getZone($postcode);
        $getLocationMultiplierFactor = getLocationMultiplierFactor($year); //deeming period given by client in sheet
        $getZone = $getZone != 0 ? "zone_".$getZone : 0;
        if($getZone == 0){
            $html = "Invalid pincode or Zone is not defined Please contact wilson";
            $response['html'] = $html;
            wp_send_json_success($response);
            wp_die();
        }
        $aqualuxSystem_group = get_field($aqualuxSystem,227);
        if (!empty($aqualuxSystem_group)) {
            $zoneValue = $aqualuxSystem_group[$getZone]; 
            $zoneValue = trim($zoneValue);
        }
        //zonevalue * deemingperiod = STC Count
        $stc_count = $zoneValue * $getLocationMultiplierFactor;
        if($stc_count){
            $globalStcValue = get_field('stc_price', 227);
            $rebateValue = floor($stc_count) * $globalStcValue;
            $rebateValue = number_format($rebateValue, 2);
        }
        $html = "<div>";
        $html .= "<div><p>STC value: <span>(based on ".floor($stc_count)." STCs at a value of $ ".$globalStcValue." each*)</span></p>$" . $rebateValue;//"ZONE=>".$getZone;
        $html .= "</div>";
        $html .= "<div class='total-wrap'> <div>In addition to the federal rebates calculated above, state rebates may also be available: <ul>
  <li>VIC residents may be eligible for VEECs, VEU Gas Subsidies and/or Solar Victoria rebates.</li>
  <li>NSW residents may be eligible for ESCs.</li>
  <li>QLD residents may be eligible for Climate Smart Energy Savers Rebate.</li>
  <li>The rebates above are examples only and may change at any time. Please refer to state and federal websites for more information.</li>
</ul>
</div></div>";
        $html .= "</div>";
        $response['html'] = $html;
        wp_send_json_success($response);
        wp_die();
        die();
        
    }
}

//Get zones by postcode for rebate calculator
//$file_path = get_stylesheet_directory() . '/assets/australianpostcode/19-12-2024-demo-6.xlsx';
function create_csv_index($csv_file) {
    $index = [];
    if (($handle = fopen($csv_file, "r")) !== false) {
        $headers = fgetcsv($handle); // Get column headers
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            
            // Check if 'id' exists; if so, append to an array
            $id = $data['id'];
            if (!isset($index[$id])) {
                $index[$id] = [];
            }
            $index[$id][] = $data;
        }
        fclose($handle);
    }
    return $index;
}
function search_csv($csv_file, $search_term) {
    $results = [];
    if (($handle = fopen($csv_file, "r")) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            // Check if the search term matches any column in the row
            if (in_array($search_term, $data)) {
                $results[] = $data; // Add row to results
            }
        }
        fclose($handle);
    }
    return $results;
}

//Get zone by post code
function getZone($postcode){
    $csv_file = get_stylesheet_directory() . '/assets/getZones/getzone-final.csv';
    $res = search_csv($csv_file,$postcode);
    if (!empty($res)) {
       if(!empty($res[0]) && isset($res[0][3])){
            return $res[0][3];
       }
    }
    return 0;
}
function getLocationMultiplierFactor($year) {
    // Example logic based on postcode
    $zone_multipliers = [
        '2021' => 1.0,
        '2022' => 0.9,
        '2023' => 0.8,
        '2024' => 0.7,
        '2025' => 0.6,
        '2026' => 0.5,
        '2027' => 0.4,
        '2028' => 0.3,
        '2029' => 0.2,
        '2030' => 0.1
    ];
    if (isset($zone_multipliers[$year])) {   
        return $zone_multipliers[$year];
    } 
    return 1.0; // Default multiplier
}
// Usage example
//add_filter( 'use_widgets_block_editor', '__return_false' );


/***
 * 19-12-2024
 */
//add_action('wp_footer','delete_all_location_posts');
 function delete_all_location_posts() {
    // Get all posts of the 'location' post type
    $posts = get_posts([
        'post_type'      => 'location', // Replace with your custom post type
        'posts_per_page' => -1,         // Get all posts
        'fields'         => 'ids',      // Only return post IDs
    ]);

    // Check if there are posts to delete
    if (empty($posts)) {
        return 'No posts found to delete.';
    }
    //echo count($posts);
    //Loop through and delete each post
    foreach ($posts as $post_id) {
        //echo $post_id.'<br/>';
        wp_delete_post($post_id, true); // Set true to force permanent deletion
    }

    return 'All location posts have been deleted successfully.';
}
//add_action('wp_footer','delete_all_terms_pincode');
function delete_all_terms_pincode(){
    if($_REQUEST['termdel'] == 1){
        $terms = get_terms([
            'taxonomy'   => 'pincode',  // The taxonomy name
            'hide_empty' => false,       // Set to false to include terms with no associated posts
        ]);
        if (is_wp_error($terms) || empty($terms)) {
            return 'No terms found to delete.';
        }
        foreach ($terms as $term) {
            //echo $term->term_id."<br/>";
            wp_delete_term($term->term_id, 'pincode');
        }
    }
    return 'All pincode terms and related location posts have been deleted.';
}

//add_action("wp_footer","importData1");
function importData1()
{   //3200
 
    if ($_REQUEST['im'] == 1) {
        $file_path = get_stylesheet_directory() . '/assets/australianpostcode/11022025/test-data-3.xlsx';
        import_locations_from_xls1($file_path);
    }
}
//Import data from spreadsheet to location
function import_locations_from_xls1($file_path)
{
    if (!file_exists($file_path)) {
        return 'File not found.';
    }
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file_path);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    $i = 0;
    echo "<pre>";
    print_r($rows);
    die();
    $pincode2 = "";
    foreach ($rows as $row) {
        $i++;
        if ($i == 1) {
            continue;
        }
        if ($row[1] == "") {
            break;
        }

        $pincode = sanitize_text_field($row[0]);
        if ($pincode != "") {
            $pincode2 = $pincode;
        }
        //echo $pincode2."<br>";
        $postname = sanitize_text_field($row[1]);
        $address_main = sanitize_text_field($row[2]);
        $phone = sanitize_text_field($row[3]);
        //$fax = sanitize_text_field($row[3]);    
        $email = sanitize_email($row[4]);
        //$website = esc_url_raw($row[4]);             
        //($post_title,$taxonomy_name="pincode", $taxonomy_term, $custom_post_type = 'location',$address_main ="",$phone="",$email="",$website="")
        //create_or_update_custom_post_with_taxonomy($post_title, $taxonomy_term,$address_main ="",$phone="",$email="",$website="",$taxonomy_name="pincode",$custom_post_type = 'location') 
        create_or_update_custom_post_with_taxonomy1($postname, $pincode2, $address_main, $phone, $email, $website = "");
        //  echo "<pre>";
        //  print_r($a);
        // die();
        // if (!is_wp_error($post_id)) {
        //     echo "Location imported: {$address}\n";
        // } else {
        //     echo "Failed to import: {$address}\n";
        // }
    }
}

function create_or_update_custom_post_with_taxonomy1($post_title, $taxonomy_term, $address_main = "", $phone = "", $email = "", $website = "", $taxonomy_name = "pincode", $custom_post_type = 'location')
{
     if (strpos($post_title, '&') !== false) {
        $post_title = str_replace('&', '&amp;', $post_title); // Replace & with &amp;
    }
    // Check if the post exists by title
    $existing_post = get_posts([
        'title' => $post_title,
        'post_type' => $custom_post_type,
        'post_status' => 'publish',
        'numberposts' => 1,
    ]);
    // Check if the term exists in the custom taxonomy
    $term = term_exists($taxonomy_term, $taxonomy_name);

    // If the post exists, update the post's taxonomy term
        // If post doesn't exist, create it
        $new_post_id = wp_insert_post([
            'post_title' => $post_title,
            'post_type' => $custom_post_type,
            'post_status' => 'publish',
        ]);
        if ($address_main != "") {
            update_post_meta($new_post_id, 'address', $address_main);
        }
        if ($phone != "") {
            update_post_meta($new_post_id, 'phone', $phone);
        }
        if ($email != "") {
            update_post_meta($new_post_id, 'email', $email);
        }
        if ($website != "") {
            update_post_meta($new_post_id, 'website', $website);
        }

        if (is_wp_error($new_post_id)) {
            return $new_post_id; // Return error if post creation fails
        }

        // If the term doesn't exist, create it
        if (!$term) {
            $term = wp_insert_term($taxonomy_term, $taxonomy_name);
            if (is_wp_error($term)) {
                return $term; // Return error if term creation fails
            }
        }

        // Assign the term to the newly created post
        wp_set_post_terms($new_post_id, [(int)$term['term_id']], $taxonomy_name, true);
    //}

    return true;
}















// 1. Add custom rewrite rule for /store/{slug}
add_action('init', function() {
    add_rewrite_rule('^store/([^/]+)/?$', 'index.php?store_slug=$matches[1]', 'top');
});

// 2. Register the 'store_slug' query var
add_filter('query_vars', function($vars) {
    $vars[] = 'store_slug';
    return $vars;
});
// 3. Handle fake /store/{slug} request and display location data dynamically
add_action('template_redirect', function() {
    $slug = get_query_var('store_slug');
    if (!$slug) return;

    $location = get_page_by_path($slug, OBJECT, 'location');

    if ($location) {
        // Make available to filters
        $GLOBALS['store_location_object'] = $location;

        setup_postdata($location);

        $title   = get_the_title($location);
        $address = get_post_meta($location->ID, 'address', true);
        $phone   = get_post_meta($location->ID, 'phone', true);
        $fax     = get_post_meta($location->ID, 'fax', true);
        $email   = get_post_meta($location->ID, 'email', true);
        $website = get_post_meta($location->ID, 'website', true);

        status_header(200);
        nocache_headers();

        get_header(); ?>

        <main class="store-page container"> 
			
			<h2 class="store-title">Contact <?php echo esc_html($title); ?></h2>
            <div class="store-form-wrapper">
                <?php echo do_shortcode('[contact-form-7 id="4e6b214" title="Store Form"]'); ?>
            </div>

			<!-- Location info here -->
<div class="store-location-info">
  <p><strong class="store-name"><?php echo esc_html($title); ?></strong></p>

  <?php if ($address): ?>
    <p class="store-address"><?php echo nl2br(esc_html($address)); ?></p>
  <?php endif; ?>

  <?php if ($phone): ?>
    <p class="store-phone"><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></p>
  <?php endif; ?>

  <?php if ($fax): ?>
    <p class="store-fax"><strong>Fax:</strong> <?php echo esc_html($fax); ?></p>
  <?php endif; ?>

  <?php if ($website): ?>
    <p class="store-website"><a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></p>
  <?php endif; ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationField = document.getElementById('location-id');
    if (locationField) {
        locationField.value = '<?php echo esc_js($location->ID); ?>';
    }
	
	const storeEmailField = document.getElementById('store_email');
    if (storeEmailField) {
        storeEmailField.value = '<?php echo esc_js($email); ?>';
    } 
});
</script>

        </main>

        <?php
        get_footer();  
        exit;
    } else {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include get_query_template('404');
        exit;
    }
});

add_filter('wpcf7_form_tag', function ($tag) {
    if ($tag['name'] !== 'your-postcode') return $tag;

    if (isset($GLOBALS['store_location_object']) && $GLOBALS['store_location_object'] instanceof WP_Post) {
        $location = $GLOBALS['store_location_object'];

        // Get the first pincode term
        $terms = wp_get_post_terms($location->ID, 'pincode');
        if (!is_wp_error($terms) && !empty($terms)) {
            $pincode = $terms[0]->name;

            // Set the default value directly
            $tag['values'] = [$pincode]; // ✅ sets <input value="1234">
        }
    }

    return $tag;
}, 10);

add_filter( 'wpcf7_form_elements', function( $content ) {
    if ( isset( $GLOBALS['store_location_object'] ) && $GLOBALS['store_location_object'] instanceof WP_Post ) {
        $location = $GLOBALS['store_location_object'];
        $address = get_post_meta($location->ID, 'address', true);

        if ( $address ) {
            if ( preg_match('/\b(VIC|NSW|ACT|NT|QLD|SA|TAS|VIC|WA)\b/i', $address, $matches) ) {
                $abbr = strtoupper($matches[1]);

                $state_map = [
                    'ACT'  => 'Australian Capital Territory',
                    'NSW'  => 'New South Wales',
                    'NT'   => 'Northern Territory',
                    'QLD'  => 'Queensland',
                    'SA'   => 'South Australia',
                    'TAS'  => 'Tasmania',
                    'VIC'  => 'Victoria',
                    'WA'   => 'Western Australia',
                ];

                if ( isset( $state_map[ $abbr ] ) ) {
                    $state_full = $state_map[ $abbr ];

                    // Use DOMDocument to replace selected option
                    libxml_use_internal_errors(true);

                    $dom = new DOMDocument();
                    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

                    $selects = $dom->getElementsByTagName('select');

                    foreach ($selects as $select) {
                        if ($select->getAttribute('name') === 'your-state') {
                            $options = $select->getElementsByTagName('option');
                            foreach ($options as $option) {
                                if ($option->getAttribute('value') === $state_full) {
                                    $option->setAttribute('selected', 'selected');
                                } else {
                                    $option->removeAttribute('selected');
                                }
                            }
                        }
                    }

                    // Save and return modified HTML
                    $body = $dom->getElementsByTagName('body')->item(0);
                    $new_content = '';
                    foreach ($body->childNodes as $child) {
                        $new_content .= $dom->saveHTML($child);
                    }

                    return $new_content;
                }
            }
        }
    }
    return $content;
});



add_action('after_setup_theme', 'create_followup_table');
function create_followup_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'followup_emails';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
		store_email VARCHAR(255) NOT NULL,
        location_id BIGINT(20) UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(50) DEFAULT 'pending',
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}




add_action('wpcf7_mail_sent', 'store_followup_email_to_db');

function store_followup_email_to_db($contact_form) {
    // Get form title to match
    $form_title = $contact_form->title();

    // Check if this is the correct form
    if ($form_title !== 'Store Form') {
        return;
    }

    $submission = WPCF7_Submission::get_instance();

    if ($submission) {
        $data = $submission->get_posted_data();

        // Adjust to match your actual form field name
        $email = isset($data['your-email']) ? sanitize_email($data['your-email']) : '';
        $location_id = isset($data['location-id']) ? intval($data['location-id']) : 0;
		$store_email = isset($data['store_email']) ? sanitize_email($data['store_email']) : '';

        if (!empty($email)) {
            global $wpdb;
            $table = $wpdb->prefix . 'followup_emails';

            // Check if this email already exists for this location
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE email = %s AND location_id = %d",
                $email, $location_id
            ));

            if (!$exists) {
                $wpdb->insert($table, [
                    'email'       => $email,
                    'location_id' => $location_id,
					'store_email' => $store_email,
                    'status'      => 'pending',
                ]);
            }
        }
    }
}




add_action('admin_menu', 'add_custom_form_data_menu');

function add_custom_form_data_menu() {
    global $wpdb;
     $table = $wpdb->prefix . 'followup_emails';

    // Get count of 'pending' entries
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'pending'" );

    $menu_title = 'Submissions';
    if ($count > 0) {
        $menu_title .= " ($count)";
    }

    add_menu_page(
        'Form Submissions',               // Page title
        $menu_title,                      // Menu title
        'manage_options',                 // Capability
        'custom-form-submissions',        // Menu slug
        'render_custom_submissions_page', // Callback
        'dashicons-feedback',             // Icon
        56                                // Position
    );
}


// Admin Page: View Recent Submissions and Stop Followup
 function render_custom_submissions_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'followup_emails';

    $per_page = 20;
    $current_page = max(1, isset($_GET['paged']) ? intval($_GET['paged']) : 1);
    $offset = ($current_page - 1) * $per_page;

    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $search_sql = $search_query ? $wpdb->prepare("WHERE s.email LIKE %s", '%' . $wpdb->esc_like($search_query) . '%') : '';

    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table s $search_sql");

    $results = $wpdb->get_results("
        SELECT s.*, p.post_title AS location_title
        FROM {$table} s
        LEFT JOIN {$wpdb->posts} p ON s.location_id = p.ID
        $search_sql
        ORDER BY s.created_at DESC
        LIMIT $per_page OFFSET $offset
    ");

    echo '<div class="wrap"><h1>Recent Submissions</h1>';

    // Search Form
    echo '<form method="get">
            <input type="hidden" name="page" value="custom-form-submissions" />
            <input type="search" name="s" value="' . esc_attr($search_query) . '" placeholder="Search email..." />
            <input type="submit" class="button" value="Search" />
          </form><br>';

    echo '<table class="widefat fixed striped">';
    echo '<thead>
            <tr>
                <th style="width: 50px;">S.No</th>
                <th>User Email</th>				
                <th>Store ID</th>				
                <th>Store Title</th>
				<th>Store Email</th>
                <th>Pincode</th>
                <th>Date</th>
                <th>Stop Mail</th>
            </tr>
          </thead><tbody>';

    if (!empty($results)) {
        $sno = $offset + 1;
        foreach ($results as $row) {
            $pincode = '—';

            if (!empty($row->location_id)) {
                $terms = wp_get_post_terms($row->location_id, 'pincode');
                if (!is_wp_error($terms) && !empty($terms)) {
                    $pincode = esc_html($terms[0]->name);
                }
            }

            $formatted_date = !empty($row->created_at) ? date('d M Y, h:i A', strtotime($row->created_at)) : '—';

            $button_html = ($row->status === 'stopped') 
                ? '<span style="color: red;">Stopped</span>' 
                : '<button class="button stop-mail-btn" data-id="' . esc_attr($row->id) . '">Stop Mail</button>';

            echo '<tr>';
            echo '<td>' . esc_html($sno++) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';			
            echo '<td>' . esc_html($row->location_id ?? '—') . '</td>';			
            echo '<td>' . esc_html($row->location_title ?? '—') . '</td>';
			echo '<td>' . esc_html($row->store_email) . '</td>';
            echo '<td>' . $pincode . '</td>';
            echo '<td>' . $formatted_date . '</td>';
            echo '<td>' . $button_html . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">No submissions found.</td></tr>';
    }

    echo '</tbody></table>';

    // Pagination
    $total_pages = ceil($total_items / $per_page);
    if ($total_pages > 1) {
        $base_url = remove_query_arg('paged');
        $page_links = paginate_links([
            'base'      => add_query_arg('paged', '%#%'),
            'format'    => '',
            'prev_text' => '« Prev',
            'next_text' => 'Next »',
            'total'     => $total_pages,
            'current'   => $current_page,
        ]);
        echo '<div class="tablenav"><div class="tablenav-pages">' . $page_links . '</div></div>';
    }

    echo '</div>'; // .wrap
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.stop-mail-btn').on('click', function() {
                const button = $(this);
                const id = button.data('id');

                if (!confirm('Are you sure you want to stop further emails for this user?')) return;

                button.prop('disabled', true).text('Stopping...');

                $.post(ajaxurl, {
                    action: 'stop_followup_mail',
                    record_id: id,
                }, function(response) {
                    if (response.success) {
                        button.replaceWith('<span style="color:red;">Stopped</span>');
                    } else {
                        alert(response.data || 'Something went wrong');
                        button.prop('disabled', false).text('Try Again');
                    }
                });
            });
        });
    </script>

    <?php
}


// AJAX handler to stop mail
add_action('wp_ajax_stop_followup_mail', 'handle_stop_followup_mail');
function handle_stop_followup_mail() {
    global $wpdb;
    $table = $wpdb->prefix . 'followup_emails';
    $id = intval($_POST['record_id']);

    $entry = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
    if (!$entry) {
        wp_send_json_error('Entry not found');
    }

    $wpdb->update($table, ['status' => 'stopped'], ['id' => $id]);
    wp_send_json_success();
}



add_action('send_weekly_followup_emails', 'run_weekly_followup_emails');
function run_weekly_followup_emails() {
    global $wpdb;
    $table = $wpdb->prefix . 'followup_emails';

    // Fetch records with status not stopped (pending or others)
    $records = $wpdb->get_results("
        SELECT * FROM $table 
        WHERE status != 'stopped'
    ");

    foreach ($records as $record) {
        $store_email = sanitize_email($record->store_email);
        $customer_email = sanitize_email($record->email);
        if (!is_email($store_email)) continue;

        // Get location name from post ID
        $location_name = get_the_title($record->location_id);
        $location_id = intval($record->location_id);

        // Format created_at datetime
        $created_at = date_i18n('j F, Y \a\t g:i A', strtotime($record->created_at));


        $subject = 'Following up on your recent customer enquiry';

        $message = "Hello,

You are receiving this email as a follow-up regarding a customer enquiry submitted earlier.

Store ID: {$location_id}
Store Name: {$location_name}
Customer Email: {$customer_email}
Enquiry Date & Time: {$created_at}

If you have any questions or need further assistance, please feel free to get in touch.

Thank you for your attention!

Best regards,
Wilson Hot Water
https://wilsonhotwater.com.au";

        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        $sent = wp_mail($store_email, $subject, $message, $headers);

        if ($sent) {
            $wpdb->update($table, ['status' => 'sent'], ['id' => $record->id]);
        }
    }
}


// Endpoint to trigger manually via server cron
add_action('init', function () {
    if (isset($_GET['run_store_mail_cron']) && $_GET['run_store_mail_cron'] === '1') {
        do_action('send_weekly_followup_emails');
        exit('Cron triggered.');
    }
});



 
