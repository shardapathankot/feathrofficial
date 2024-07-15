<?php 
global $post;
$permalink = get_permalink($post->ID);
$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
$featured_image = $featured_image['0'];
$post_title = rawurlencode(get_the_title($post->ID));
?>

<div class="post-social">
	<ul>
    	<li><a class="social-facebook" title="facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>&amp;images=<?php echo esc_url($featured_image); ?>"><i class="fa fa-facebook"></i></a></li>
        <li><a class="social-twitter" title="twitter" target="_blank" href="https://twitter.com/share?url=<?php echo esc_url($permalink); ?>&amp;text=Check out this <?php echo esc_url($permalink); ?>"><i class="fa fa-twitter"></i></a></li>
        <li><a class="social-google" title="googleplus" target="_blank" href="https://plus.google.com/share?url=<?php echo esc_url($permalink); ?>"><i class="fa fa-google"></i></a></li>
        <li><a class="social-linkedin" title="linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url($permalink); ?>"><i class="fa fa-linkedin"></i></a></li>
        <li><a class="social-send" title="email" href="mailto:?subject=<?php echo esc_html( $post_title ); ?>&amp;body=<?php echo esc_url($permalink); ?>"><i class="fa fa-envelope"></i></a></li>
    </ul>
</div>