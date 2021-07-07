<?php

namespace Pagekit\Widget\Controller;

use Pagekit\Application as App;
use Pagekit\Site\Model\Node;
use Pagekit\User\Model\Role;
use Pagekit\Widget\Model\Widget;

/**
 * @Access("system: manage widgets", admin=true)
 */
class WidgetController
{
    public function indexAction(): array
    {
        return [
            '$view' => [
                'title' => __('Widgets'),
                'name' => 'system/widget/index.php'
            ],
            '$data' => [
                'widgets' => array_values(Widget::findAll()),
                'types' => App::widget()->all(),
                'config' => [
                    'menus' => App::menu(),
                    'nodes' => array_values(Node::query()->get())
                ]
            ]
        ];
    }

    /**
     * @Request({"id": "int", "type": "string"})
     */
    public function editAction($id = 0, $type = null): array
    {
        if (!$id) {
            $widget = Widget::create(['type' => $type]);
        } elseif (!$widget = Widget::find($id)) {
            App::abort(404, 'Widget not found.');
        }

        return [
            '$view' => [
                'title' => __('Widgets'),
                'name' => 'system/widget/edit.php'
            ],
            '$data' => [
                'widget' => $widget,
                'config' => [
                    'menus' => App::menu(),
                    'nodes' => array_values(Node::query()->get()),
                    'roles' => array_values(Role::findAll()),
                    'types' => array_values(App::widget()->all()),
                    'positions' => array_values(App::position()->all())
                ]
            ]
        ];
    }
}
