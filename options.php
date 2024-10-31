<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		wp_die( __( 'Directly access this file you can not!' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'Sufficient permissions to access this page you have not.' ) );
	}
	$options = get_option(
			'travel.gayguide.options',
			array(
					'search'       => '',
					'lang'         => 'en',
					'color'        => '#e91e63',
					'extra_style'  => '#fw-list a {box-shadow: none;}',
					'agb_accepted' => false
			)
	);
	$changed = false;

	// AGB have been accepted
	if ( isset( $_POST['bow_to_rules'] ) && ! empty( $_POST['bow_to_rules'] ) ) {
		$options['agb_accepted'] = true;
		$changed = true;
	}

	if (
			isset($_POST['pridecal_search']) &&
			strtolower(trim( $_POST['pridecal_search'] )) != strtolower( trim( $options['search'] ) )
	) {
		$options['search'] = sanitize_text_field( strtolower(trim( $_POST['pridecal_search'] ) ));
		$changed = true;
	}

	if (
			isset($_POST['pridecal_lang']) &&
			strtolower(trim( $_POST['pridecal_lang'] )) != strtolower( trim( $options['lang'] ) )
	) {
		$options['lang'] = sanitize_text_field( strtolower(trim( $_POST['pridecal_lang'] ) ));
		$changed = true;
	}

	if (
			isset($_POST['pridecal_color']) &&
			strtolower( trim( $_POST['pridecal_color'] ) ) != strtolower( trim( $options['color'] ) )
	) {
		$options['color'] = sanitize_text_field( strtolower( trim( $_POST['pridecal_color'] ) ) );
		$changed = true;
	}

	if (
			isset($_POST['pridecal_extra_style']) &&
			strtolower( trim( $_POST['pridecal_extra_style'] ) ) != strtolower( trim( $options['extra_style'] ) )
	) {
		$options['extra_style'] = sanitize_text_field( strtolower( trim( $_POST['pridecal_extra_style'] ) ) );
		$changed = true;
	}

	update_option( 'travel.gayguide.options', $options );
?>
<div class="wrap">
	<h1>Einstellungen › Pridecalendar</h1>

	<?php if ( ! $options['agb_accepted'] ) { ?>
		<div class="error notice">
			<p>
				<strong>
					<?php _e( 'Bitte bestätige unsere Regeln (siehe unten).' ) ?>
				</strong>
			</p>
		</div>
	<?php } ?>

	<?php if ( $changed ) { ?>
		<div class="updated notice is-dismissible">
			<p>
				<strong>
					<?php _e( 'Änderungen gespeichert.' ) ?>
				</strong>
			</p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
	<?php } ?>

	<p>
		<img style="border: 2px solid black; max-width: 320px" align="right"
		     src="<?php echo plugins_url( '/wordpress_example.png', __FILE__ ); ?>" alt="so könnte es aussehen"/>
		Das Plugin erlaubt Dir den Spartacus Pride Kalender auf jeder beliebigen Seite deines Blogs einzubinden.
	</p>

	<?php if ( ! $options['agb_accepted'] ) { ?>

		<div class="card">
			<h2>Rechtliches.</h2>
			<ul>
				<li>Um den Pride Kalender von Spartacus auf Deiner Seite anzeigen zu dürfen, darfst Du keine Gebühren für
					den Zugang zur Liste der Prides erheben.
				</li>
				<li>Auch darf keine Werbung auf der Seite mit dem Kalender geschaltet sein (wenn Du auf anderen Seiten deiner
					Webpräsenz Werbung hast, ist das natürlich dein Ding).
				</li>
				<li>
					Du darfst unser Plugin nicht auf Seiten veröffentlichen, die dem lokalen Recht zu Folge nicht legal sind.
				</li>
			</ul>
			<ul>
				<li>
					<form method="post">
						<input type="checkbox" name="bow_to_rules" id="rules">
						<label for="rules">
							<?php printf(__( 'Ich habe die %s und obige Regeln verstanden und halte mich dran.'), '<a href="https://spartacus.gayguide.travel/blog/spartacus-international-gayguide-app-privacy-policy/" target="_blank">Datenschutzerklärung</a>'); ?>
						</label>
						<hr/>
						<input type="submit" value="<?php _e('Speichern'); ?>" class="button button-primary">
					</form>
				</li>
			</ul>
		</div>

	<?php } else { ?>

		<div class="card">
			<p>
				Einfach den Shortcode <code>[pridecal_list]</code> in den Text eines Posts oder einer Seite einfügen.
				<hr>
				Du kannst im Suchfeld schon etwas vorab eintragen und auch die Ausgabesprache ändern:
				<ul>
					<li>Pride Events für Berlin:
						<code>[pridecal_list search='Berlin']</code>
					</li>
					<li>Pride Events auf Spanisch mit 'CSD' im Namen:
						<code>[pridecal_list search='CSD' lang='es']</code>
					</li>
				</ul>
			</p>
		</div>

		<div class="card">
			<h2>
				<?php _e( 'Standardwerte' ); ?>
			</h2>
			<p>Du kannst einen Standardwert für das Suchfeld hinterlegen. Dieser wird verwendet,
				wenn der Shortcode keine Vorgabe enthält.</p>
			<form method="post">
				<ul>
					<li>
						<label for="pridecal_search">Suchfeld (optional)</label>
						<input type="text" id="pridecal_search" name="pridecal_search" value="<?php echo $options['search']; ?>">
					</li>
					<li>
						<label for="pridecal_lang">Sprache (optional)</label>
						<select id="pridecal_lang" name="pridecal_lang">
							<?php foreach (json_decode(PRIDECAL_LANGS) as $k=>$v) {?>
								<option value="<?php echo $k; ?>" <?php echo ($options['lang']===$k)?'selected':''; ?>><?php echo $v; ?></option>
							<?php }?>
						</select>
					</li>
					<li>
						<label for="pridecal_color">Farbe (optional)</label>
						<input type="color" id="pridecal_color" name="pridecal_color" value="<?php echo $options['color']; ?>">
					</li>
					<li>
						<input type="submit" name="submit" id="pridecal_submit" value="<?php _e( 'Speichern' ); ?>"
						       class="button button-primary">
					</li>
				</ul>
			</form>
		</div>

		<div class="card">
			<h2>
				<?php _e( 'Stylesheet anpassen' ); ?>
			</h2>
			<p>
				Da Wordpress Themes mitunter sehr unterschiedliche Basisstile haben, kommt es immer mal wieder vor, dass die Ausgabe dieses Plugins nicht optimal aussieht.
				<br />
				In diesem Fall kannst Du hier zusaetzliche CSS Regeln eintragen, die für die Liste angewendet werden sollen.
			</p>
			<form method="post">
				<ul>
					<li>
						<label for="pridecal_extra_style">CSS Regeln (optional)</label>
					</li>
					<li>
						<textarea name="pridecal_extra_style" id="pridecal_extra_style" style="width: 100%" rows="10"><?php
								echo str_ireplace(array( '}', '} '),"}\n",$options['extra_style']);
						?></textarea>
					</li>
					<li>
						<input type="submit" name="submit" id="pridecal_submit" value="<?php _e( 'Speichern' ); ?>"
						       class="button button-primary">
					</li>
				</ul>
			</form>
		</div>

	<?php }
	if ( $options['agb_accepted'] ) { ?>
		<script type="text/javascript">
			jQuery('#pridecal_needs_configuration').remove();
		</script>
	<?php } ?>
</div>
