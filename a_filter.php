<?php $list_tags = getTagSugguest('all');?>
    <div class="header_filter">
    	<img src="assets/img/filter_arrow.png" class="arrow" alt="">
        <div class="share_block">
        	share us on
            <a href="#" class="share_ico share_twitter"></a>
            <a href="#" class="share_ico share_facebook"></a>
        </div>
        <div class="search">
            <form name="search_form" method="get" action="" class="search_form">
                <input type="text" name="search_field" value="Search the Site" title="Search the Site">
            </form>
        </div>    
        <div class="filter_block">
            <div class="filter_navigation" >                
                <ul class="splitter" id="options">
                    <li>
                        <ul class="optionset" data-option-key="filter">
                            <li class="selected"><a href="#filter" data-option-value="*">ทั้งหมด</a></li>
                            <?php if(is_array($list_tags)) {
								foreach($list_tags as $tg) {?>
									<li class="sep">:</li>
                            		<li><a href="#filter" data-option-value=".tag-<?php echo $tg['id']?>"><?php echo $tg['name']?></a></li>
							<?php
                            	}
							}?>
                           
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!-- .filter_block -->    	
    </div><!-- .header_filter -->