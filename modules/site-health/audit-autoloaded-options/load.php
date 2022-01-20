<?php
/**
 * Module Name: Audit Autoloaded Options
 * Description: Adds Autoloaded options checker in Site Health checks.
 * Experimental: No
 *
 * @package performance-lab
 * @since 1.0.0
 */

/**
 * Adds test to site health.
 *
 * @since 1.0.0
 *
 * @param array $tests Site Health Tests.
 * @return array
 */
function perflab_aao_add_autoloaded_options_test( $tests ) {
	$tests['direct']['autoloaded_options'] = array(
		'label' => esc_html__( 'Autoloaded options', 'performance-lab' ),
		'test'  => 'perflab_aao_autoloaded_options_test',
	);
	return $tests;
}
add_filter( 'site_status_tests', 'perflab_aao_add_autoloaded_options_test' );

/**
 * Callback for autoloaded_options test.
 *
 * @since 1.0.0
 *
 * @return array
 */
function perflab_aao_autoloaded_options_test() {

	$autoloaded_options_size  = perflab_aao_autoloaded_options_size();
	$autoloaded_options_count = perflab_aao_autoloaded_options_count();

	$result = array(
		'label'       => esc_html__( 'Autoloaded options', 'performance-lab' ),
		'status'      => 'good',
		'badge'       => array(
			'label' => esc_html__( 'Performance', 'performance-lab' ),
			'color' => 'blue',
		),
		'description' => sprintf(
		/* translators: 1: Number of autoloaded options. 2.Autoloaded options size. */
			'<p>' . esc_html__( 'The amount of %1$s autoloaded options (size: %2$s) in options table is acceptable.', 'performance-lab' ) . '</p>',
			$autoloaded_options_count,
			size_format( $autoloaded_options_size )
		),
		'actions'     => '',
		'test'        => 'autoloaded_options',
	);

	/**
	 * Hostings can modify its limits depending on their own requirements.
	 */
	$limit = apply_filters( 'perflab_aao_autoloaded_options_limit_size_in_bytes', 800000 );

	if ( $autoloaded_options_size > $limit ) {
		$result['status']         = 'critical';
		$result['badge']['color'] = 'red';
		$result['description']    = sprintf(
		/* translators: 1: Number of autoloaded options. 2.Autoloaded options size. */
			'<p>' . esc_html__( 'Your website uses %1$s autoloaded options (size: %2$s). Try to reduce the number of autoloaded options or performance will be affected.', 'performance-lab' ) . '</p>',
			$autoloaded_options_count,
			size_format( $autoloaded_options_size )
		);
		$result['description'] = apply_filters( 'perflab_aao_autoloaded_options_limit_description', $result['description'] );

		/**
		 * Hostings can add actions to take to reduce size of autoloaded options linking to their own guides.
		 */
		$result['actions'] = apply_filters( 'perflab_aao_autoloaded_options_action_to_perform', 'How to solve it' );
	}

	return $result;
}

/**
 * Autoloaded options counter.
 *
 * @since 1.0.0
 *
 * @return int
 */
function perflab_aao_autoloaded_options_count() {
	return count( wp_load_alloptions() );
}

/**
 * Calculate total amount of autoloaded data.
 *
 * @since 1.0.0
 *
 * @return int autoloaded data in bytes.
 */
function perflab_aao_autoloaded_options_size() {
	global $wpdb;
	$autoloaded_options_size = $wpdb->get_row( 'SELECT SUM(LENGTH(option_value)) FROM ' . $wpdb->prefix . 'options WHERE autoload = \'yes\'', ARRAY_N );
	return current( $autoloaded_options_size );
}
