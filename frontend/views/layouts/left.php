<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => Yii::t('app', 'Clients'),
                        'icon' => 'users',
                        'url' => ['/clients'],
                    ],
                    [
                        'label' => Yii::t('app', 'Projects'),
                        'icon' => 'list',
                        'url' => ['/projects'],
                    ],
                    [
                        'label' => Yii::t('app', 'Passwords'),
                        'icon' => 'lock',
                        'url' => ['/passwords'],
                    ],
                    [
                        'label' => Yii::t('app', 'Tasks'),
                        'icon' => 'tasks',
                        'url' => ['/tasks'],
                    ],
                    [
                        'label' => Yii::t('app', 'Stats'),
                        'icon' => 'history',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Stats'), 'icon' => 'cogs', 'url' => ['/'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
