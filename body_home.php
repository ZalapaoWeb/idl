<?php
include 'a_filter.php';
$contents = getContentsList();
?>
    <!-- C O N T E N T -->
    <div class="content_wrapper">
		<div class="gallery_block">
            <div class="portfolio_block image-grid columns-grid" id="list">
                <?php
                	if (is_array($contents)) {
						foreach ($contents as $row) {
							$tags = implode(' ', $row['tags']);
																	?>
                            <div data-category="data-tag" class="<?php echo $tags?> element">
                                <div class="filter_img">
                                    <img src="img/1/360_<?php echo $row['file_name']?>" alt="" >
                                    <div class="portfolio_wrapper"></div>
                                    <div class="portfolio_content">
                                        <h5><?php echo $row['name']?></h5>
                                        <p><?php echo $row['shop_name']?></p>
                                        <span class="ico_block">
                                            <a href="img/1/ori_<?php echo $row['file_name']?>" class="ico_zoom prettyPhoto" title="<?php echo $row['name']?>" rel="prettyPhoto[album]" ><span></span></a>
                                            <a href="portfolio_post.html" class="ico_link"><span></span></a>
                                        </span>
                                    </div>
                                    <span class="post_type post_type_video"></span>
                                </div>
                            </div>
				
                <?php
						}
					}
						
				?>
                
                
            </div><!-- .portfolio_block -->
            <div class="clear"><!-- ClearFix --></div>
            <a href="#insert" class="load_more_grid" data-count="5"><img src="assets/img/btn_loadmore.png"><span>load more</span></a>
			<script type="text/javascript" src="assets/js/grid_portfolio.js"></script>
        </div>
    </div>
