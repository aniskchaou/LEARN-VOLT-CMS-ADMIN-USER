<div class="thim-nav-wrapper <?php echo get_theme_mod( 'thim_header_size', 'default' ) == 'full_width' ? 'header_full' : 'container'; ?>">
	<div class="row">
		<div class="navigation col-sm-12">
			<div class="tm-table">
				<div class="width-logo table-cell sm-logo">
					<?php
					do_action( 'thim_logo' );
//					do_action( 'thim_sticky_logo' );
					?>
				</div>
                <nav class="width-navigation table-cell table-right">
                    <?php get_template_part( 'inc/header/main-menu' ); ?>
                </nav>

                <div class="menu-mobile-effect navbar-toggle" data-effect="mobile-effect">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>

			</div>
		</div>
	</div>
</div>