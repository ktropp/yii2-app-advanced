<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\User;

AppAsset::register($this);
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
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $item = Yii::$app->controller->action->id;
    $controllerId = Yii::$app->controller->id;
    $url_id = Yii::$app->request->get('id');

    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandImage' => Yii::$app->homeUrl . "img/logo.svg",
        'brandUrl' => Yii::$app->homeUrl,
        'headerContent' => '<a class="navbar-text" href="' . Yii::$app->homeUrl . '">' . Yii::$app->name . '</a>',
        'innerContainerOptions' => ['class' => 'container-fluid'],
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Přejít na web', 'url' => Yii::$app->urlManagerFrontend->createUrl(['']), 'linkOptions' => ['target' => '_blank','class' => 'btn']];
        $menuItems[] = ['label' => 'Přihlásit se', 'url' => ['/admin/user/login']];
    } else {
        $menuItems[] = ['label' => 'Přejít na web', 'url' => Yii::$app->urlManagerFrontend->createUrl(['']), 'linkOptions' => ['target' => '_blank','class' => 'btn']];

        $menuItems[] =  [
            'label' => 'Můj profil',
            'url' => ['/admin/user/update', 'id' => Yii::$app->user->id],
            'active' => $controllerId == 'user' && $item == "update" && $url_id == Yii::$app->user->id,
        ];

        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Odhlásit se (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <?php
                use kartik\sidenav\SideNav;
                if (!Yii::$app->user->isGuest) {

                    //use kartik\widgets\SideNav;
                    $menuItems = [];
                    $menuItems[] = ['label' => 'Nástěnka', 'url' => ['/site/index'], 'active' => $controllerId == 'site'];
                    $menuItems[] = ['label' => 'Média', 'url' => ['/imagemanager'], 'active' => $controllerId == 'manager'];

                    //IF IS ADMIN
                    if(User::hasRole("admin", Yii::$app->user->id) || User::hasRole("superadmin", Yii::$app->user->id)){
                        $submenuItems = [];
                        if(User::hasRole("superadmin", Yii::$app->user->id)){
                            $submenuItems[] = [
                                'label' => 'Administrátoři',
                                'url' => ['/admin/user'],
                                'active' => $controllerId == 'user' && $item == "index",
                            ];
                            $submenuItems[] =  ['label' => 'Přiřazení uživatelských rolí', 'url' => ['/admin'], 'active' => $controllerId == 'assignment' && $item == "index"];
                            $submenuItems[] =  ['label' => 'Uživatelské role', 'url' => ['/admin/role'], 'active' => $controllerId == 'role' && $item == "index"];
                            $submenuItems[] =  ['label' => 'Uživatelské cesty', 'url' => ['/admin/route'], 'active' => $controllerId == 'route' && $item == "index"];
                            $submenuItems[] =  ['label' => 'Uživatelské práva', 'url' => ['/admin/permission'], 'active' => $controllerId == 'permission' && $item == "index"];
                        }
                        $menuItems[] =  [
                            'label' => 'Administrátoři',
                            'url' => ['/admin/user'],
                            'active' => in_array($controllerId, ['user', 'assignment', 'role', 'route', 'permission']),
                            'items' => $submenuItems,
                        ];
                    }

                    echo SideNav::widget(['items' => $menuItems, 'heading' => false, "encodeLabels" => false]);
                }
                ?>
            </div>
            <div class="col-lg-10">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'homeLink' => [
                        'label' => Yii::$app->name,
                        'url' => Yii::$app->defaultRoute
                    ]
                ]) ?>
                <?php if(isset($this->params['back_link'])): ?>
                    <a class="back_link btn btn-primary" href="<?= Url::to(isset($this->params['back_link']) ? $this->params['back_link'] : ""); ?>">Vrátit se zpět</a>
                <?php endif; ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<script type="text/javascript">
    $(document).ready(function(){

        $('.kv-grid-container').doubleScroll({
            resetOnWindowResize: true,
        });

        $(".js-tabs a").click(function (e){
            e.preventDefault;
            $(this).parents('.js-tabs').find(".nav-item").removeClass('active');
            $(this).parents('.nav-item').addClass('active');
            $('.content').hide(); // hides all content divs
            $($(this).attr('href') ).show();
        });

        $(".js-slug_generate_btn:visible").click(function(e){
            e.preventDefault();
            var $btn = $(this);
            $.ajax({
                url: '<?php echo Url::to(['/tools/generate-slug']) ?>',
                type: 'post',
                data: {
                    title: $('.js-slug_generate:visible').val(),
                    _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                },
                success: function (data) {
                    $(".js-slug_receiver:visible").val(data.slug);
                }
            });
        });
    });

</script>
</body>
</html>
<?php $this->endPage() ?>
