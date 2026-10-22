<?php
get_header();
?>

<div class="content-area">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 40px;">
                <header class="entry-header">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="posted-on">Posted on <?php echo get_the_date(); ?></p>
                </header>
                <div class="entry-content" style="margin-top: 15px;">
                    <?php the_excerpt(); ?>
                </div>
            </article>
            <?php
        endwhile;

        the_posts_navigation();

    else :
        ?>
        <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'custom-starter' ); ?></p>
        <?php
    endif;
    ?>
</div>

<?php
get_footer();
