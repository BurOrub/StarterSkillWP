<?php
/**
 * Applica a un sito con Starter Theme un "profilo" di opzioni scritto in JSON (le risposte dell'intervista del nuovo progetto).
 *
 * Uso (dalla cartella del sito WordPress, con WP-CLI; "prova" non scrive niente):
 *   wp eval-file applica-profilo.php profilo.json            applica
 *   wp eval-file applica-profilo.php profilo.json prova     mostra cosa farebbe, senza scrivere
 *
 * Formato del profilo:
 *   {
 *     "mods":    { "starter_header_style": "pills", "starter_footer_style": "style-2" },   opzioni del tema (theme_mod)
 *     "options": { "blogname": "Nome del sito", "blogdescription": "Motto" },              opzioni di WordPress
 *     "palette": [ { "nome": "Principale", "valore": "#0a2540" }, ... ],                   tavolozza (fino a 10 colori)
 *     "modules": [ "portfolio", "immobili" ]                                               moduli da accendere
 *   }
 *
 * Contenuti (logo, foto, testi delle pagine, menu) NON passano di qui: li carica l'utente.
 * Font di Google: si sceglie il font in "mods" (es. "starter_font1_family": "google:Montserrat"); a fine applicazione lo script
 * li scarica sul sito (cartella uploads/starter-fonts) e dice se è andata a buon fine.
 * Le proprietà con Desktop/Tablet/Mobile (dimensioni dei testi) hanno tre chiavi: ..._font_size_desktop, ..._tablet, ..._mobile.
 *
 * Ogni valore è controllato sul controllo che lo ha registrato: chiave sconosciuta, scelta non ammessa o formato sbagliato = RIFIUTATO
 * (e scritto nel resoconto), mai applicato. Prima si prova sempre con la parola "prova".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$profile_file = isset( $args[0] ) ? $args[0] : '';
$dry          = in_array( 'prova', (array) $args, true );

if ( ! $profile_file || ! is_readable( $profile_file ) ) {
	WP_CLI::error( 'Indica il file JSON del profilo.' );
}

$profile = json_decode( (string) file_get_contents( $profile_file ), true );

if ( ! is_array( $profile ) ) {
	WP_CLI::error( 'Il profilo non è un JSON valido.' );
}

wp_set_current_user( 1 );
require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';

$manager = new WP_Customize_Manager();
$GLOBALS['wp_customize'] = $manager;
do_action( 'customize_register', $manager );

$done     = array();
$rejected = array();

/* Controllo che ha registrato un'opzione (anche quelle a tre valori Desktop/Tablet/Mobile). */
$controls_by_setting = array();

foreach ( $manager->controls() as $control_obj ) {
	foreach ( $control_obj->settings as $setting_obj ) {
		$controls_by_setting[ $setting_obj->id ] = $control_obj;
	}
}

/* Controlla un valore sul controllo registrato; restituisce array( valore pulito ) oppure null se non ammesso. */
$check = static function ( $id, $value ) use ( $manager, $controls_by_setting ) {
	$setting = $manager->get_setting( $id );
	$control = $controls_by_setting[ $id ] ?? null;

	if ( ! $control || ! $setting ) {
		return null;
	}

	switch ( $control->type ) {
		case 'checkbox':
			return array( (bool) $value );

		case 'select':
		case 'radio':
			$choices = array_map( 'strval', array_keys( (array) $control->choices ) );

			return in_array( (string) $value, $choices, true ) ? array( (string) $value ) : null;

		case 'number':
			return is_numeric( $value ) ? array( $value + 0 ) : null;

		case 'url':
			return array( esc_url_raw( (string) $value ) );

		case 'color':
			return preg_match( '/^#[0-9a-fA-F]{3,8}$/', (string) $value ) ? array( (string) $value ) : null;

		case 'textarea':
			return array( sanitize_textarea_field( (string) $value ) );

		default:
			if ( ! is_scalar( $value ) ) {
				return null;
			}

			// Controlli speciali (font, testi responsive…): vale la pulizia dell'opzione stessa; se la cambia, il valore non era ammesso.
			$clean = $setting->sanitize( $value );

			return null !== $clean && (string) $clean === (string) $value ? array( (string) $value ) : null;
	}
};

