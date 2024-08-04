<?php

/**
 * The system functionality of the plugin.
 *
 * @link       https://lucavisciola.com
 * @since      1.0.0
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/system
 */

/**
 * The system functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how init system calls.
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/system
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente_System {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private string $version;

	/**
	 * Initialize the class and set its properties.
     *
	 * @since   1.0.0
	 * @param   string $version    The version of this plugin.
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the Custom post type.
	 *
	 * @since    1.0.0
	 */
    public function register_custom_post_type(): void {

        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Transparent Administration', 'Post Type General Name' ),
            'singular_name'       => _x( 'Transparent Administration', 'Post Type Singular Name' ),
            'menu_name'           => __( 'Transparency', 'melasistema-amministrazione-trasparente' ),
            'all_items'           => __( 'All Voices', 'melasistema-amministrazione-trasparente' ),
            'view_item'           => __( 'View Voice', 'melasistema-amministrazione-trasparente' ),
            'add_new_item'        => __( 'Add New Voice', 'melasistema-amministrazione-trasparente' ),
            'add_new'             => __( 'Add New', 'melasistema-amministrazione-trasparente' ),
            'edit_item'           => __( 'Edit Voice', 'melasistema-amministrazione-trasparente' ),
            'update_item'         => __( 'Update Voice', 'melasistema-amministrazione-trasparente' ),
            'search_items'        => __( 'Search Voices', 'melasistema-amministrazione-trasparente' ),
            'not_found'           => __( 'No Voices Found', 'melasistema-amministrazione-trasparente' ),
            'not_found_in_trash'  => __( 'No Voices found in Trash', 'melasistema-amministrazione-trasparente' ),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label'               => __( 'Transparency', 'melasistema-amministrazione-trasparente' ),
            'description'         => __( 'All Transparencies', 'melasistema-amministrazione-trasparente' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title','editor'),
            'taxonomies'          => array( 'mela_trans_section' ),
            'rewrite' => array( 'slug' => __( 'transparent-administration', 'melasistema-amministrazione-trasparente' ) ),
            'hierarchical'        => true,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => false,
            'menu_position'       => 3,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'menu_icon' 		  => 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path color="rgba(240,246,252,.6)" d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 1 1 1.591 1.59l-1.83 1.83h2.16M2.99 15.745h1.125a1.125 1.125 0 0 1 0 2.25H3.74m0-.002h.375a1.125 1.125 0 0 1 0 2.25H2.99" /></svg>' ),
        );

        // Registering your Custom Post Type
        register_post_type( 'mela_trans_admin', $args );
    }

	/**
	 * Register the Custom Taxonomy.
	 *
	 * @since    1.0.0
	 */
    public function register_custom_taxonomy(): void {

        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Sections', 'taxonomy general name', 'melasistema-amministrazione-trasparente' ),
            'singular_name'     => _x( 'Section', 'taxonomy singular name', 'melasistema-amministrazione-trasparente' ),
            'search_items'      => __( 'Search Sections', 'melasistema-amministrazione-trasparente' ),
            'all_items'         => __( 'All Sections', 'melasistema-amministrazione-trasparente' ),
            'parent_item'       => __( 'Parent Section', 'melasistema-amministrazione-trasparente' ),
            'parent_item_colon' => __( 'Parent Section:', 'melasistema-amministrazione-trasparente' ),
            'edit_item'         => __( 'Edit Section', 'melasistema-amministrazione-trasparente' ),
            'update_item'       => __( 'Update Section', 'melasistema-amministrazione-trasparente' ),
            'add_new_item'      => __( 'Add New Section', 'melasistema-amministrazione-trasparente' ),
            'new_item_name'     => __( 'New Section Name', 'melasistema-amministrazione-trasparente' ),
            'menu_name'         => __( 'Sections', 'melasistema-amministrazione-trasparente' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'trans-admin/section' ),
        );

        register_taxonomy( 'mela_trans_section', array( 'mela_trans_admin' ), $args );
    }

    /**
     * Add the default taxonomy data
     * NOT IN USE - WE START WITH EMPTY TAXONOMY
     * Reference https://docs.italia.it/italia/designers-italia/design-comuni-docs/it/versione-corrente/modello-sito-comunale/amministrazione-trasparente.html
     * @since    1.0.0
     */
    public function add_default_it_taxonomy_data(): void {
        $defaultTaxonomies = [
            'Disposizioni generali',
            'Organizzazione',
            'Consulenti e Collaboratori',
            'Personale',
            'Bandi di concorso',
            'Performance',
            'Enti Controllati',
            'Attività e Procedimenti',
            'Provvedimenti',
            'Controlli sulle Imprese',
            'Bandi di gara e contratti',
            'Sovvenzioni contributi sussidi vantaggi economici',
            'Bilanci',
            'Beni immobili e gestione patrimonio',
            'Controlli e rilievi sull’Amministrazione',
            'Servizi erogati',
            'Pagamenti dell’Amministrazione',
            'Opere Pubbliche',
            'Pianificazione e governo del territorio',
            'Informazioni Ambientali',
            'Interventi straordinari e di emergenza',
            'Altri contenuti',
        ];
        foreach ($defaultTaxonomies as $termName) {
            // Controlla se il termine esiste già
            $term_exists = term_exists( $termName, 'mela_trans_section' );

            // Se il termine non esiste, crealo
            if ( 0 === (int) $term_exists ) {
                $result = wp_insert_term(
                    $termName,
                    'mela_trans_section',
                    [
                        'description' => 'Default term for ' . $termName,
                    ]
                );

                // Gestisci gli errori come prima
                if (is_wp_error($result)) {
                    error_log('Error creating term: ' . $result->get_error_message());
                }
            }
        }
    }

}
