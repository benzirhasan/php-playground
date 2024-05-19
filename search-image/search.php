<?php
/**
 * This file will search image from a CSV file
 */

// Path to the CSV file
$csvFile = 'product.csv';
// Path to the directory where images are stored
$imageDir = 'images/';

// Function to check if the image exists in the directory
function checkImageExists($imageName, $directory) {
    //echo $directory . $imageName;
    return ($imageName == NULL)? false : file_exists($directory . $imageName);
}

// Function to extract the image file name from the URL
function getImageFileName($url) {
    return basename(parse_url($url, PHP_URL_PATH));
}

// Open the CSV file
/if (($handle = fopen($csvFile, 'r')) !== false) {
    // Read the header row
    $header = fgetcsv($handle, 1000, ",");

    // Find the index of the "Images" column
    $imageIndex = array_search('Images', $header);

    $result = ['exist' => [], 'notExist' => []];
    if ($imageIndex !== false) {
        // Read the data rows
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // echo '<pre>';
            // print_r($data);exit;
            // Extract the image file name
            $imageFileName = getImageFileName($data[$imageIndex]);

            //echo "$imageFileName<br/>";
            if (checkImageExists($imageFileName, $imageDir)) {
                $result['exist'][$data[0]] = $imageFileName;
                //echo "Image $imageName exists in the directory.<br/>";
            } else {
                $result['notExist'][$data[0]] = $imageFileName;
                //echo "Image $imageName does not exist in the directory.<br/>";
            }
        }
    } else {
        echo "No 'Images' column found in the CSV file.<br/>";
    }

    // Close the CSV file
    fclose($handle);

    echo '<pre>';
    //print_r($result);

    // Iterate over the array
    foreach ($result['exist'] as $index => $value) {
        // Convert index to string to ensure strpos works correctly
        $indexStr = (string)$index;
        
        // Check if index name exists in the value name
        if (strpos($value, $indexStr) !== false) {
            //echo "Index $index exists in the value $value.<br/>";
        } else {
            echo "Index $index does not exist in the value $value.\n";
        }
    }

} else {
    echo "Failed to open the CSV file.<br/>";
}