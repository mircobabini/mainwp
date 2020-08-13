<?php
/**
 * MainWP Site Info Widget
 *
 * Build the Child Site Info Widget.
 *
 * @package     MainWP/Dashboard
 */

namespace MainWP\Dashboard;

/**
 * Class MainWP_Site_Info
 *
 * Grab Child Site info and build widget.
 */
class MainWP_Site_Info {

	/**
	 * Method get_class_name()
	 *
	 * @return string __CLASS__ Class name.
	 */
	public static function get_class_name() {
		return __CLASS__;
	}

	/**
	 * Method render()
	 *
	 * @return mixed render_site_info()
	 */
	public static function render() {
		self::render_site_info();
	}

	/**
	 * Method render_site_info()
	 *
	 * Grab Child Site Info and render.
	 */
	public static function render_site_info() {
		$current_wpid = MainWP_System_Utility::get_current_wpid();
		if ( empty( $current_wpid ) ) {
			return;
		}

		$website = MainWP_DB::instance()->get_website_by_id( $current_wpid );

		$website_info = json_decode( MainWP_DB::instance()->get_website_option( $website, 'site_info' ), true );

		if ( is_array( $website_info ) ) {
			$code        = $website->http_response_code;
			$code_string = MainWP_Utility::get_http_codes( $code );
			if ( ! empty( $code_string ) ) {
				$code .= ' - ' . $code_string;
			}
			$website_info['last_status'] = $code;
		}

		$child_site_info = array(
			'wpversion'     => __( 'WordPress Version', 'mainwp' ),
			'debug_mode'    => __( 'Debug Mode', 'mainwp' ),
			'phpversion'    => __( 'PHP Version', 'mainwp' ),
			'child_version' => __( 'MainWP Child Version', 'mainwp' ),
			'memory_limit'  => __( 'PHP Memory Limit', 'mainwp' ),
			'mysql_version' => __( 'MySQL Version', 'mainwp' ),
			'ip'            => __( 'Server IP', 'mainwp' ),
			'group'         => __( 'Groups', 'mainwp' ),
			'last_status'   => __( 'Last Check Status', 'mainwp' ),
		);

		self::render_info( $website, $website_info, $child_site_info );
	}

	/**
	 * Render Sites Info.
	 *
	 * @param object $website Website object.
	 * @param array  $website_info Website data.
	 * @param array  $child_site_info Website info to display.
	 */
	public static function render_info( $website, $website_info, $child_site_info ) {
		?>
		<h3 class="ui header handle-drag">
			<?php esc_html_e( 'Site Info', 'mainwp' ); ?>
			<div class="sub header"><?php esc_html_e( 'Basic child site system information', 'mainwp' ); ?></div>
		</h3>

		<div class="ui section hidden divider"></div>

		<div class="mainwp-widget-site-info">
			<?php
			/**
			 * Site info widget top
			 *
			 * Fires at the top of the Site Info widget on the Individual site overview page.
			 *
			 * @since 4.0
			 */
			do_action( 'mainwp_site_info_widget_top' );
			?>
			<?php
			if ( ! is_array( $website_info ) || ! isset( $website_info['wpversion'] ) ) {
				?>
				<h2 class="ui icon header">
					<i class="info circle icon"></i>
					<div class="content">
						<?php esc_html_e( 'No info found!', 'mainwp' ); ?>
						<div class="sub header"><?php esc_html_e( 'Site information could not be found. Make sure your site is properly connected and syncs correctly.', 'mainwp' ); ?></div>
					</div>
				</h2>
				<?php
			} else {

				$website_info['group'] = ( '' == $website->wpgroups ? 'None' : $website->wpgroups );

				?>
			<table class="ui celled striped table">
				<tbody>
				<?php
				/**
				 * Site info table top
				 *
				 * Fires at the top of the Site Info table in Site Info widget on the Individual site overview page.
				 *
				 * @since 4.0
				 */
				do_action( 'mainwp_site_info_table_top' );
				?>
				<?php
				foreach ( $child_site_info as $index => $title ) {
					$val = '';
					if ( isset( $website_info[ $index ] ) ) {
						if ( 'debug_mode' == $index ) {
							$val = 1 == $website_info[ $index ] ? 'Enabled' : 'Disabled';
						} else {
							$val = $website_info[ $index ];
						}
					}
					?>
						<tr>
						<td><?php echo esc_html( $title ); ?></td>
						<td><?php echo esc_html( $val ); ?></td>
						</tr>
				<?php } ?>
				<?php
				/**
				 * Site info table bottom
				 *
				 * Fires at the bottom of the Site Info table in Site Info widget on the Individual site overview page.
				 *
				 * @since 4.0
				 */
				do_action( 'mainwp_site_info_table_bottom' );
				?>
				</tbody>
			</table>
			<?php } ?>
			<?php
			/**
			 * Site info widget bottom
			 *
			 * Fires at the bottom of the Site Info widget on the Individual site overview page.
			 *
			 * @since 4.0
			 */
			do_action( 'mainwp_site_info_widget_bottom' );
			?>
		</div>
		<?php
	}

}
