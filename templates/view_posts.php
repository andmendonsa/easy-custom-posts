<div class="container">
    <div class="row">
        <div class="view-top">
            <h1><?php esc_attr_e( 'Easy Custom Posts', 'wp_admin_style' ); ?></h1>
            <a href="admin.php?page=add_post" class="btn btn-primary" role="button"><?php esc_attr_e( 'Adicionar', 'wp_admin_style' ); ?></a>
            <?php if (!has_posts()):?>
                <a href="admin.php?page=import_posts" class="btn btn-primary import" role="button"><?php esc_attr_e( 'Importar Demo', 'wp_admin_style' ); ?></a>
            <?php endif;?>
        </div>
	    <?php
            global $wpdb;
            $table = $wpdb->prefix ."custom_posts";
            $custom_posts = $wpdb->get_results("SELECT * FROM ".$table." ORDER BY name");
	    ?>
        <h2><?php esc_attr_e( 'Custom Posts Cadastrados', 'wp_admin_style' ); ?></h2>

        <ul class="responsive-table">
            <li class="table-header">
                <div class="col"><?php esc_attr_e( 'Título do Custom Post', 'wp_admin_style' ); ?></div>
                <div class="col"><?php esc_attr_e( 'Ícone do Custom Post', 'wp_admin_style' ); ?></div>
                <div class="col"><?php esc_attr_e( 'Custom Post Type', 'wp_admin_style' ); ?></div>
                <div class="col"><?php esc_attr_e( 'Data de Criação', 'wp_admin_style' ); ?></div>
                <div class="col"><?php esc_attr_e( 'Ações', 'wp_admin_style' ); ?></div>
            </li>
	        <?php
	        if (count($custom_posts) > 0):
		        foreach ($custom_posts as $item):?>
                    <li class="table-row">
                        <div class="col" data-label="Título do Custom Post"><?=$item->name;?></div>
                        <div class="col" data-label="Ícone do Custom Post"><?=$item->dashicon;?></div>
                        <div class="col" data-label="Custom Post Type"><?=$item->post_type;?></div>
                        <div class="col" data-label="Data de Criação"><?=date("d/m/Y H:i:s", strtotime($item->create_timestamp));?></div>
                        <div class="col actions" data-label="Ações">
                            <a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=edit_post&post=<?=$item->id?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <div class="tooltip">
                                <div class="tooltiptext">
                                    <strong>Tem certeza que deseja excluir?</strong>
                                    <a href="#" >Não</a>
                                    <a href="#" class="delete-post" data-id="<?=$item->id?>">Sim</a>
                                </div>
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </div>
                        </div>
                    </li>
		        <?php endforeach;?>
	        <?php else:?>
                <li class="table-row empty">
                    <div class="col">Nenhum Custom Post Cadastrado.</div>
                </li>
	        <?php endif;?>
        </ul>
    </div>
</div>

<script>
    jQuery(document).ready(function( $ ) {
        $(".delete-post").on('click', function () {
            $('body').loadingModal({text: 'Excluindo...', color: '#313131', opacity: '0.7', backgroundColor: 'rgba(187,211,48)', animation : 'wanderingCubes'});
            var id = jQuery(this).data('id');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    'action': 'delete_post_type',
                    'id': id
                },

                success: function(response){
                    var obj = JSON.parse(response);
                    if (obj.class == "success"){
                        location.reload();
                    } else {

                    }
                    $('body').loadingModal('hide');
                    $('body').loadingModal('destroy');
                }
            });
        });
    });
</script>