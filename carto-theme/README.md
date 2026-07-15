# CARTO — Thème WordPress

Thème AppSec "tactical HUD" : fond navy profond, signal teal, composants animés intégrés.

---

## Installation

1. Copiez le dossier `carto-theme/` dans `/wp-content/themes/`
2. Dans WordPress admin → **Apparence → Thèmes** → activez **CARTO**
3. Allez dans **Apparence → Menus** pour créer vos menus `primary` et `footer`
4. Configurez la page d'accueil : **Réglages → Lecture → Page d'accueil statique**

---

## Structure des fichiers

```
carto-theme/
├── style.css                 ← Déclaration du thème (obligatoire WP)
├── functions.php             ← Supports, enqueue, shortcodes, widgets
├── header.php                ← Topbar avec navigation
├── footer.php                ← Pied de page
├── front-page.php            ← Page d'accueil vitrine (hero + composants animés)
├── page.php                  ← Pages statiques
├── single.php                ← Articles de blog
├── archive.php               ← Catégories, tags, auteurs
├── index.php                 ← Fallback WordPress
└── assets/
    ├── css/
    │   └── carto-theme.css   ← Tous les styles + tokens design
    └── js/
        ├── carto-components.js  ← Bibliothèque composants animés
        └── carto-theme.js       ← Nav mobile, scroll reveal, interactions
```

---

## Shortcodes composants animés

Utilisables dans n'importe quelle page ou article via l'éditeur WordPress.

### KPI Counter
```
[carto_kpi value="72" suffix="%" label="Score de sécurité" color="teal"]
[carto_kpi value="1.8" decimals="1" prefix="×" label="Surface couverte" color="orange"]
```
Options : `value`, `suffix`, `prefix`, `label`, `decimals`, `color` (teal / orange / amber)

### Gauge radiale
```
[carto_gauge value="72" max="100" label="Posture" color="teal"]
```

### Sparkline
```
[carto_sparkline points="42,38,45,40,33,26,18,11" color="teal" label="Findings / trimestre"]
```

### Bar Chart
```
[carto_barchart data="Critique:4:orange,Élevé:11:orange-lt,Moyen:23:amber,Faible:38:teal" label="Findings par sévérité"]
```
Format data : `Label:valeur:couleur` séparés par des virgules.  
Couleurs disponibles : `teal`, `orange`, `orange-lt`, `amber`

---

## Personnalisation via le Customizer

Allez dans **Apparence → Personnaliser** pour modifier :

| Option                 | Clé theme_mod           | Défaut                        |
|------------------------|-------------------------|-------------------------------|
| Eyebrow hero           | `hero_eyebrow`          | "Sécurité applicative"        |
| Titre ligne 1          | `hero_title_line1`      | "Cartographiez"               |
| Titre ligne 2          | `hero_title_line2`      | "vos risques"                 |
| Texte lead             | `hero_lead`             | (description du produit)      |
| Label CTA principal    | `hero_cta_label`        | "Demander une démo"           |
| URL CTA principal      | `hero_cta_url`          | "#contact"                    |
| Label CTA secondaire   | `hero_cta2_label`       | "Voir les fonctionnalités"    |
| URL CTA secondaire     | `hero_cta2_url`         | "#features"                   |
| Description footer     | `footer_description`    | (description du site)         |
| Contact footer         | `footer_contact`        | —                             |

---

## Tokens design (CSS custom properties)

Toutes les couleurs, polices et espacements sont dans `assets/css/carto-theme.css` sous `:root { }`.

Couleurs principales :
- `--carto-teal` : #00CFCF (signal primaire)
- `--carto-orange` : #FF6B35 (menace / danger)
- `--carto-amber` : #E8B84B (avertissement)
- `--carto-bg` : #070C18 (fond page)

Polices :
- `--carto-font-display` : Syncopate (titres)
- `--carto-font-body` : Rajdhani (corps)
- `--carto-font-mono` : Inconsolata (labels, code)

---

## Ajouter un composant animé manuellement

Dans n'importe quel template PHP :

```html
<div class="carto-stage">
    <div class="carto-stage__grid"></div>
    <div class="carto-stage__label">mon label</div>
    <div class="carto-stage__inner">
        <div id="mon-composant"></div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Carto.counter(document.getElementById('mon-composant'), {
        value: 42, suffix: '%', label: 'Mon KPI', color: '#00CFCF'
    });
});
</script>
```

---

## Compatibilité

- WordPress 6.0+
- PHP 8.0+
- Navigateurs modernes (Chrome, Firefox, Safari, Edge)
