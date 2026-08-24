<?php
/**
 * CARTO Theme — template-parts/global/header-cart.php
 * Bouton panier de l'en-tête.
 *
 * Ne s'affiche que si WooCommerce est actif : le thème doit rester utilisable
 * sur un site sans boutique.
 *
 * Le balisage est produit par carto_header_cart_html(), et non écrit ici, car
 * WooCommerce le redemande en AJAX à chaque ajout au panier pour rafraîchir le
 * compteur sans recharger la page (voir functions.php, « fragments »). Une
 * seule source évite que le bouton rendu au chargement et celui rendu en AJAX
 * divergent.
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'carto_header_cart_html' ) ) {
	echo carto_header_cart_html();
}
