<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - FACILITIES</title>
    <?php require("./inc/links.php")?>
    <style>
        .pop{
            transition: transform 0.5s ease, border-top-color 0.5s ease;
        }
        .pop:hover{
            border-top-color: var(--teal)!important;
            transform: scale(1.03);
        }
    </style>
</head>

<body class="bg-light">
   
    <?php require("./inc/header.php") ?>


    <!-- our facilities -->
    <div class="my-4 text-center">
        <h4 class="h-font fw-bold fs-2">OUR FACILITIES</h4>
        <div class="h-line bg-dark"></div>
        <p class="mt-3">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam numquam illum deserunt?<br> In error blanditiis voluptatum nesciunt, neque consectetur maiores.</p>
    </div>

    <div class="container mt-5">
        <div class="row">
            <?php 
            $facilities_q = "SELECT * FROM `facilities`";
            $res = $conn->query($facilities_q);
            while($row = mysqli_fetch_assoc($res)){
                $path = FACILITIES_IMAGE_FOLDER_PATH.$row['picture'];
                echo <<<data
                    <div class="col-lg-4 col-md-6 mb-3 px-3">
                        <div class="p-4 bg-white border-top h-100 border-4 border-dark pop rounded shadow">
                            <div class="d-flex align-items-center">
                                <img src="$path" width="50px">
                                <h6 class="m-0 ms-3">$row[name]</h6>
                            </div>
                            <p class="mt-3">$row[description]</p>
                        </div>    
                    </div>
                data;
            }
            ?>
        </div>
    </div>
    <!-- our facilities end -->


    <?php require("./inc/footer.php") ?>
</body>

</html>