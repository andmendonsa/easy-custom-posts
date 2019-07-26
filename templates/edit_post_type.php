<?php
	global $wpdb;
	$table = $wpdb->prefix."custom_posts";
	$post_type = $wpdb->get_results("SELECT * FROM ".$table." WHERE id = ". $_GET['post']);
	$supports = explode(',', $post_type[0]->supports);
    $custom_posts = sc_get_custom_posts();
?>
<div class="container">
    <h1><?php esc_attr_e( 'Editar Post Type', 'wp_admin_style' ); ?></h1>
    <a href="admin.php?page=sc_custom_posts" class="btn-voltar btn-primary" role="button"><?php esc_attr_e( 'Voltar', 'wp_admin_style' ); ?></a>

    <div class="row">
        <form action="" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nome do Post
                        <input type="hidden" name="post_id" value="<?=$post_type[0]->id?>">
                        <input type="text" class="form-control" id="name" name="name" value="<?=$post_type[0]->name?>" placeholder="Nome do Post">
                    </label>
                </div>
                <div class="form-group">
                    <label for="singular_name">Nome Singular do Post
                        <input type="text" class="form-control" id="singular_name" name="singular_name" value="<?=$post_type[0]->singular_name?>" placeholder="Nome Singular do Post">
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="dashicon">Dashicon do Post
                        <input type="text" class="form-control" id="dashicon" name="dashicon" value="<?=$post_type[0]->dashicon?>" placeholder="Dashicon do Post">
                    </label>
                </div>
                <div class="form-group">
                    <label for="post_type">Post Type
                        <input type="text" class="form-control" id="post_type" name="post_type" value="<?=$post_type[0]->post_type?>" placeholder="Nome Singular do Post">
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="supports">Supports</label>
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="supports[]" value="title" <?php echo (in_array("title", $supports))?'checked':'';?>>Title
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="editor" <?php echo (in_array("editor", $supports))?'checked':'';?>>Editor
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="author" <?php echo (in_array("author", $supports))?'checked':'';?>>Author
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" id="thumbnail" value="thumbnail" <?php echo (in_array("thumbnail", $supports))?'checked':'';?>>Thumbnail
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="excerpt" <?php echo (in_array("excerpt", $supports))?'checked':'';?>>Excerpt
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="comments" <?php echo (in_array("comments", $supports))?'checked':'';?>>Comments
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="thumbnail_dimensions" id="thumbnail_dimensions" <?php echo ($post_type[0]->thumbnail_dimensions)?'style="display: block"':'style="display: none"'?>>Dimensões da imagem destacada.
                    <input type="text" class="form-control" name="thumbnail_dimensions" value="<?=$post_type[0]->thumbnail_dimensions?>" placeholder="Dimensões da imagem destacada.">
                </label>
            </div>
            <div class="form-group">
                <label for="category" class="half-size">Possui suporte a categoria?
                    <input type="checkbox" name="category" value="1" <?php echo ($post_type[0]->has_category == 1)?'checked':'';?>>Sim
                </label>
                <label for="tag" class="half-size">Possui suporte a tags?
                    <input type="checkbox" name="tag" value="1" <?php echo ($post_type[0]->has_tag == 1)?'checked':'';?>>Sim
                </label>
            </div>
            <div class="form-group">
                <label for="other_post">Atribuir a outro custom post?
                    <input type="checkbox" name="other_post" id="other_post" value="1">Sim
                </label>
            </div>
            <div class="form-group" id="related_post">
                <label for="related_post">Selecione o custom post:
                    <select name="related_post">
                        <option value="">Selecione o post...</option>
				        <?php foreach ($custom_posts as $item):?>
                            <option value="<?=$item->post_type?>"><?=$item->name?></option>
				        <?php endforeach;?>
                    </select>
                </label>
            </div>
            <div class="form-group btn-submit">
                <input type="submit" name="edit_post" id="submit-post" class="btn btn-primary submit-post" value="Atualizar">
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function( $ ) {
        $("#thumbnail").on('click', function () {
            if( $(this).is(':checked')) {
                console.log('teste')
                $("#thumbnail_dimensions").delay( 500 ).css( 'display','block' );
            } else {
                $("#thumbnail_dimensions").delay( 500 ).css( 'display','none' );
            }
        });

        $("#other_post").on('click', function () {
            if( $(this).is(':checked')) {
                $("#related_post").delay( 500 ).fadeIn( 300 );
            } else {
                $("#related_post").delay( 500 ).fadeOut( 300 );
            }
        });
    });
</script>
