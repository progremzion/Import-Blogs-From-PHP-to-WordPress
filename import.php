<?php

//Set execution time to 0
ini_set('max_execution_time', 0);

//Include WordPress Necessary Files
require_once("wp-load.php");
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

$servername = "DATABASE_SERVER"; //localhost
$username = "DATABASE_USER"; // root
$password = "DATABASE_PASSWORD"; // ''
$database = "DATABASE_NAME";

// Create connection
$conn = new mysqli($servername, $username, $password,$database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Get Categories
$sql = "SELECT * FROM CATEGORY_TABLE";
$result = mysqli_query($conn, $sql);

$cate_arry = array();
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $my_cat = array('cat_name' => 'CATEGORY_NAME', 'category_nicename' => 'CATEGORY_SLUG', 'category_parent' => '');

        // Create the category
        $my_cat_id = wp_insert_category($my_cat);
        $cate_arry[$row['category_id']] = $my_cat_id;

    }
} else {
    echo "0 results";
}

//Get all the posts
$sql = "SELECT * FROM BLOG_TABLE";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //Insert New Post
        $post_id = wp_insert_post(array(
            'post_date' => 'POST_DATE',
            'post_content' => 'POST_CONTENT',
            'post_name' => 'POST_NAME',
            'post_status' => 'publish',
            'post_category' => array($cate_arry[$row['category_id']])
        ));

        //Set Feature Image to that post
        Generate_Featured_Image( 'wp-content/blog_images/'.$row['url'],   $post_id );
    }
} else {
    echo "0 results";
}

/**
 * Generate Featured Image and assigned to new post
 */
function Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}

mysqli_close($conn);

echo "Imported Successfully";
?>