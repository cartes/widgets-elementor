<?php
/**
 * SVG Preview Graphics for Layout Options
 *
 * @package Elementor_Post_Layout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Layout 1 SVG Preview
 * Layout: Large post left + 4 small posts right (ad in position 4)
 *
 * @return string SVG markup
 */
function elpl_get_layout_1_svg() {
	return '<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
		<!-- Large post left -->
		<rect x="5" y="5" width="140" height="190" fill="#e3f2fd" stroke="#1976d2" stroke-width="2" rx="4"/>
		<text x="75" y="105" font-family="Arial" font-size="12" fill="#1976d2" text-anchor="middle">Post Grande</text>
		
		<!-- Small post 1 -->
		<rect x="155" y="5" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="225" y="30" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 1</text>
		
		<!-- Small post 2 -->
		<rect x="155" y="53" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="225" y="78" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 2</text>
		
		<!-- Small post 3 -->
		<rect x="155" y="101" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="225" y="126" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 3</text>
		
		<!-- Google Ad position 4 -->
		<rect x="155" y="149" width="140" height="46" fill="#fff3e0" stroke="#e65100" stroke-width="2" rx="4" stroke-dasharray="5,5"/>
		<text x="225" y="174" font-family="Arial" font-size="10" fill="#e65100" text-anchor="middle" font-weight="bold">Google Ads</text>
	</svg>';
}

/**
 * Get Layout 2 SVG Preview
 * Layout: 4 small posts left (ad in position 4) + large post right
 *
 * @return string SVG markup
 */
function elpl_get_layout_2_svg() {
	return '<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
		<!-- Small post 1 -->
		<rect x="5" y="5" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="75" y="30" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 1</text>
		
		<!-- Small post 2 -->
		<rect x="5" y="53" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="75" y="78" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 2</text>
		
		<!-- Small post 3 -->
		<rect x="5" y="101" width="140" height="43" fill="#f3e5f5" stroke="#7b1fa2" stroke-width="2" rx="4"/>
		<text x="75" y="126" font-family="Arial" font-size="10" fill="#7b1fa2" text-anchor="middle">Post 3</text>
		
		<!-- Google Ad position 4 -->
		<rect x="5" y="149" width="140" height="46" fill="#fff3e0" stroke="#e65100" stroke-width="2" rx="4" stroke-dasharray="5,5"/>
		<text x="75" y="174" font-family="Arial" font-size="10" fill="#e65100" text-anchor="middle" font-weight="bold">Google Ads</text>
		
		<!-- Large post right -->
		<rect x="155" y="5" width="140" height="190" fill="#e3f2fd" stroke="#1976d2" stroke-width="2" rx="4"/>
		<text x="225" y="105" font-family="Arial" font-size="12" fill="#1976d2" text-anchor="middle">Post Grande</text>
	</svg>';
}
