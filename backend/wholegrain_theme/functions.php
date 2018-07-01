<?php
// if reciving AJAX request for recipes, relax security header
// to allow external domains access
if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "get_recipes" ) {
    header('Access-Control-Allow-Origin: *');
}


// create recipe custom post type
function create_post_type() {
    $labels = array(
		'name'               => __( 'Recipes' ),
		'singular_name'      => __( 'Recipe' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Recipe' ),
		'edit_item'          => __( 'Edit Recipe' ),
		'new_item'           => __( 'New Recipe' ),
		'all_items'          => __( 'All Recipes' ),
		'view_item'          => __( 'View Recipe' ),
		'search_items'       => __( 'Search Recipes' ),
		'not_found'          => __( 'No Recipes found' ),
		'not_found_in_trash' => __( 'No Recipes found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Recipes'
	);

  register_post_type( 'recipe',
    array(
      'labels' => $labels,
      'public' => true,
      'supports' => array( 'title', 'editor' ),
      'taxonomies' => array( 'category' )

    )
  );
}
add_action( 'init', 'create_post_type' );


// Fuction to run on recipe save
add_action('pre_post_update', 'save_recipe_meta', 10, 2);


// if user is on recipe post page ( existing or new ) then execute recipe meta initialization
$gtid = isset($_GET['post']) ? intval($_GET['post']) : null;
if (get_post_type($gtid) == 'recipe' || isset($_GET['post_type']) && $_GET['post_type'] == 'recipe' ) {
    add_action('admin_init','recipe_meta_init');
}


// action to initialize meta box
function recipe_meta_init() {
    add_action('add_meta_boxes', 'add_custom_meta_box');
}


// Add the Ingredients Meta Box
function add_custom_meta_box() {
    add_meta_box(
        'recipe_meta', // $id
        'Ingredients', // $title
        'show_custom_meta_box', // $callback
        'recipe', // $page
        'advanced', // $context
        'high' // $priority
    );
}


// create custom meta box containing ingredients WYSIWYG editor
function show_custom_meta_box() {
    global $post;
    $meta = get_post_meta($post->ID, "ingredients", true);
    //$action = get_current_screen()->action;
    ?>
    <div id="wholegrain-ingredients-meta">
        <input type="hidden" name="wholegrain_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
        <?php
        $args = array('editor_height' => 250);
        $ingredients = (isset($meta)) ? $meta : "";
        wp_editor( $ingredients, 'ingredients' ,$args );
        ?>
    </div>
    <?php
}


// store ingredient meta on post publish/update
function save_recipe_meta($post_id){

    // verify nonce
    if (!wp_verify_nonce($_POST['wholegrain_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    $ingredients = $_POST['ingredients'];
    update_post_meta($post_id, 'ingredients', $ingredients);
}




// RECIPE FETCH AJAX FUNCTIONS
// ---------------------------

function get_recipes(){

    // define recipe loop parameters
    $query_props = array(
        'post_type'=> 'recipe',
        'posts_per_page' => -1
    );

    // query all recipes
    query_posts( $query_props );

    // create fresh array to store any recipes found during loop
    $recipes = array();

    // the recipe loop
    if ( have_posts() ) : while ( have_posts() ) : the_post();

        // create categories array containing only category names
        $categories = array_map("cat_map", get_the_category());

        // create information array for this recipe
        $recipe = array(
            'title'=>get_the_title(),
            'content'=>get_the_content(),
            'categories'=>$categories,
            'ingredients'=>get_post_meta(get_the_ID(),'ingredients')[0],
            'permalink'=>get_the_permalink()
        );

        // add this recipe to array
        array_push($recipes,$recipe);

    endwhile; endif; // end loop

    // encode recipe array into a string and return to query source
    $return = json_encode($recipes);
    echo $return;
    die();
}

// function to return only category names
function cat_map($c){
    return($c->name);
}


// add get_recipe AJAX actions
add_action( 'wp_ajax_get_recipes', 'get_recipes' );
add_action( 'wp_ajax_nopriv_get_recipes', 'get_recipes' );
