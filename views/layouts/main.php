<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Button;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$bodyClass = [];
$items = array();

$Currency = Yii::$app->params['currency'];
$Unit = Yii::$app->params['unit'];

$items_default = [
    [
        'label' => 'Заказы',
        'active' => in_array(Yii::$app->controller->id, ['orders']),
        'items' => [
            ['label' => 'Заказы',
                'url' => ['/orders']
            ],
            ['label' => 'Заказы сегодня',
                'url' => ['/orders', 'Orders_Search' => ['date_add' => date('Y-m-d')]],
            ],
        ]],
    [
        'label' => 'Инвойсы',
        'url' => ['/invoices'],
        'active' => in_array(Yii::$app->controller->id, ['invoices']),
        'items' => [
            ['label' => 'Инвойсы',
                'url' => ['/invoices']
            ],
            ['label' => 'Инвойсы сегодня',
                'url' => ['/invoices', 'Invoices_Search' => ['date' => date('Y-m-d')]],
            ],
        ]

    ],
    [
        'label' => 'Расходы',
        'url' => ['/expenses'],
        'active' => in_array(Yii::$app->controller->id, ['expenses']),
        'items' => [
            ['label' => 'Расходы',
                'url' => ['/expenses']
            ],
            ['label' => 'Расходы сегодня',
                'url' => ['/expenses', 'Expenses_Search' => ['date' => date('Y-m-d')]],
            ],
        ]
    ]
];

if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin') {
    $items = [
        ['label' => 'Списание товара', 'url' => ['/charge-off-goods'], 'active' => in_array(Yii::$app->controller->id, ['charge-off-goods'])],
        ['label' => 'Рабочие дни', 'url' => ['/places-day'], 'active' => in_array(Yii::$app->controller->id, ['places-day'])],
        [
            'label' => 'Склад',
            'items' => [
                ['label' => 'Склад', 'url' => ['/stocks'], 'active' => in_array(Yii::$app->controller->id, ['stocks'])],
                ['label' => 'Инвойсы склада', 'url' => ['/invoices-stocks'], 'active' => in_array(Yii::$app->controller->id, ['invoices-stocks'])],
            ]
        ],
        [
            'label' => 'Настройки',
            'items' => [
                ['label' => 'Сотрудники', 'url' => ['/users'], 'active' => in_array(Yii::$app->controller->id, ['users'])],
                ['label' => 'Заведения', 'url' => ['/places'], 'active' => in_array(Yii::$app->controller->id, ['places'])],
                ['label' => 'Продукты', 'url' => ['/goods'], 'active' => in_array(Yii::$app->controller->id, ['goods'])],
                ['label' => 'Тех. карта', 'url' => ['/products'], 'active' => in_array(Yii::$app->controller->id, ['products'])],
                ['label' => 'Категории', 'url' => ['/categories'], 'active' => in_array(Yii::$app->controller->id, ['categories'])],
                ['label' => 'Типы расходов', 'url' => ['/expenses-type'], 'active' => in_array(Yii::$app->controller->id, ['expenses-type'])],
            ]
        ],
        ['label' => 'Выход (' . Yii::$app->user->identity->email . ')', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post', 'class' => 'text-warning']]
    ];

    $items = array_merge($items_default, $items);

} else if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'user') {

    $items = [
        ['label' => 'Списание товара', 'url' => ['/charge-off-goods'], 'active' => in_array(Yii::$app->controller->id, ['charge-off-goods'])],
        ['label' => 'Рабочие дни', 'url' => ['/places-day'], 'active' => in_array(Yii::$app->controller->id, ['places-day'])],
        ['label' => 'Выход (' . Yii::$app->user->identity->email . ')', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']]
    ];

    $items = array_merge($items_default, $items);

} else {
    $bodyClass = ['text-center'];
}
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <script>
            var Currency = '<?php echo $Currency;?>';
            var arrayUnit = JSON.parse('<?php echo json_encode($Unit);?>');
        </script>
        <?php $this->head() ?>
    </head>
    <body class="<?php echo implode(' ', $bodyClass); ?>">
    <?php $this->beginBody(); ?>

    <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <?php

                    $show_new = true;
                    if (isset($_COOKIE['order_id']) && !empty($_COOKIE['order_id'])) {

                        $Orders = \app\models\Orders::findOne($_COOKIE['order_id']);

                        if ($Orders) {
                            $show_new = false;
                            $newOrder = Html::a('Закрыть заказ', ['site/close-order', 'id' => $_COOKIE['order_id']], [
                                'title' => Yii::t('app', 'Закрыть заказ'),
                                'class' => 'btn_action btn btn-sm btn-success',
                                'data-pjax' => 0,
                                'data-method' => 'post',
                                'data-confirm' => 'Точно закрыть заказ?',
                                'id' => 'confirm_order'
                            ]);

                            $newOrder .= Html::tag('span', 'Сумма заказа: ' . $Orders->total . ' ' . $Currency, [
                                'class' => 'badge btn-success'
                            ]);
                        }
                    }

                    if ($show_new) {
                        $newOrder = Html::a('Новый заказ', ['site/add-order'], [
                            'title' => Yii::t('app', 'Новый заказ'),
                            'class' => 'btn_action btn btn-sm btn-primary',
                            'data-pjax' => 0,
                            'data-method' => 'post',
                            // 'data-confirm' => 'Точно новый заказ?',
                            'id' => 'new_order'
                        ]);
                    }

                    NavBar::begin([
                        'brandLabel' => 'Lucky Coffee',
                        'brandUrl' => Yii::$app->homeUrl,
                        'innerContainerOptions' => ['class' => 'container-fluid'],
                        'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
                        'headerContent' => '<div class="basket_header">' . $newOrder . '</div>'
                    ]);

                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'items' => $items,
                    ]);

                    NavBar::end();
                    ?></div>
            </div>
        </div>
    <?php } ?>

    <?php if (Yii::$app->controller->id !== 'site') { ?>
        <?php echo Breadcrumbs::widget([
            'itemTemplate' => '<li class="">{link}</li>',
            'activeItemTemplate' => '<li class=" active" aria-current="page">{link}</li>',
            'tag' => 'ul',
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
        ]); ?>
    <?php } ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <?php echo Alert::widget(['options' => ['class' => 'show']]); ?>
            </div>
        </div>
    </div>

    <div class="container-fluid ">
        <?php echo $content; ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>