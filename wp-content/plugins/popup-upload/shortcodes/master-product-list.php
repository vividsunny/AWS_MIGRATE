<?php
$per_page = 20;
$bs_page = isset( $_GET['bs_page'] ) ? $_GET['bs_page'] : 1 ;
$qrystr = '';
$qrystr = '';

$array_filter = array();
if(isset( $_GET['orderby'] ) && !empty( isset( $_GET['orderby'] ) ) ){

	$args['order_by'] = $_GET['orderby'];
	$qrystr .='&orderby='.$_GET['orderby'];
	$desc_chk = explode('-',$_GET['orderby']);
	if( isset( $desc_chk[1] ) && !empty( $desc_chk[1] ) ){
		$args['order_by'] =  $desc_chk[0];
		$args['order'] = $desc_chk[1];
		$qrystr .='&order='.$desc_chk[1];
	}
}
$bs_name = '';
if(isset( $_GET['bs_name'] ) && !empty( $_GET['bs_name'] ) ){
	 $array_filter['name']['name'] = $_GET['bs_name'];
	 $bs_name = $_GET['bs_name'];
	 $qrystr .='&bs_name='.$_GET['bs_name'];
}

if(isset( $_GET['category'] ) && !empty( $_GET['category'] ) ){
	 $array_filter['category']['name'] = $_GET['category'];
	 $qrystr .='&category='.$_GET['category'];
}

if(isset( $_GET['color'] ) && !empty( $_GET['color'] ) ){
	 $array_filter['color']['name'] = $_GET['color'];
	 $qrystr .='&color='.$_GET['color'];
}

if(isset( $_GET['celebration'] ) && !empty( $_GET['celebration'] ) ){
	 $array_filter['celebration']['name'] = $_GET['celebration'];
	 $qrystr .='&celebration='.$_GET['celebration'];
}
//debug($_GET);
if(isset( $_GET['min_price'] ) && !empty( $_GET['min_price'] ) && isset( $_GET['max_price'] ) && !empty( $_GET['max_price'] ) ){
	 $array_filter['price']['min'] = $_GET['min_price'];
	 $array_filter['price']['max'] = $_GET['max_price'];
	 $qrystr .='&min_price='.$_GET['min_price'].'&max_price='.$_GET['max_price'];
}

//debug($array_filter);
if( !empty( $array_filter ) && count( $array_filter ) > 0 ){
	$args['filter_query'] = $array_filter;
}
global $mpdb;
$per_page = 20;
$bs_page = isset( $_GET['bs_page'] ) ? $_GET['bs_page'] : 1 ;
$args = array(
	'per_page'	=> $per_page,
	'page'	=> $bs_page,
	'order' => 'asc',
	//'fields' => 'name,wholesale_price,img_url',
);
$result = $mpdb->mp_query( $args );

if( isset( $result['data'] )  && count( $result['data'] ) ){
	$total_records = $result['total_data'];
	$products = $result['data'];
	?>
	<div class="col-12 col-sm-12 col-md-9 col-lg-9">
		<div class="row border-bottom">
			<div class="remove-filers col-12 col-md-8 col-lg-8 col-sm-8">
				<?php 
					if(isset($_GET)){
						$price_arr = array('min_price','max_price');
						foreach ($_GET as $remove_key => $remove_filter) {
							
							if($remove_key != 'price'){
								
								if(in_array($remove_key,$price_arr)){
									$remove_filter = '$'.$remove_filter;
								}

								$link = remove_query_arg( $remove_key );
								echo '<a href="'.$link.'" class="btn btn-outline-danger btn-sm mr-1">'.$remove_filter.' <i class="fas fa-times-circle"></i> </a>';	
							}
							
						}
						
					}
				?>
			</div>
			<div class="col-12 col-md-4 col-lg-4 col-sm-4">
				<form class="woocommerce-ordering mb-2" method="get">
					<!-- <select name="orderby" class="orderby form-control-sm">
						<option value="asc">Sort by old</option>
						<option value="desc" <?php //if(isset($_GET['orderby'])){ selected($_GET['orderby'],'created');  } ?> >Sort by latest</option>
						<option value="wholesale_price" <?php //if(isset($_GET['orderby'])){ selected($_GET['orderby'],'wholesale_price');  } ?> >Sort by price: low to high</option>
						<option value="wholesale_price-desc" <?php //if(isset($_GET['orderby'])){ selected($_GET['orderby'],'wholesale_price-desc'); }  ?> >Sort by price: high to low</option>
					</select> -->
					<input type="hidden" name="paged" value="1">
					<?php
					if(isset($_GET)){
						foreach($_GET as $get_key => $get_val){
							echo '<input type="hidden" name="'.$get_key.'" value="'.$get_val.'">';
						}
					}
					?>
				</form>
			</div>
		</div>
		<ul class="products columns-3 mt-3">
			<?php
				if( !empty( $products ) ){

					$licount = 1;
					foreach($products as $product){

						$last  = ($licount%3 == 0) ? 'last' : '';
						$first = ($licount == 1) ? 'first' : '';
						if( !empty( $product['aws_image'] ) && $product['aws_image'] == 'yes' ){
							$aws_url = $product['aws_key'];
							$thumb_url = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$aws_url;
						}else{
							$thumb_url = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
						}
						
						?>
						<li class="product <?php echo $last.' '.$first; ?>">
							<a href="javascript:void(0);">
								<img height="300" width="300" src="<?php echo $thumb_url; ?>" class="woocommerce-placeholder wp-post-image">
								<h2 class="woocommerce-loop-product__title"><?php echo $product['product_name']; ?></h2>
								<span class="price"><?php echo get_woocommerce_currency_symbol().$product['price']; ?></span>
							</a>
							<?php
							$slug_title = str_replace(' ','-', $product['product_name'] );
							
							$bsslug = home_url()."/buyseason-product/$slug_title";
							?>
							<a href="<?php echo $bsslug; ?>" class="button addtocartbutton">View Product</a>
							

						</li>
						<?php
						
						$licount++;
						$licount = ($licount == 4) ? 1 : $licount; 
						
					}
				}else{
					echo '<div class="alert alert-danger">No product found.!</div>';
				}
			?>
		</ul>
		<?php
		
		
		echo '<nav>';
        echo $mpdb->mp_pagination($total_records, $per_page, $qrystr, 'Show Pagination', 'bs_page');
        echo ' </nav>';
		?>
			
	</div>
	<?php
}else{
	echo "No data found";
}
?>