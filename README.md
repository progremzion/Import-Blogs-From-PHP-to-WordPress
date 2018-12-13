# Import-Blogs-From-PHP-to-WordPress

Howdy Programmers!

Please follow below steps to run this script.

1) Import CATEGORY_TABLE and BLOG_TABLE from PHP site to your local server.
2) Create a fresh build OR You can use existing build and place this script in your wp-root folder.
3) Change the connection settings
4) Make sure you have updated below lines before running this script. You can also add or remove parameters as per your requirement.
$my_cat = array('cat_name' => 'CATEGORY_NAME', 'category_nicename' => 'CATEGORY_SLUG', 'category_parent' => '');

$post_id = wp_insert_post(array(
    'post_date' => 'POST_DATE',
    'post_content' => 'POST_CONTENT',
    'post_name' => 'POST_NAME',
    'post_status' => 'publish',
    'post_category' => array($cate_arry[$row['category_id']])
));

5) Place image folder under the wp-content as blog_images
Generate_Featured_Image( 'wp-content/blog_images/'.$row['url'],   $post_id );

6) That's it run this script.
