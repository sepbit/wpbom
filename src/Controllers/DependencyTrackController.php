<?php
/**
 * Wpbom - WordPress integration with OWASP CycloneDX and Dependency Track
 * Copyright (C) 2021  Sepbit
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package Wpbom
 */

namespace Sepbit\WpBom\Controllers;

/**
 * DependencyTrackController
 */
class DependencyTrackController {
	/**
	 * Send
	 *
	 * @param string $server Dependency Track server.
	 * @param string $api_key Dependency Track API key.
	 * @param string $project Dependency Track project.
	 * @param array  $bom Dependency Track bom.
	 */
	public static function send( $server, $api_key, $project, $bom ) {
		$bom = json_encode( $bom );

		$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL            => $server . '/api/v1/bom',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'POST',
				CURLOPT_POSTFIELDS     => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"project\"\r\n\r\n$project\r\n"
					. "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"bom\"\r\n\r\n$bom\r\n-----011000010111000001101001--\r\n",
				CURLOPT_HTTPHEADER     => array(
					'Content-Type: multipart/form-data; boundary=---011000010111000001101001',
					"X-API-Key: $api_key",
				),
			)
		);
		$response = curl_exec( $curl );
		$httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		$err      = curl_error( $curl );
		curl_close( $curl );

		if ( 200 !== $httpcode ) {
			return $httpcode;
		}
		if ( $err ) {
			return $err;
		}
		return $response;
	}

	/**
	 * Auto update
	 */
	public static function auto_update() {
		if ( isset( $wpbom['_wpbom_disable'] ) && 'on' === $wpbom['_wpbom_disable'] ) {
			return 'Auto update disable';
		}
		return self::update();
	}

	/**
	 * Update
	 */
	public static function update() {
		$wpbom = get_option( 'wpbom' );

		if ( ! isset( $wpbom['_wpbom_server'] ) || empty( $wpbom['_wpbom_server'] ) ) {
			return 'Server not found';
		}

		if ( ! isset( $wpbom['_wpbom_api_key'] ) || empty( $wpbom['_wpbom_api_key'] ) ) {
			return 'API Key not found';
		}

		if ( ! isset( $wpbom['_wpbom_project'] ) || empty( $wpbom['_wpbom_project'] ) ) {
			return 'Project not found';
		}

		$bom = \Sepbit\WpBom\Controllers\CycloneDXController::bom();
		return self::send(
			$wpbom['_wpbom_server'],
			$wpbom['_wpbom_api_key'],
			$wpbom['_wpbom_project'],
			$bom
		);
	}
}
