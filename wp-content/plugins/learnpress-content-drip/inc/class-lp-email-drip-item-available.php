<?php
/**
 * LP_Email_Drip_Item_Available
 *
 * @author   ThimPress
 * @package  LearnPress/Content-Drip/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Email_Drip_Item_Available' ) ) {

	/**
	 * Class LP_Email_Drip_Item_Available
	 */
	class LP_Email_Drip_Item_Available extends LP_Email {

		/**
		 * LP_Email_Drip_Item_Available constructor.
		 */
		public function __construct() {

			$this->id          = 'drip-item-available';
			$this->title       = __( 'Drip Item Available', 'learnpress-content-drip' );
			$this->description = __( 'Send this email to user when an item was blocked become available.', 'learnpress-co-instructor' );


			$this->default_subject = __( '[{{site_title}}] Item available for learning', 'learnpress-co-instructor' );
			$this->default_heading = __( '{{item_name}} has become available.', 'learnpress-co-instructor' );

			$this->template_base  = '';
			$this->template_plain = 'emails/plain/drip-item-available.php';
			$this->template_html  = 'emails/drip-item-available.php';

			parent::__construct();
		}
	}
}

return new LP_Email_Drip_Item_Available();
?>