<?php
/**
 * Tests for webp-uploads module.
 *
 * @package performance-lab
 * @group   webp-uploads
 */

class get_dominant_color_GD_Tests extends WP_UnitTestCase {

	function set_up() {
		parent::set_up(); // TODO: Change the autogenerated stub

		add_filter( 'wp_image_editors', static function (){ return array('WP_Image_Editor_GD'); } );
	}

	/**
	 * Test if the function returns the correct color.
	 * @dataProvider provider_get_dominant_color
	 *
	 * @covers ::get_dominant_color_GD
	 */
	function test_get_dominant_color( $image_path, $expected_color) {
		$attachment_id = $this->factory->attachment->create_upload_object( $image_path );

		$wp_Dominant_Color = new WP_Dominant_Color();
		$this->assertEquals( $expected_color, $wp_Dominant_Color->get_dominant_color( $attachment_id ) );
	}

	/**
	 * Data provider for test_get_dominant_color_GD.
	 *
	 * @return array
	 */
	function provider_get_dominant_color() {
		return array(

			'jpg' => array(
				'image_path' => TESTS_PLUGIN_DIR . '/tests/testdata/modules/images/leafs.jpg',
				'expected_color' => '283c23',
			),
			'webp' => array(
				'image_path' => TESTS_PLUGIN_DIR . '/tests/testdata/modules/images/balloons.webp',
				'expected_color' => 'c0bbb9',
			),
		);
	}
}
