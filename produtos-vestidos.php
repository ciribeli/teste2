<?php get_header(); /* Template Name: Produtos Vestidos */


$url = explode("?", $_SERVER["REQUEST_URI"]);
$size = count($url);



for ($i = 1; $i < $size; $i++)  {
  $separaigual = explode("=", $url[$i]);
  var_dump($separaigual);
  $separaigualnewparametro = explode ("&", $separaigual[1]);

  $size2 = count($separaigualnewparametro);
  if ($size2 > 0) {

    if ($separaigual[0] == "preco") {
      $parametrobuscapreco[] = array(
        'key' => '_price',
        'value' => $separaigualnewparametro[0],
        'compare' => "<=",
        'type' => 'NUMERIC');
    }else{
      $parametrobusca[] = array(
        'taxonomy' => 'pa_'.$separaigual[0],
        'field' => 'slug',
        'terms' => $separaigualnewparametro[0]);
    }

    for ($ii = 1; $ii < $size2; $ii++)  {

      $separaigualnovo = explode("=", $separaigualnewparametro[$ii]);

      if ($separaigualnovo[0] == "preco") {
        $parametrobuscapreco[] = array(
          'key' => '_price',
          'value' => $separaigualnovo[1],
          'compare' => "<=",
          'type' => 'NUMERIC');
      }else{
        $parametrobusca[] = array(
          'taxonomy' => 'pa_'.$separaigualnovo[0],
          'field' => 'slug',
          'terms' => $separaigualnovo[1]);
      }
    }

  }else{
    if ($separaigual[0] == "preco") {
        $parametrobuscapreco[] = array(
          'key' => '_price',
          'value' => $separaigual[1],
          'compare' => "<=",
          'type' => 'NUMERIC');
    }else {
      $parametrobusca[] = array(
        'taxonomy' => 'pa_'.$separaigual[0],
        'field' => 'slug',
        'terms' => $separaigual[1]);
    }
  }


}


?>


  <div class="title-alugue-online">
    <h1>VESTIDOS</h1>
  </div>

  <main class="main-catalog">

	  <!-- Filtro -->
	  <?php get_sidebar('catalog'); ?>


      <!-- Grid Catalog -->
	  <div class="grid-interno-catalog">
		  <?php

      $first_ids = get_posts( array(
        'fields'         => 'ids',
        'post_type'      => array('product'),
        'product_cat'    => 'vestidos',
        'posts_per_page' => '-1',
        'order'          => 'desc',
        'post__in'      => wc_get_featured_product_ids(),
      ));

      $second_ids = get_posts( array(
        'fields'         => 'ids',
        'post_type'      => array('product'),
        'product_cat'    => 'vestidos',
        'posts_per_page' => '-1',
        'order'          => 'desc',
        'post__not_in'      => wc_get_featured_product_ids(),
      ));
      $post_ids = array_merge($first_ids, $second_ids);

                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                 $args = array(
                     'post_type' => 'any',
                     'post__in'  => $post_ids,
                     'paged'     => $paged,
                     'posts_per_page' => '12',
                     'orderby'   => 'post__in',
                     'ignore_sticky_posts' => 1,
                     'meta_query' => array(
                       $parametrobuscapreco
                     ),
                     'tax_query' =>

                      array(
                        $parametrobusca
                      )


                 );

                 $counter = 0;
                 $parent = new WP_Query( $args );

                 if ( $parent->have_posts() ) :
                 while ( $parent->have_posts() ) : $parent->the_post();

		  		           $counter++;




             ?>

		  		  <a href="<?the_permalink(); ?>" class="producten-griden gride<?php echo $counter; ?> unique-gride-catalogue">
					<div class="background-image-catalogue" style="background-image:url(<?php the_post_thumbnail_url('large'); ?>)"></div>
					<div class="desc-prodimage-catalogue">
					  <h2><?php the_title(); ?></h2>
              <?php
                 $preco = $product->get_regular_price();
                 $precobr = str_replace('.',',',$preco);
                 if ($precobr > 0 or $precobr <> ""){
                   echo "<span class='price-cataloguers'>R$ $precobr</span>";
                 }
               ?>

					</div>
				  </a>


              <?php

                  endwhile;
                  endif;
                  wp_reset_postdata();
              ?>
	  </div>
    <?php
     $max = $parent->max_num_pages;
     if ($max > 1): ?>
      <div class="pagination">
        <p>Ver Mais</p>
          <?php
              echo paginate_links( array(
                  'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                  'total'        => $parent->max_num_pages,
                  'current'      => max( 1, get_query_var( 'paged' ) ),
                  'format'       => '?paged=%#%',
                  'show_all'     => false,
                  'type'         => 'plain',
                  'end_size'     => 2,
                  'mid_size'     => 1,
                  'prev_next'    => true,
                  'prev_text'    => sprintf( '<i></i> %1$s'),
                  'next_text'    => sprintf( '%1$s <i></i>'),
                  'add_args'     => false,
                  'add_fragment' => '',
              ) );
          ?>
      </div>
    <?php endif; ?>


  </main>

<?php get_footer(); ?>
