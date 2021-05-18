<?php
include "config.php";
?>
<!doctype html>
<html>
    <head>
        <title>Industrystock</title>
        <link href="style.css" type="text/css" rel="stylesheet">
        <script src="//code.jquery.com/jquery-1.12.0.min.js" type="text/javascript"></script>
        <script src="script.js"></script>
		
<!-- Bootstrap CDN-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	 
		
    </head>
    <body>
	 <div class="container">
	 <div class="card-columns">
       <?php
            $rowperpage = 6;

            // counting total number of posts
            $allcount_query = "SELECT count(*) as allcount FROM product_generic";
            $allcount_result = mysqli_query($con,$allcount_query);
            $allcount_fetch = mysqli_fetch_array($allcount_result);
            $allcount = $allcount_fetch['allcount'];

          	$query ="SELECT company_generic.company_id, product.company_id, product.id AS productID, product_generic.product_id, 
						  
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
						LIMIT 0,$rowperpage"; 
					   
			  
            $result = mysqli_query($con,$query);
			 
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
            ?>

         <div class="card">
           <img src="img/<?php echo $productimg. '.' .$productimgext;?>" alt="<?php echo $producttitle; ?>" class="card-img-top">
           <div class="card-body">
             <h5 class="card-title"><?php echo $producttitle; ?></h5>
			 <p class="card-text"><small class="text-muted"><?php  echo $productTags; ?></small></p>
			 <span class="preis">Preis</span> <p style="text-align:center;"><?php echo $productprice;?></p>
        <p class="card-text"><?php  echo $productdetail; ?></p>		
          <p  style="text-align:center"><strong><?php echo $companyName; ?></strong> <br>	
          <?php echo $cityName . ', '.$countryName; ?></p>
           <button type="button" class="btn btn-warning" style=" display:block; margin: 0 auto;">Weitere Informationen</button>
      </div>
    </div>
                 
 <?php } ?>
    </div>
		   <h1 class="load-more">Load More</h1>
              <input type="hidden" id="row" value="0">
	   <input type="hidden" id="all" value="<?php echo $allcount; ?>">

	</div>
	</body>
</html>