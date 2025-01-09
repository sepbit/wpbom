=== WpBom ===
Contributors: vitoranguia
Donate link: https://liberapay.com/vitoranguia/
Tags: BOM, CycloneDX, Dependency Track
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.3.0
Requires PHP: 8.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress integration with OWASP CycloneDX and Dependency Track

== Description ==

This package is compatible with [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards), [PSR-4](https://www.php-fig.org/psr/psr-4).

WordPress integration with OWASP [CycloneDX](https://cyclonedx.org) and [Dependency Track](https://dependencytrack.org)

Features:

* Automatic BOM submission to OWASP Dependency Track
* Manual BOM submission to OWASP Dependency Track
* Download BOM JSON in OWASP CycloneDX format

== Frequently Asked Questions ==

Add [CPE](https://nvd.nist.gov/products/cpe) from BOM

`
add_filter(
	'wpbom_bom',
	function( $bom ) {
		foreach ( $bom['components'] as $key => $component ) {
			if ( 'woocommerce' === $component['name'] ) {
                $bom['components'][ $key ]['cpe'] = 'cpe:2.3:a:woocommerce:woocommerce:' . $component['version'] . ':*:*:*:*:wordpress:*:*';
			}
		}
		return $bom;
	}
);
`

We are building a feature to automate this

Remove component from BOM

`
add_filter(
	'wpbom_bom',
	function( $bom ) {
		foreach ( $bom['components'] as $key => $component ) {
			if ( 'woocommerce' === $component['name'] ) {
				unset( $bom['components'][ $key ] );
			}
		}
		return $bom;
	}
);
`

Add component from BOM

`
add_filter(
	'wpbom_bom',
	function( $bom ) {
		global $wpdb;
		$db_server_info      = explode( '-', $wpdb->db_server_info() );
		$bom['components'][] = array(
			'type'     => 'application',
			'bom-ref'  => 'pkg:deb/debian/' . strtolower( $db_server_info[2] ) . '@' . $db_server_info[1],
			'name'     => strtolower( $db_server_info[2] ),
			'version'  => $db_server_info[1],
			'purl'     => 'pkg:deb/debian/' . strtolower( $db_server_info[2] ) . '@' . $db_server_info[1],
			'licenses' => array(
				array(
					'license' => array(
						'id' => 'GPL-2.0-or-later',
					),
				),
			),
		);
		return $bom;
	}
);
`

== Installation ==

This project uses [PHP](https://php.net) and [Composer](https://getcomposer.org).

$ cd wp-content/plugins/
$ git clone https://gitlab.com/sepbit/wpbom.git
$ cd wpbom
$ composer update --no-dev

== Screenshots ==

1. See option page

== Changelog ==

= 1.3.0 =
* Add PHP 8.4

= 1.2.0 =
* Add WordPress as component
* Fix some bugs

= 1.1.0 =
* Add CPE example
* Fix Copyright
* Remove purl

= 1.0.2 =
* Add icon

= 1.0.0 =
* First release!
