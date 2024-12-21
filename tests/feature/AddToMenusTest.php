<?php

namespace Tests\PackageCrud\Feature;

use Tests\TestCase;
use Lavary\Menu\Facade as Menu;

class AddToMenusTest extends TestCase
{
    public function testAddToMenusClassAndhandleMethod()
    {
        $menu = Menu::make('sidebar_admin', function ($menu) {
            $menu->add('Home');
        });
        $menu = Menu::get('sidebar_admin')->first();

        // Add our menu item to the top nav
        $menu->add(__('CRUD'), [
            'route' => 'package.crud.index',
            'icon' => 'fa-archive',
        ]);

        $menuClass = new \ProcessMaker\Package\PackageCrud\Http\Middleware\AddToMenus();
        $request = new \Illuminate\Http\Request();
        $next = function ($request) {
            return $request;
        };
        $response = $menuClass->handle($request, $next);
        $this->assertInstanceOf(\Illuminate\Http\Request::class, $response);
    }
}
