<html>
<head>
    <title>Results Page</title>
</head>

<body>

<?php require "includes/resultsfile.php"; ?>

<h3>Apply Some Filters</h3>
<form method="post" action="results.php?new_query=<?php echo $query?>" >
    Filter By Brand :<br />
    <input type="checkbox" name="brand[]" value="samsung">Samsung<br />
    <input type="checkbox" name="brand[]" value="lenovo"> Lenovo<br />
    <input type="checkbox" name="brand[]" value="dell"> Dell<br />
    <input type="checkbox" name="brand[]" value="asus"> Asus<br />
    Filter by Range :<br />
    <input type="radio" name="range" value=10> 0-10,000<br />
    <input type="radio" name="range" value=30> 10,000-20,000<br />
    <input type="radio" name="range" value=50> 20,000 & More<br />

    <input type="submit" value="Apply Filters" style="border-style: none; width: 94px; height: 20px;">
</form>


<?php
//start Displaying Items and Description Using Pagination
for ($i = $start_index; $i <= $end_index; $i++) {
    $id = $results['hits']['hits'][$i]['_id'];
    $doc = $results['hits']['hits'][$i]['_source'];
    $title = $doc['title'];
    $prod_desc = $doc['prod_desc'];
    $price = $doc['price'];
    echo '<p><a class="item-links" href="details.php?id=' . $id . '">' . $title . '</a></p>';
    echo "<p>Price : {$price}</p>";
    echo '<p>' . $prod_desc . '</p>';
    echo '<br />';


}


if ($end_index < ($total - 1)) {//means it's not the last page
    $page_num = ($end_index + 1) / 10;
    $end_index++;
    echo '<a href="results.php?' . "start_index={$end_index}" . "&query={$query}&type={$type}&brand={$brand}&range={$range}" . '">Next</a>';
}


?>

</body>
</html>