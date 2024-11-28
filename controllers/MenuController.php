<?php

namespace Controllers;

use Model\ActiveRecord;
use Model\Menu;
use MVC\Router;

class MenuController extends ActiveRecord
{
    private $menuModel;

    public static function index(Router $router)
    {
        $query = "SELECT m.id, m.name, m.description, m.parent_id, p.name AS parent_name
                FROM menus m
                LEFT JOIN menus p ON m.parent_id = p.id";
        $menus = Menu::SQL($query);

        $router->render('menu/list', [
            'menus' => $menus
        ]);
    }

    public static function create(Router $router)
    {

        $menu = new Menu;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $menu->sincronizar($_POST);
            $result = $menu->crear();

            if($result){
                header('Location: /project2');
            }

        }

        $menus = Menu::all();
        $router->render('menu/create', [
            'menus' => $menus
        ]);
    }

    public static function update(Router $router)
    {

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $menu = Menu::find($_POST['id']);

            $menu->sincronizar($_POST);
            $result = $menu->guardar();

            if($result){
                header('Location: /project2');
            }

        }else{
            $menu = Menu::find($_GET['id']);

            if(empty($menu)){
                Menu::setAlerta('error', 'No existe el Menu');
            }

            $menus = Menu::all();
            $alertas = Menu::getAlertas();
            $router->render('menu/update', [
                'menus' => $menus,
                'menu' => $menu,
                'alertas' => $alertas
            ]);
        }

    }

    public static function delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $menu = Menu::find($_POST['id']);
            $menu->eliminar();
            header('Location: /project2');
        }
    }

    public function getHierarchicalMenu()
    {
        $menus = Menu::all('ASC');
        $menuHierarchy = [];

        $menuById = [];
        foreach ($menus as $menu) {
            $menu->children = [];
            $menuById[$menu->id] = $menu;
        }

        foreach ($menus as $menu) {
            if ($menu->parent_id) {
                $menuById[$menu->parent_id]->children[] = &$menuById[$menu->id];

            } else {
                $menuHierarchy[] = &$menuById[$menu->id];
            }
        }

        return $menuHierarchy;
    }
}