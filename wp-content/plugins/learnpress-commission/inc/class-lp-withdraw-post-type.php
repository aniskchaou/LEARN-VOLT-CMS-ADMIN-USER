<?php
/**
 * Learnpress thdrawal Custom post type class.
 *
 * @author   ThimPress
 * @package  LearnPress/Commission/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Withdrawal_Post_Type' ) ) {
	/**
	 * Class LP_Withdrawal_Post_Type.
	 */
	final class LP_Withdrawal_Post_Type extends LP_Abstract_Post_Type {

		/**
		 * @var null
		 */
		protected static $_instance = null;

		/**
		 * @var array
		 */
		public static $metaboxes = array();

		/**
		 * LP_Withdrawal_Post_Type constructor.
		 *
		 * @param $post_type
		 */
		public function __construct( $post_type ) {
			parent::__construct( $post_type );

			add_action( 'template_redirect', array( $this, 'hidden_front_end' ) );
		}

		/**
		 * Admin scripts.
		 *
		 * @return $this|void
		 */
		public function admin_scripts() {
			if ( in_array( get_post_type(), array( LP_WITHDRAW_CPT ) ) ) {
				wp_enqueue_style( 'lp_commission_manage', LP_ADDON_COMMISSION_URI . 'assets/css/admin.css', array(), LP_ADDON_COMMISSION_VER );
				wp_enqueue_script( 'lp_withdrawal', LP_ADDON_COMMISSION_URI . 'assets/js/admin.js', array( 'jquery' ), LP_ADDON_COMMISSION_VER );
			}
		}

		/**
		 * Register announcement post type.
		 *
		 * @return array|bool
		 */
		public function register() {
			$labels = array(
				'name'                  => _x( 'Withdrawals', 'Post Type General Name', 'learnpress-commission' ),
				'singular_name'         => _x( 'Withdrawal', 'Post Type Singular Name', 'learnpress-commission' ),
				'menu_name'             => __( 'Withdrawal', 'learnpress-commission' ),
				'name_admin_bar'        => __( 'Post Type', 'learnpress-commission' ),
				'archives'              => __( 'Item Archives', 'learnpress-commission' ),
				'parent_item_colon'     => __( 'Parent Item:', 'learnpress-commission' ),
				'all_items'             => __( 'Withdrawals', 'learnpress-commission' ),
				'add_new_item'          => __( 'Add New Item', 'learnpress-commission' ),
				'add_new'               => __( 'Add New', 'learnpress-commission' ),
				'new_item'              => __( 'New Item', 'learnpress-commission' ),
				'edit_item'             => __( 'Edit Item', 'learnpress-commission' ),
				'update_item'           => __( 'Update Item', 'learnpress-commission' ),
				'view_item'             => __( 'View Item', 'learnpress-commission' ),
				'search_items'          => __( 'Search Item', 'learnpress-commission' ),
				'not_found'             => __( 'Not found', 'learnpress-commission' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'learnpress-commission' ),
				'featured_image'        => __( 'Featured Image', 'learnpress-commission' ),
				'set_featured_image'    => __( 'Set featured image', 'learnpress-commission' ),
				'remove_featured_image' => __( 'Remove featured image', 'learnpress-commission' ),
				'use_featured_image'    => __( 'Use as featured image', 'learnpress-commission' ),
				'insert_into_item'      => __( 'Insert into item', 'learnpress-commission' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'learnpress-commission' ),
				'items_list'            => __( 'Items list', 'learnpress-commission' ),
				'items_list_navigation' => __( 'Items list navigation', 'learnpress-commission' ),
				'filter_items_list'     => __( 'Filter items list', 'learnpress-commission' ),
			);

			$args = array(
				'label'               => __( 'Withdrawal', 'learnpress-commission' ),
				'description'         => __( 'Withdraw', 'learnpress-commission' ),
				'labels'              => $labels,
				'supports'            => array( '' ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => 'learn_press',
				'menu_position'       => 10,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'post',
				'capabilities'        => array(
					'edit_post'          => 'edit_post',
					'read_post'          => 'read_post',
					'delete_posts'       => 'delete_posts',
					'edit_posts'         => 'edit_posts',
					'edit_others_posts'  => 'edit_others_posts',
					'publish_posts'      => 'publish_posts',
					'read_private_posts' => 'read_private_posts',
					'create_posts'       => 'do_not_allow',
				),
				'map_meta_cap'        => true
			);

			return $args;
		}

		/**
		 * Add columns to admin manage commission page.
		 *
		 * @param  array $columns
		 *
		 * @return array
		 */
		public function columns_head( $columns ) {

			$columns['status'] = __( 'Publisher', 'learnpress-commission' );

			return $columns;
		}

		/**
		 * Displaying the content of extra columns.
		 *
		 * @param $column
		 * @param $post_id
		 */
		public function columns_content( $column, $post_id = 0 ) {

			switch ( $column ) {
				case 'status':
					$all_stats = LP_Withdrawal::get_all_status();
					$status    = get_post_meta( $post_id, 'lp_status', true );

					if ( empty( $status ) ) {
						$status = 'pending';
					}
					echo $all_stats[ $status ];
					break;
			}
		}

		/**
		 * Redirect in frontend.
		 */
		public function hidden_front_end() {
			if ( is_singular( LP_WITHDRAW_CPT ) ) :
				$url = get_bloginfo( 'url' );

				wp_redirect( esc_url_raw( $url ), 301 );
				exit();
			endif;
		}

		/**
		 * Withdraw meta box.
		 */
		public static function withdraw_details() {
			lp_commission_admin_view( 'withdraw-details.php' );
		}

		/**
		 * Withdraw action meta box.
		 *
		 * @param $post
		 */
		public static function withdraw_actions( $post ) {
			lp_commission_admin_view( 'withdraw-actions.php' );
		}

		/**
		 * Instance.
		 *
		 * @return LP_Withdrawal_Post_Type|null
		 */
		public static function instance() {
			if ( ! self::$_instance ) {
				self::$_instance = new self( LP_WITHDRAW_CPT );
			}

			return self::$_instance;
		}
	}
}

$commission_post_type = LP_Withdrawal_Post_Type::instance();

$commission_post_type->add_meta_box( 'withdraw_details', __( 'Details', 'learnpress-commission' ), 'withdraw_details', 'normal', 'high' )
                     ->add_meta_box( 'submitdiv', __( 'Withdraw Actions', 'learnpress-commission' ), 'withdraw_actions', 'side', 'high' );;