$set_mod = static function ( $id, $value ) use ( $check, $dry, &$done, &$rejected, $manager ) {
	$clean = $check( $id, $value );

	if ( null === $clean ) {
		$rejected[] = $id . ' = ' . wp_json_encode( $value );

		return;
	}

	$setting = $manager->get_setting( $id );
	$before  = 'option' === $setting->type ? get_option( $id ) : get_theme_mod( $id, $setting->default );

	if ( ! $dry ) {
		'option' === $setting->type ? update_option( $id, $clean[0] ) : set_theme_mod( $id, $clean[0] );
	}

	$done[] = $id . ': ' . wp_json_encode( $before ) . ' → ' . wp_json_encode( $clean[0] );
};

// Opzioni del tema.
foreach ( (array) ( $profile['mods'] ?? array() ) as $id => $value ) {
	$set_mod( (string) $id, $value );
}

// Opzioni di WordPress ammesse.
foreach ( (array) ( $profile['options'] ?? array() ) as $id => $value ) {
	if ( in_array( $id, array( 'blogname', 'blogdescription' ), true ) ) {
		$before = get_option( $id );

		if ( ! $dry ) {
			update_option( $id, sanitize_text_field( (string) $value ) );
		}

		$done[] = $id . ': ' . wp_json_encode( $before ) . ' → ' . wp_json_encode( $value );
	} else {
		$rejected[] = 'options.' . $id . ' (non ammessa)';
	}
}

// Tavolozza: i colori vanno negli slot 1…10 (nome + valore).
foreach ( array_values( (array) ( $profile['palette'] ?? array() ) ) as $i => $color ) {
	$n = $i + 1;

	if ( $n > 10 ) {
		$rejected[] = 'palette: oltre 10 colori';
		break;
	}

	if ( ! empty( $color['nome'] ) ) {
		$set_mod( "starter_named_color_{$n}_name", $color['nome'] );
	}

	if ( ! empty( $color['valore'] ) ) {
		$set_mod( "starter_named_color_{$n}_value", $color['valore'] );
	}
}

// Moduli.
foreach ( (array) ( $profile['modules'] ?? array() ) as $module ) {
	$set_mod( 'starter_module_' . sanitize_key( $module ), true );
}

// Google Fonts: scaricati sul sito (come fa il Personalizza al salvataggio).
$fonts_report = '';

if ( ! $dry && ( isset( $profile['mods']['starter_font1_family'] ) || isset( $profile['mods']['starter_font2_family'] ) ) ) {
	if ( function_exists( 'starter_local_fonts_enabled' ) && starter_local_fonts_enabled() ) {
		$ok           = starter_local_fonts_download();
		$fonts_report = $ok && starter_local_fonts_ready() ? 'Google Fonts scaricati sul sito (uploads/starter-fonts).' : 'DOWNLOAD DEI FONT NON RIUSCITO: restano serviti da Google; riprovare o controllare la rete.';
	} else {
		$fonts_report = 'Download in locale dei font spento (Prestazioni): i font restano serviti da Google.';
	}
}

if ( ! $dry ) {
	delete_option( 'rewrite_rules' );
}

WP_CLI::log( ( $dry ? 'PROVA (niente scritto)' : 'APPLICATO' ) . ': ' . count( $done ) . " modifiche, " . count( $rejected ) . ' rifiutate.' );

foreach ( $done as $line ) {
	WP_CLI::log( '  ✓ ' . $line );
}

if ( $fonts_report ) {
	WP_CLI::log( '  ' . $fonts_report );
}

foreach ( $rejected as $line ) {
	WP_CLI::warning( 'RIFIUTATO ' . $line );
}
