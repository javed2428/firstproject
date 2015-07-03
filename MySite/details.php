<html>
<head>
    <title>Details of the product</title>
</head>
<body>


<?php
require "includes/detailsfile.php";

echo '<h1><a href="' . $link . '">' . $title . '</a></h1>';//Title
echo '<p><a href="' . $img_src . '"><img src="' . $img_src . '"></a></p>';//image
echo '<p>Price :' . $price . ' </p>';//price
echo '<p><a href="' . $link . '">Buy Now</a></p>';//buy now from amazon
echo '<h3>Product Description</h3>';
echo '<p>' . $prod_desc . ' </p>';//product description

echo '<h3>Technical Details</h3>';
echo '<ul>';

foreach ($doc as $key => $value) {
    if(($key == 'title' ) || ($key == 'link' ) || ($key == 'prod_desc' ) || ($key == 'img_src' ) || ($key == 'price' ) )
        continue ;
    echo "<li>{$key} : {$value}</li>";
}
echo '</ul>';
?>
</body>
</html>