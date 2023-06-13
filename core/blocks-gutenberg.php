<?php

// ADD BLOCK CATEGORY
add_filter( 'block_categories_all' , function( $categories ) {

    $categories[] = array(
      'slug'  => 'apros-block-category',
      'title' => 'Apros'
    );
    return $categories;
    
});


// REGISTER BLOCKS
add_action('acf/init', function (){
    if (function_exists('acf_register_block')) {

        // NOMBRE BLOCK / PAGINA
        $blocks = [ 
            // GLOBAL
            (object) array(
                'pageName' => "Global",
                'src' => "global",
                'blocks' => array(
                    "Espaciado",
                ),
            ),
            // iNICIO
            // (object) array(
            //     'pageName' => "Home",
            //     'src' => "pages/home",
            //     'blocks' => array(
            //         "Banner slider",
            //         "Productos",
            //         "Cómo trabajamos",
            //         "Qué hemos logrado",
            //         "Articulos",
            //         "Testimonios",
            //         "Nuestros aliados",
            //         "Refiere",
            //     ),
            // ),
     

        ];

        
        foreach ($blocks as $block):

            $page_name = $block->pageName;
            $src = $block->src;
            $items = $block->blocks;

            foreach($items as $item):

                $title_block  = $item;
                $new_title_block = $title_block . " (".$page_name.")";
                $block_name_file = get_name_title_block_file(sanitize_title($title_block));
                $keywords = get_keywords_block_name($new_title_block);

                acf_register_block(array(
                    'name' => $new_title_block,
                    'title' => __($new_title_block),
                    'description' => __("Bloque ".$new_title_block),
                    'render_template'   => "templates/$src/$block_name_file.php",
                    'category' => 'apros-block-category',
                    'icon' => get_icon("apros"),
                    'keywords' => $keywords,
                ));

            endforeach;
            
        endforeach;

    }
});