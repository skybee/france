<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php foreach( $mainpage_cat_list as $catlist_ar ): ?>

<div class="cat_listing">

    <div class="header">
        <h1>
            <a href="/<?=$catlist_ar['s_cat_ar']['full_uri']?>">
                <?=$catlist_ar['s_cat_ar']['name']?>
            </a>
        </h1>
    </div><!-- #header -->

    <div class="content">
        <div class="left">
            <div class="imgholder">
                <a href="<?="/{$catlist_ar['s_cat_ar']['full_uri']}-{$catlist_ar[0]['id']}-{$catlist_ar[0]['url_name']}/"?>">
                    <!--medium--><img src="/upload/images/real/<?=$catlist_ar[0]['main_img']?>" alt="<?=$catlist_ar[0]['title']?>" border="0" onerror="imgError(this);" />
                </a>
                <div class="description"><h3><a href="<?="/{$catlist_ar['s_cat_ar']['full_uri']}-{$catlist_ar[0]['id']}-{$catlist_ar[0]['url_name']}/"?>"><?=$catlist_ar[0]['title']?></a></h3></div><!-- #description -->
            </div><!-- #imgholder -->
        </div><!-- #left -->
        
        
        <div class="right">
            <?php if( isset($catlist_ar[1]) ): ?>
            <div class="small-listing">
                <div class="thumb">
                    <a href="<?="/{$catlist_ar[1]['full_uri']}-{$catlist_ar[1]['id']}-{$catlist_ar[1]['url_name']}/"?>">
                        <img src="/upload/images/small/<?=$catlist_ar[1]['main_img']?>" alt="<?=$catlist_ar[1]['title']?>"  border="0" onerror="imgError(this);" />
                    </a>
                </div><!-- #thumb -->
                <div class="description"><h4><a href="<?="/{$catlist_ar[1]['full_uri']}-{$catlist_ar[1]['id']}-{$catlist_ar[1]['url_name']}/"?>"><?=$catlist_ar[1]['title']?></a></h4></div><!-- #description -->
            </div><!-- #small-listing -->
            <?php endif; ?>
            
            <?php if( isset($catlist_ar[2]) ): ?>
            <div class="small-listing">
                <div class="thumb">
                    <a href="<?="/{$catlist_ar[2]['full_uri']}-{$catlist_ar[2]['id']}-{$catlist_ar[2]['url_name']}/"?>">
                        <img src="/upload/images/small/<?=$catlist_ar[2]['main_img']?>" alt="<?=$catlist_ar[2]['title']?>"  border="0" onerror="imgError(this);" />
                    </a>
                </div><!-- #thumb -->
                <div class="description"><h4><a href="<?="/{$catlist_ar[2]['full_uri']}-{$catlist_ar[2]['id']}-{$catlist_ar[2]['url_name']}/"?>"><?=$catlist_ar[2]['title']?></a></h4></div><!-- #description -->
            </div><!-- #small-listing -->
            <?php endif; ?>
            
            <?php if( isset($catlist_ar[3]) ): ?>
            <div class="small-listing">
                <div class="thumb">
                    <a href="<?="/{$catlist_ar[3]['full_uri']}-{$catlist_ar[3]['id']}-{$catlist_ar[3]['url_name']}/"?>">
                        <img src="/upload/images/small/<?=$catlist_ar[3]['main_img']?>" alt="<?=$catlist_ar[3]['title']?>"  border="0" onerror="imgError(this);" />
                    </a>
                </div><!-- #thumb -->
                <div class="description"><h4><a href="<?="/{$catlist_ar[3]['full_uri']}-{$catlist_ar[3]['id']}-{$catlist_ar[3]['url_name']}/"?>"><?=$catlist_ar[3]['title']?></a></h4></div><!-- #description -->
            </div><!-- #small-listing -->
            <?php endif; ?>
            
        </div><!-- #right -->
    </div><!-- #content -->
</div><!-- #listing -->

<?php endforeach; ?>

<?php
//    if($_SERVER['REQUEST_URI'] == '/'){
//        echo '<div style="display:none;">'."\n";
//        
//        echo '<a href="/sitemap_link_page/news/">Sitemap 1</a>'."\n";
//        for($i=2; $i<=100; $i++){
//            echo '<a href="/sitemap_link_page/news/'.$i.'/">Sitemap '.$i.'</a>'."\n";
//        }
//        
//        echo '<a href="/sitemap_link_page/hi-tech/">Sitemap 1</a>'."\n";
//        for($i=2; $i<=50; $i++){
//            echo '<a href="/sitemap_link_page/hi-tech/'.$i.'/">Sitemap '.$i.'</a>'."\n";
//        }
//        
//        echo '</div>';
//    }
?>

