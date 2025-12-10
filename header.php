<?php
/**
 * Header.
 */

use theme\FoundationNavigation;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Set up Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="<?php bloginfo('charset'); ?>">

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <!-- Remove Microsoft Edge's & Safari phone-email styling -->
    <meta name="format-detection" content="telephone=no,email=no,url=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class('no-outline fwp'); ?>>
<?php wp_body_open(); ?>
<!-- BEGIN of header -->
<header class="header">
    <div class="grid-container grid-header">
        <div class="grid-x grid-margin-x">
            <div class="cell">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <h1>
                        <?php show_custom_logo(); ?><span class="show-for-sr"><?php echo get_bloginfo('name'); ?></span>
                    </h1>
                </div>
                <?php if (has_nav_menu('header-menu')) { ?>
                    <button class="mobile-menu-toggle hide-for-medium" type="button" aria-label="Menu" aria-controls="main-menu">
                        <span></span>
                    </button>
                    <nav class="top-bar" id="main-menu">
                        <div class="menu-container">
                            <?php wp_nav_menu([
                                'theme_location' => 'header-menu',
                                'menu_class' => 'menu header-menu',
                                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'walker' => new FoundationNavigation(),
                            ]); ?>
                        </div>
                    </nav>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</header>


<!-- END of header -->
