function display_latest_posts_shortcode() {
    // Define the URL of the WordPress site's REST API endpoint
    $api_url = 'https://yourwebsite.com/wp-json/wp/v2/posts?per_page=5&_embed';

    // Fetch the latest 5 posts
    $response = wp_remote_get($api_url);
    $posts = json_decode(wp_remote_retrieve_body($response));

    // Start building the HTML output
    $output = '<div class="latest-posts">';

    // Process the retrieved posts
    foreach ($posts as $index => $post) {
        $featured_image_url = $post->_embedded->{'wp:featuredmedia'}[0]->source_url;
        $post_title = $post->title->rendered;
        $post_date = date('jS F Y', strtotime($post->date));
        $post_link = $post->link;

        // Check if it's the first post
        if ($index === 0) {
            $output .= '<div class="first-post">';
        } elseif ($index === 1) {
            $output .= '</div><div class="right-posts-widget">';
        }

        // Append post HTML to the output
        $output .= '<div class="post">';
        // Wrap image in an anchor tag to open in new tab
        $output .= '<a class="feature-image" href="' . esc_url($post_link) . '" target="_blank">';
        $output .= '<img src="' . esc_url($featured_image_url) . '" alt="' . esc_attr($post_title) . '" />';
        $output .= '</a>';
		$output .= '<div class="meta-wrapper">';
        $output .= '<h2><a href="' . esc_url($post_link) . '" target="_blank">' . esc_html($post_title) . '</a></h2>';
        $output .= '<p class="date-meta">' . esc_html($post_date) . '</p>';
		$output .= '</div>';
        $output .= '</div>';
    }
    // Close the div
    $output .= '</div></div>';
    // Return the HTML output
    return $output;
}

// Register the shortcode
add_shortcode('latest_posts', 'display_latest_posts_shortcode');
