<html>
<head>
    <title>Results Page</title>
    <link rel="stylesheet" type="text/css" href="includes/style.css" />
</head>

<body>

<?php require "includes/resultsfile.php"; ?>
<div id="wrap">
    <div id="header"></div>
    <div id="sidebar">


        <form method="post" action="results.php?new_query=<?php echo $query ?>">
            <strong>Filter By Brand</strong> :<br/>
            <?php
            foreach ($brands_arr as $brand_key => $brand_value) {
                echo '<input type="checkbox" name="brand[]" value="' . $brand_key . '">' . $brand_key . "({$brand_value})" . '<br />';
            }
            ?>
            <strong>Filter by Range</strong> :<br/>
            <input type="radio" name="range" value=10> 0-10,000<br/>
            <input type="radio" name="range" value=30> 10,000-20,000<br/>
            <input type="radio" name="range" value=50> 20,000 & More<br/>

            <input type="submit" value="Apply Filters" style="border-style: none; width: 94px; height: 20px;">
        </form>
    </div>

    <div id="main">
        <h3>Results</h3>
        <?php
        //start Displaying Items and Description Using Pagination
        for ($i = $start_index; $i <= $end_index; $i++) {
            $id = $results['hits']['hits'][$i]['_id'];
            $doc = $results['hits']['hits'][$i]['_source'];
            $title = $doc['title'];
            $prod_desc = $doc['prod_desc'];
            $price = $doc['price'];
            $brand_name = $doc['brand'];
            echo '<p><a class="item-links" href="details.php?id=' . $id . '">' . $title . '</a></p>';
            echo "<p>By : {$brand_name}</p>";
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
    </div>
</div>
</body>
</html>