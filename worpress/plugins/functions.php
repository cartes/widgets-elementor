function destacados_posts_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'num_posts' => '7',
        'destacado_pos' => '1',
        'aviso' => '¡Destacado!',
        'post_type' => 'post',
        'categoria' => '',
        'offset' => '0',
        'columnas' => '3'
    ), $atts);

    $num_posts = intval($atts['num_posts']);
    $destacado_pos = intval($atts['destacado_pos']) - 1;
    $offset = intval($atts['offset']);
    $columnas = max(1, min(5, intval($atts['columnas'])));

    $args = array(
        'post_type' => $atts['post_type'],
        'posts_per_page' => $num_posts,
        'offset' => $offset,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if ($atts['categoria']) {
        $args['category_name'] = $atts['categoria'];
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No hay posts disponibles.</p>';
    }

    ob_start();
    ?>
    <div class="destacados-module" style="max-width: 1200px; margin: 0 auto; padding: 0;">
        <!-- DESTACADO: FULL WIDTH ARRIBA -->
        <?php
        $query->rewind_posts();
        $destacado_encontrado = false;

        if ($atts["destacado_pos"] == '1' || $atts["destacado_pos"] == '1') {
            $destacado_encontrado = true;
        }

        while ($query->have_posts() && $destacado_encontrado):
            $query->the_post();
            $index = $query->current_post;
            if ($index == $destacado_pos) {
                $destacado_encontrado = true;
                $cats = get_the_category();
                $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
                ?>
                <div class="post-destacado-full" style="margin-bottom: 10px; position: relative;">
                    <!-- DESKTOP: imagen izquierda + texto derecha -->
                    <div class="destacado-desktop"
                        style="display: flex; gap: 20px; align-items: top; max-width: 1200px; margin: 0 auto;">
                        <a href="<?php the_permalink(); ?>" style="flex: 0 0 370px;">
                            <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: 250px; object-fit: cover !important;')); ?>
                        </a>
                        <div style="flex: 1;">
                            <div
                                style="display: flex; font-weight: 900; justify-content: space-between; align-items: center; flex-shrink: 0;">
                                <small style="color: #B70015;">
                                    <?php echo get_the_date('d M Y'); ?>
                                </small>
                            </div>
                            <h2 style="margin: 0 0 15px 0; font-size: 21px; line-height: 1.2; color: #1a1a1a;">
                                <a href="<?php the_permalink(); ?>"
                                    style="text-decoration: none; color: inherit; font-weight: 700;">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <p style="margin-bottom: 20px; color: #555; line-height: 1.6; font-size: 16px;">
                                <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                            </p>
                        </div>
                    </div>
                    <!-- MOBILE: categoría → título → imagen (columna) -->
                    <div class="destacado-mobile">
                        <?php if ($cat_name): ?>
                            <span class="destacado-mobile-cat"><?php echo $cat_name; ?></span>
                        <?php endif; ?>
                        <h2 class="destacado-mobile-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <a href="<?php the_permalink(); ?>" class="destacado-mobile-img-link">
                            <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: auto; display: block; object-fit: cover;')); ?>
                        </a>
                    </div>
                </div>
                <?php
            }
        endwhile;
        ?>

        <!-- RESTO POSTS: Grid columnas (desktop) / Lista (mobile) -->
        <div class="posts-normales"
            style="display: grid; grid-template-columns: repeat(<?php echo $columnas; ?>, 1fr); gap: 15px; align-items: start;">
            <?php
            $query->rewind_posts();
            while ($query->have_posts()):
                $query->the_post();
                $index = $query->current_post;
                if ($index != $destacado_pos) {
                    $cats = get_the_category();
                    $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
                    ?>
                    <div class="post-normal" style="background: white; display: flex; flex-direction: column; min-height: 200px;">
                        <a href="<?php the_permalink(); ?>"
                            style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1;">
                            <!-- CONTAINER para imagen: controla height perfecto -->
                            <div class="post-image-container"
                                style="width: 100%; height: 180px; margin-bottom: 15px; flex-shrink: 0; overflow: hidden;">
                                <?php the_post_thumbnail('medium', array('loading' => 'eager', 'style' => 'width: 100%; height: 100%; object-fit: cover !important; display: block;')); ?>
                            </div>
                            <div class="post-normal-text">
                                <div class="post-normal-meta"
                                    style="display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                                    <small style="color: #B70015; font-weight: 900">
                                        <?php if ($cat_name)
                                            echo $cat_name;
                                        else
                                            echo get_the_date('d M Y'); ?>
                                    </small>
                                </div>
                                <h3 class="post-normal-title"
                                    style="margin: 0 0 12px 0; font-size: 16px; line-height: 1.3; color: #333; flex-grow: 1; font-weight: 700;">
                                    <?php the_title(); ?>
                                </h3>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            endwhile;
            ?>
        </div>
    </div>
    <style>
        /* ── DESKTOP (sin cambios) ── */
        .destacados-module .post-image-container {
            width: 100% !important;
            height: 180px !important;
            overflow: hidden !important;
        }

        .destacados-module .post-image-container img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .destacados-module .post-normal {
            display: flex !important;
            flex-direction: column !important;
        }

        /* Ocultar bloque mobile en desktop */
        .destacados-module .destacado-mobile {
            display: none;
        }

        /* Wrapper de texto: transparente en desktop (no afecta el flex del <a>) */
        .destacados-module .post-normal-text {
            display: contents;
        }

        /* ── MOBILE ── */
        @media (max-width: 768px) {

            /* Post destacado: ocultar versión desktop, mostrar versión mobile */
            .destacados-module .destacado-desktop {
                display: none !important;
            }

            .destacados-module .destacado-mobile {
                display: block;
                margin-bottom: 12px;
            }

            .destacados-module .destacado-mobile-cat {
                display: block;
                color: #B70015;
                font-size: 13px;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .destacados-module .destacado-mobile-title {
                margin: 0 0 10px 0;
                font-size: 22px;
                line-height: 1.25;
                font-weight: 700;
                color: #1a1a1a;
            }

            .destacados-module .destacado-mobile-title a {
                text-decoration: none;
                color: inherit;
            }

            .destacados-module .destacado-mobile-img-link img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
            }

            /* Grid de posts normales → lista vertical de una columna */
            .destacados-module .posts-normales {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }

            /* Cada post normal → fila horizontal: imagen pequeña | columna texto */
            .destacados-module .post-normal {
                flex-direction: row !important;
                align-items: center !important;
                min-height: unset !important;
                padding: 10px 0;
                border-bottom: 1px solid #e0e0e0;
            }

            /* El <a> interior: fila con gap */
            .destacados-module .post-normal > a {
                flex-direction: row !important;
                align-items: center !important;
                gap: 12px;
            }

            /* Imagen pequeña cuadrada a la izquierda */
            .destacados-module .post-normal .post-image-container {
                width: 100px !important;
                height: 75px !important;
                min-width: 100px !important;
                margin-bottom: 0 !important;
                flex-shrink: 0 !important;
            }

            /* Columna derecha: categoría arriba + título abajo */
            .destacados-module .post-normal .post-normal-text {
                display: flex !important;
                flex-direction: column !important;
                flex: 1;
                min-width: 0;
            }

            .destacados-module .post-normal .post-normal-meta {
                margin-bottom: 2px;
            }

            .destacados-module .post-normal .post-normal-title {
                font-size: 14px !important;
                margin: 0 !important;
                line-height: 1.3;
            }
        }
    </style>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('destacados_posts', 'destacados_posts_shortcode');
