<?php
/**
 * CARTO Theme — Walker du menu principal.
 *
 * POURQUOI UN WALKER
 *
 * WordPress sait empiler des <ul> imbriqués, mais il ne sait rien du panneau
 * déroulant dessiné dans la maquette : celui-ci porte un intitulé de rubrique
 * (« // Plateforme »), un chevron devant chaque lien, et un compteur à droite.
 * Aucun de ces trois éléments n'est déductible en CSS seul :
 *
 *   - l'intitulé reprend le libellé du PARENT, qu'un <ul> enfant ignore ;
 *   - le compteur vient du nombre d'éléments de la catégorie liée, une donnée
 *     de base et non de présentation.
 *
 * D'où ce walker, qui les produit au moment où le menu est construit.
 *
 * Le survol lui-même reste du CSS : ouvrir un panneau n'a pas besoin de
 * JavaScript, et un menu qui dépend d'un script ne s'ouvre pas quand celui-ci
 * échoue à charger.
 *
 * @package Carto
 */

defined( 'ABSPATH' ) || exit;

class Carto_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Libellé du dernier élément rencontré à chaque profondeur.
	 *
	 * start_lvl() est appelé APRÈS le start_el() du parent : au moment où l'on
	 * ouvre le panneau, le libellé à afficher est déjà passé. On le met donc
	 * de côté en chemin.
	 *
	 * @var array<int,string>
	 */
	private $libelle_parent = [];

	/**
	 * Ouvre un niveau de menu : le panneau déroulant.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="sub-menu">';

		// L'intitulé de rubrique : décoratif, donc masqué aux lecteurs d'écran
		// qui viennent déjà de lire le libellé du parent juste au-dessus.
		if ( 0 === $depth && ! empty( $this->libelle_parent[ $depth ] ) ) {
			$output .= '<li class="sub-menu__eyebrow" aria-hidden="true">// '
				. esc_html( $this->libelle_parent[ $depth ] ) . '</li>';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	/**
	 * Rend un élément de menu.
	 *
	 * Reprend la mécanique du walker de WordPress (classes, identifiant,
	 * attributs de lien, filtres de titre) pour ne pas casser les extensions
	 * qui s'y branchent, et n'ajoute que le balisage de la maquette.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$this->libelle_parent[ $depth ] = $item->title;

		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
		$class_names = join( ' ', array_map( 'esc_attr', $classes ) );

		$id_attr = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id_attr = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';

		$a_pour_enfants = in_array( 'menu-item-has-children', $classes, true );

		$output .= '<li' . $id_attr . ' class="' . $class_names . '">';

		$atts = [
			'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => ! empty( $item->target ) ? $item->target : '',
			'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
			'href'   => ! empty( $item->url ) ? $item->url : '',
		];

		// Annonce le panneau aux technologies d'assistance. aria-expanded reste
		// à « false » : c'est le CSS qui ouvre au survol, et mentir sur l'état
		// vaudrait mieux que rien, mais un script s'en chargera si besoin.
		if ( $a_pour_enfants && 0 === $depth ) {
			$atts['aria-haspopup'] = 'true';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributs = '';
		foreach ( $atts as $attr => $valeur ) {
			if ( '' === $valeur || false === $valeur ) {
				continue;
			}
			$valeur = ( 'href' === $attr ) ? esc_url( $valeur ) : esc_attr( $valeur );
			$attributs .= ' ' . $attr . '="' . $valeur . '"';
		}

		$titre = apply_filters( 'the_title', $item->title, $item->ID );
		$titre = apply_filters( 'nav_menu_item_title', $titre, $item, $args, $depth );

		$interieur = '';

		// Chevron devant les liens du panneau : repère visuel de la maquette,
		// sans valeur pour un lecteur d'écran.
		if ( $depth > 0 ) {
			$interieur .= '<span class="nav-chevron" aria-hidden="true">&rsaquo;</span>';
		}

		$interieur .= '<span class="nav-label">' . $titre . '</span>';

		$compteur = self::compteur( $item );
		if ( '' !== $compteur ) {
			$interieur .= '<span class="nav-count" aria-hidden="true">' . esc_html( $compteur ) . '</span>';
		}

		// Le caret n'apparaît que sur les entrées de premier niveau qui ouvrent
		// réellement un panneau : ailleurs il promettrait une action qui n'existe pas.
		if ( $a_pour_enfants && 0 === $depth ) {
			$interieur .= '<span class="nav-caret" aria-hidden="true">&#9660;</span>';
		}

		$html = ( isset( $args->before ) ? $args->before : '' )
			. '<a' . $attributs . '>' . $interieur . '</a>'
			. ( isset( $args->after ) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $html, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}

	/**
	 * Compteur affiché à droite d'un lien de panneau.
	 *
	 * Uniquement pour les entrées qui pointent vers une catégorie ou une
	 * catégorie de produit : le nombre d'éléments qu'elle contient est une
	 * information utile avant de cliquer. Formaté sur deux chiffres, comme la
	 * maquette (« 06 », « 04 »), parce que des largeurs égales s'alignent.
	 *
	 * @param object $item Élément de menu.
	 * @return string Chaîne vide si l'entrée n'est pas un terme comptable.
	 */
	private static function compteur( $item ) {
		if ( empty( $item->type ) || 'taxonomy' !== $item->type ) {
			return '';
		}

		$terme = get_term( (int) $item->object_id, $item->object );
		if ( ! $terme || is_wp_error( $terme ) || $terme->count < 1 ) {
			return '';
		}

		return ( $terme->count < 10 )
			? '0' . (int) $terme->count
			: (string) (int) $terme->count;
	}
}
