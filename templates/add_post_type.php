<?php $custom_posts = sc_get_custom_posts();?>
<div class="container">
	<h1><?php esc_attr_e( 'Adicionar Post Type', 'wp_admin_style' ); ?></h1>
    <a href="admin.php?page=sc_custom_posts" class="btn-voltar btn-primary" role="button"><?php esc_attr_e( 'Voltar', 'wp_admin_style' ); ?></a>

	<div class="row">
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Nome do Post
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome do Post">
                </label>
            </div>
            <div class="form-group">
                <label for="singular_name">Nome Singular do Post
                    <input type="text" class="form-control" id="singular_name" name="singular_name" placeholder="Nome Singular do Post">
                </label>
            </div>
            <div class="form-group">
                <label for="dashicon">Dashicon do Post
                    <input type="text" class="form-control" id="dashicon" name="dashicon" placeholder="Dashicon do Post">
                </label>
            </div>
            <div class="form-group">
                <label for="post_type">Post Type
                    <input type="text" class="form-control" id="post_type" name="post_type" placeholder="Nome Singular do Post">
                </label>
            </div>
            <div class="form-group">
                <label for="supports">Supports</label>
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="supports[]" value="title">Title
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="editor">Editor
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="author">Author
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" id="thumbnail" value="thumbnail">Thumbnail
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="excerpt">Excerpt
                    </label>
                    <label>
                        <input type="checkbox" name="supports[]" value="comments">Comments
                    </label>
                </div>
            </div>
            <div class="form-group" id="thumbnail_dimensions">
                <label for="thumbnail_dimensions">Dimensões da imagem destacada.
                <input type="text" class="form-control" name="thumbnail_dimensions" placeholder="Dimensões da imagem destacada.">
                </label>
            </div>
            <div class="form-group">
                <label for="category" class="half-size">Possui suporte a categoria?
                    <input type="checkbox" name="category" value="1">Sim
                </label>
                <label for="tag" class="half-size">Possui suporte a tags?
                    <input type="checkbox" name="tag" value="1">Sim
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
                <input type="submit" name="add_post" id="submit-post" class="btn btn-primary submit-post" value="Salvar">
            </div>
        </form>
	</div>
</div>

<script>
    jQuery(document).ready(function( $ ) {
        $("#thumbnail").on('click', function () {
            if( $(this).is(':checked')) {
                $("#thumbnail_dimensions").delay( 500 ).fadeIn( 300 );
            } else {
                $("#thumbnail_dimensions").delay( 500 ).fadeOut( 300 );
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
