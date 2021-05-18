<?php
// configuration
include 'config.php';
$row = $_POST['row'];
$rowperpage = 3;

// selecting products
$query = "SELECT company_generic.company_id, product.company_id, product.id AS productID, product_generic.product_id, 
						  
						  ( SELECT product_generic.value
						    FROM product_generic
							WHERE product_generic.key = 'title'
							AND product.id = product_generic.product_id ) AS productTitle,
							( SELECT product_generic.value
						    FROM product_generic
							WHERE product_generic.key = 'desc'
							AND product.id = product_generic.product_id ) AS productDetail,
							( SELECT product_generic.value
						    FROM product_generic
							WHERE product_generic.key = 'tags'
							AND product.id = product_generic.product_id ) AS productTags, 
						 
						 product_uploads.hash AS imageName, product_uploads.ext AS imageExtension,
						 product_trait_generic.value AS productPrice,						 
												 
						 ( SELECT company_generic.value
						    FROM company_generic
							WHERE company_generic.key = 'name'
							AND company_generic.company_id = product.company_id ) AS companyName,
						( SELECT company_generic.value
						    FROM company_generic
							WHERE company_generic.key = 'city'
							AND company_generic.company_id = product.company_id ) AS cityName,
						( SELECT company_generic.value
						    FROM company_generic
							WHERE company_generic.key = 'country'
							AND company_generic.company_id = product.company_id ) AS countryName
							
						FROM product, product_generic, product_uploads, product_traits, product_trait_generic, 
						     company_generic 
						WHERE product.id = product_generic.product_id AND product.id = product_uploads.product_id
						       AND product.id = product_traits.product_id
							   AND product_traits.id = product_trait_generic.product_trait_id
							   AND company_generic.company_id = product.company_id
						GROUP BY product_generic.product_id 
						LIMIT $row,$rowperpage";
$result = mysqli_query($con,$query);

$html = '';

while($row = mysqli_fetch_array($result)){

	            $id = $row['productID'];
				$producttitle = $row['productTitle'];
				$productTags =  $row['productTags'];
				$productdetail =  $row['productDetail'];
				
				$productimg = $row['imageName'];
				$productimgext = $row['imageExtension'];
				$productprice =  $row['productPrice']; 
				
				$companyName = $row['companyName'];
				$cityName = $row['cityName'];
				$countryName = $row['countryName'];			
				
	// Creating HTML structure
    $html .= '<div class="card">';
	$html .= '<img src="img/'.$productimg. '.' .$productimgext.'" alt="'.$producttitle.'" class="card-img-top">';
	$html .= '<div class="card-body">';
	$html .= '<h5 class="card-title">'.$producttitle.'</h5>';	
	$html .= '<p class="card-text"><small class="text-muted">'.$productTags.'</small></p>';
	$html .= '<span class="preis">Preis</span> <p style="text-align:center;">'.$productprice.'</p>';
	$html .= '<p class="card-text">'.$productdetail.'</p>';
	$html .= '<p  style="text-align:center"><strong>'.$companyName.'</strong>
			  <br>'.$cityName. ', '.$countryName.'</p>';
    $html .='<button type="button" class="btn btn-warning" style=" display:block; margin: 0 auto;">Weitere Informationen</button>';
	$html .= '</div> </div>';
}
echo  $html;