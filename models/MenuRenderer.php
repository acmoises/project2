<?php

namespace Model;

use Interface\MenuRenderInterface;

class MenuRenderer implements MenuRenderInterface
{
    public function renderMenu($menuItems) {
        echo '<ul>';
        foreach ($menuItems as $menu) {
            echo '<li>';
            echo '<a href="javascript:void(0)" onclick="showDescription(\'' . $menu->description . '\')">';
            echo htmlspecialchars($menu->name);
            echo '</a>';
            if (!empty($menu->children)) {
                $this->renderMenu($menu->children);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}