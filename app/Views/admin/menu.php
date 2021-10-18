<div id="fconnector_app" class="warp fconnector_app">
    <div class="fframe_app">
        <div class="fframe_main-menu-items">
            <div class="menu_logo_holder">
                <a href="<?php echo $base_url; ?>">
                    <img style="max-height: 40px;" src="<?php echo $logo; ?>" />
                    <span>beta</span>
                </a>
            </div>
            <div class="fframe_handheld"><span class="dashicons dashicons-menu-alt3"></span></div>

            <ul class="fframe_menu">
                <?php foreach ($menuItems as $item): ?>
                    <?php $hasSubMenu = !empty($item['sub_items']); ?>
                    <li data-key="<?php echo $item['key']; ?>" class="fframe_menu_item <?php echo ($hasSubMenu) ? 'fframe_has_sub_items' : ''; ?> fframe_item_<?php echo $item['key']; ?>">
                        <a class="fframe_menu_primary" href="<?php echo $item['permalink']; ?>">
                            <?php echo $item['label']; ?>
                            <?php if($hasSubMenu){ ?>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <?php } ?></a>
                        <?php if($hasSubMenu): ?>
                            <div class="fframe_submenu_items">
                                <?php foreach ($item['sub_items'] as $sub_item): ?>
                                    <a href="<?php echo $sub_item['permalink']; ?>"><?php echo $sub_item['label']; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if($secondaryItems): ?>
                <ul class="frame_secondary_menu fframe_menu">
                    <?php foreach ($secondaryItems as $item): ?>
                        <?php $hasSubMenu = !empty($item['sub_items']); ?>
                        <li data-key="<?php echo $item['key']; ?>" class="fframe_menu_item <?php echo ($hasSubMenu) ? 'fframe_has_sub_items' : ''; ?> fframe_item_<?php echo $item['key']; ?>">
                            <a class="fframe_menu_primary" href="<?php echo $item['permalink']; ?>">
                                <?php echo $item['label']; ?>
                                <?php if($hasSubMenu){ ?>
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                <?php } ?></a>
                            <?php if($hasSubMenu): ?>
                                <div class="fframe_submenu_items">
                                    <?php foreach ($item['sub_items'] as $sub_item): ?>
                                        <a href="<?php echo $sub_item['permalink']; ?>"><?php echo $sub_item['label']; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="fframe_body">
            <div class="fs_route_wrapper">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
