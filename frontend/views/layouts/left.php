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
                        'label' => Yii::t('app', 'Articles'),
                        'icon' => 'list',
                        'url' => ['/articles'],
                    ],
                    [
                        'label' => Yii::t('app', 'Parsers result'),
                        'icon' => 'history',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Parse Exist'), 'icon' => 'cogs', 'url' => ['/parse-exist'],],
                            ['label' => Yii::t('app', 'Parse Autopiter'), 'icon' => 'cogs', 'url' => ['/parse-autopiter'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
