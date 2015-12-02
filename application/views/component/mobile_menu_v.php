<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php 
//print_r($mobile_menu_list);
$mobile_menu_list = array_reverse($mobile_menu_list);
?>

<a href="javascript:void(0);" id="mobile_nav_btn">
</a>

<div id="mobile_menu">
    <div id="mobile_menu_tabs">
        <ul id="nav-tabs">
            <?php foreach ($mobile_menu_list as $i => $menuTop):?>
            <li><a href="#tabs-<?=$i?>"><?=$menuTop['name']?></a></li>
            <?php endforeach;?>
        </ul>
        
        <?php foreach ($mobile_menu_list as $i => $menuTop):?>
        <div id="tabs-<?=$i?>">
            <ul class="mobile_sub_menu">
                <li>
                    <span data-url="/<?=$menuTop['full_uri']?>" data-anchor="<?=$menuTop['name']?> / Главная" ></span>
                    <!--<a href="/<?=$menuTop['full_uri']?>"><?=$menuTop['name']?> / Главная</a>-->
                </li>
            <?php foreach ($menuTop['sub_menu'] as $subMenu):?>
                <li>
                    <span data-url="/<?=$subMenu['full_uri']?>" data-anchor="<?=$subMenu['name']?>" ></span>
                    <!--<a href="/<?=$subMenu['full_uri']?>"><?=$subMenu['name']?></a>-->
                    
                    <?php if(isset($subMenu['sub_cat_list'])):?>
                    <ul class="mobile_sub_menu mobile_sub_sub_menu">
                        <?php foreach ($subMenu['sub_cat_list'] as $subSubMenu):?>
                        <li>
                            <span data-url="/<?=$subSubMenu['full_uri']?>" data-anchor="<?=$subSubMenu['name']?>" ></span>
                            <!-- <a href="/<?=$subSubMenu['full_uri']?>"><?=$subSubMenu['name']?></a> -->
                        </li>
                        <?php endforeach;?>
                    </ul>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
        <?php endforeach;?>
    </div>
</div>

