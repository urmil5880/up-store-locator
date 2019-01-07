<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;	// Exit if accessed directly
}

function wpnaw_get_store( $atts, $content = null ){
	// setup the query
	
	?>
	<div class="wpnawfree-plugin news-clearfix">
		<form method="post" id="BookingForm" class="active">
			<?php
	        $products_category_terms = get_terms('store-category', array('hide_empty' => false, 'parent' => 0 ));
	        if ( !empty($products_category_terms) ) :
	            $tabs = 1;
	           ?>
			<?php
                echo '<select class="getcategorystate" name="state_name" data-cat_tx="'.$products_category_terms->slug.'">
                          <option value="'.$products_category_terms->term_id.'" selected="selected">State</option>';
                       foreach ( $products_category_terms as $products_category_term ) : ?>
                          <option class="get_cat_value" data-cat-parent-id="<?php echo $products_category_term->term_id; ?>" data-cat-parent-slug="<?php echo $products_category_term->slug; ?>" value="<?php echo $products_category_term->slug; ?>"><?php echo $products_category_term->name; ?></option>
                 
                       <?php endforeach;
                echo '</select>';
           
                ?>

                <?php 

                 $tabs++;
            	wp_reset_postdata();
		        endif;       
		    ?>

		    <select name="city_name" class="city_name">

		    	<option>Select City</option>

		    </select>

		    <input class="submit_btn" type="button" name="submit" value="submit">
		</form>

		<div class="result_data"></div>
	</div>	

	<script type="text/javascript">
			/*************************************/
			jQuery(document).ready(function() {

				jQuery(document).on('change', '.getcategorystate', function($) { 	

			      var formData = jQuery("#BookingForm").serialize();
			      var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			     
			      	var catID = jQuery(this).find("option:selected").attr('data-cat-parent-id');

     				var catSlug = jQuery(this).find("option:selected").attr('data-cat-parent-slug');

			        jQuery.ajax({
			            type   : "POST",
			            //dataType : "json",
			            url    : ajaxurl,
			            data   : {
			                      data: formData,
			                      action : "make_statecity",
			                      parentId : catID,
                      			  parentSlug:catSlug,
			                     /* taxonomy:procategory*/
			                    },
			                success:function(data){
			                    jQuery('.city_name').html( data ); 

			                }
			        });
			        return false;
			    });


	        });

	        jQuery(document).on('click', '.submit_btn', function() {



	        	var formData = jQuery("#BookingForm").serialize();
			    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

			    var catID = jQuery(".getcategorystate").find("option:selected").attr('data-cat-parent-id');
				var catSlug = jQuery(".getcategorystate").find("option:selected").attr('data-cat-parent-slug');

			    var childcatID = jQuery(".city_name").find(":selected").attr('data-cat-child-id');
			  	var childcatSlug = jQuery(".city_name").find(':selected').attr('data-cat-child-slug');


			    jQuery.ajax({
			            type   : "POST",
			            //dataType : "json",
			            url    : ajaxurl,
			            data   : {
			                      	data: formData,
			                      	action : "make_getall_data",
			                      	parentId : catID,
                      			  	parentSlug:catSlug,
			                      	childparentId : childcatID,
			                      	childparentSlug:childcatSlug,
			                      
			                    },
			                success:function(data){
			                   console.log(data);
			                	jQuery(".result_data").html(data);
			                }
			        });


	        });	
			</script>	
	<?php
	
	wp_reset_postdata(); 
				
	return ob_get_clean();
}

// 'up_store' shortcode
add_shortcode('up_store','wpnaw_get_store');


function make_statecity(){

     // global $post;

     $parent_id = $_POST['parentId'];
	 $parent_slug = $_POST['parentSlug'];

        $get_parent_id = get_term_by('slug', $parent_slug, 'store-category');
        $parent_id = $get_parent_id->term_id;

        $parentcat = get_terms( 'store-category', 'hide_empty=0&parent='.$parent_id);
            
            foreach ($parentcat as $child) {
            	
            	$option .= '<option data-cat-child-slug="'.$child->slug.'" data-cat-child-id="'.$child->term_id.'" value="'.$child->slug.'">';
                $option .= $child->name;
                $option .= '</option>';
            }
        echo '<option value="0" selected="selected">Select Product</option>'.$option;
        //echo $option;
        die();
	

}
add_action('wp_ajax_make_statecity', 'make_statecity');
add_action('wp_ajax_nopriv_make_statecity', 'make_statecity');


function make_getall_data(){

	global $post;

	$parent_Id = $_POST['parentId'];
	$parent_Slug = $_POST['parentSlug'];

	$childparent_Id = $_POST['childparentId'];
	$childparent_Slug = $_POST['childparentSlug'];


	$args = array(
			'post_type' => 'store-locator',
			'posts_per_page' => -1,
			'tax_query' => array(
				 'relation' => 'AND',
				 	array(
						'hide_empty' => 0,
						'taxonomy' => 'store-category',
						'field'    => 'id',
						'terms'    => $parent_Id,

					),
					array(
						'hide_empty' => 0,
						'taxonomy' => 'store-category',
						'field'    => 'id',
						'terms'    => $childparent_Id,
					),
			),
		);
		
      $query = new WP_Query( $args );
      if( $query->have_posts() ) :
      		 while( $query->have_posts() ): $query->the_post();

      					$contact_name = get_post_meta( $post->ID, '_contact_name', true );
      					$contact_email = get_post_meta( $post->ID, '_contact_email', true );

		      				$result = '<div class="main">';
		      				$result .= '<h3>' . get_the_title() . '</h3><br>';
		      				$result .= '<div>'.get_the_content(). '</div>';
		      				$result .= '<div><strong>Compnay Name:</strong>' .$contact_name. '</div>';
		      				$result .= '<div><strong>C:</strong>' .$contact_email. '</div>';
		      				$result .= '</div>';

      				echo $result;	
      			endwhile;
      	 wp_reset_postdata();
		else :
		    echo 'No Product Found';
		endif;

		die(1); 

}
add_action('wp_ajax_make_getall_data', 'make_getall_data');
add_action('wp_ajax_nopriv_make_getall_data', 'make_getall_data');
