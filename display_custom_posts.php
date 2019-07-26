<?php

$custom_posts = sc_get_custom_posts();

if ($custom_posts){
	foreach ($custom_posts as $post){
		add_action('init', function() use($post){
			$supports = explode(",", $post->supports);
			$featured_image = "Imagem destacada";

			if ($post->thumbnail_dimensions){
				$featured_image = $post->thumbnail_dimensions;
			}

			$labels = array(
				'name' => _x($post->name, 'post type general name'),
				'singular_name' => _x($post->singular_name, 'post type singular name'),
				'add_new_item' => _x('Adicionar '.$post->singular_name, 'post type add new item'),
				'edit_item' => _x('Editar '.$post->singular_name, 'post type edit item'),
				'featured_image' => _x($featured_image, 'post type edit item'),
			);

			$args = array(
				'labels'=>$labels,
				'public'=>true,
				'menu_icon'	=> $post->dashicon,
				'show_in_menu' => ($post->related_post)?'edit.php?post_type='.$post->related_post.'':true,
				'supports' => $supports
			);

			register_post_type($post->post_type, $args);
			flush_rewrite_rules();
		});

		if ( $post->has_category == 1 ) {
			add_action('init', function() use($post){
				$label = "Categorias ".$post->name;
				$slug = 'categoria-'.$post->post_type;
				register_taxonomy(
					$slug,
					$post->post_type,
					array(
						'label' => __($label),
						'show_ui' => true,
						'show_admin_column' => true,
						'show_tagcloud' => false,
						'rewrite' => array('slug' => $slug),
						'hierarchical' => true
					)
				);
			});	
		}

		if ( $post->has_tag == 1 ) {
			add_action('init', function() use($post){
				$label = "Tags";
				$slug = 'tag-'.$post->post_type;
				register_taxonomy(
					$slug,
					$post->post_type,
					array(
						'label' => __($label),
						'show_ui' => true,
						'show_admin_column' => true,
						'show_tagcloud' => false,
						'rewrite' => array('slug' => $slug),
						'hierarchical' => true
					)
				);
			});
		}
	}
}