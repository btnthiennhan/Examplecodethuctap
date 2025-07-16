<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Breadcrumbs;

/** @var yii\web\View $this */
/** @var string $content */
$this->registerCss('
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        color: #fff;
        transition: all 0.3s;
        z-index: 1000;
    }
    .sidebar.hidden {
        left: -250px;
    }
    .sidebar .nav-link {
        color: #fff;
        padding: 10px 20px;
        display: block;
    }
    .sidebar .nav-link:hover {
        background-color: #495057;
    }
    .sidebar .dropdown-toggle::after {
        margin-left: 0.5em;
        vertical-align: middle;
    }
    .sidebar .dropdown-menu {
        background-color: #495057;
        border: none;
        padding: 0;
    }
    .sidebar .dropdown-item {
        color: #fff;
        padding: 8px 30px;
    }
    .sidebar .dropdown-item:hover {
        background-color: #6c757d;
    }
    .toggle-btn {
        position: fixed;
        top: 70px;
        left: 260px;
        z-index: 1001;
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 1.2em;
    }
    .toggle-btn:hover {
        background-color: #495057;
    }
    .toggle-btn.hidden {
        left: 10px;
    }
    .content {
        margin-left: 260px;
        padding: 80px 20px 20px 20px;
        transition: all 0.3s;
        min-height: 100vh;
    }
    .content.expanded {
        margin-left: 0;
    }
    .content-inner {
        max-width: 800px;
        margin: 0 auto;
    }
    .alert {
        position: relative;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        opacity: 0;
        animation: fadeIn 0.5s ease-in forwards;
        font-size: 1em;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    .alert .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 1.2em;
        color: inherit;
        opacity: 0.6;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
    }
    .alert .close:hover {
        opacity: 1;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .cart-button {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cart-icon {
        font-size: 1.2em;
    }
    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
        }
        .sidebar.active {
            left: 0;
        }
        .toggle-btn {
            left: 10px;
            top: 60px;
        }
        .content {
            margin-left: 0;
            padding: 70px 15px 15px 15px;
        }
        .alert {
            font-size: 0.9em;
            padding: 10px 15px;
        }
    }
');
$this->registerJs('
    $(document).ready(function() {
        $("#toggleSidebar").click(function() {
            $(".sidebar").toggleClass("hidden active");
            $("#toggleSidebar").toggleClass("hidden");
            $(".content").toggleClass("expanded");
        });
        // Xử lý dropdown bằng jQuery
        $(".dropdown-toggle").click(function(e) {
            e.preventDefault();
            $(this).next(".dropdown-menu").slideToggle(200);
        });
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(function() {
            $(".alert").fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
        // Xử lý nút đóng thông báo
        $(".alert .close").click(function() {
            $(this).parent().fadeOut(300, function() {
                $(this).remove();
            });
        });
    });
');
?>

<div class="sidebar" id="sidebar">
    <h3 class="text-center pt-3"><?= Html::encode(Yii::t('app', 'Menu')) ?></h3>
    <ul class="nav flex-column">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="productDropdown">
                <?= Yii::t('app', 'Product') ?>
            </a>
            <ul class="dropdown-menu" style="display: none;">
                <li>
                    <?= Html::a(Yii::t('app', 'Create Product'), ['product/create'], ['class' => 'dropdown-item']) ?>
                </li>
                <li>
                    <?= Html::a(Yii::t('app', 'View Products'), ['product/index'], ['class' => 'dropdown-item']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown">
                <?= Yii::t('app', 'Category') ?>
            </a>
            <ul class="dropdown-menu" style="display: none;">
                <li>
                    <?= Html::a(Yii::t('app', 'Create Categories'), ['category/create'], ['class' => 'dropdown-item']) ?>
                </li>
                <li>
                    <?= Html::a(Yii::t('app', 'View Categories'), ['category/index'], ['class' => 'dropdown-item']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('app', 'Manage Users'), ['user/index'], ['class' => 'nav-link']) ?>
        </li>
<!-- Thành dòng này -->
<li class="nav-item">
    <a href="<?= Url::to(['cart/index']) ?>" class="nav-link cart-button">
        <span class="cart-icon">🛒</span> <?= Yii::t('app', 'Giỏ hàng') ?>
    </a>
</li>
        <li class="nav-item">
    <?php if (!Yii::$app->user->isGuest): ?>
        <!-- Hiển thị nút Logout khi người dùng đã đăng nhập -->
        <?= Html::a(Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')', ['site/logout'], ['class' => 'nav-link', 'data-method' => 'post']) ?>
    <?php endif; ?>
</li>

    </ul>
</div>

<button id="toggleSidebar" class="toggle-btn"><?= Html::encode(Yii::t('app', '☰')) ?></button>

<div class="content">
    <div class="content-inner">
        <!-- Hiển thị flash messages -->
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
                <button type="button" class="close">&times;</button>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error') ?>
                <button type="button" class="close">&times;</button>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('info')): ?>
            <div class="alert alert-info">
                <?= Yii::$app->session->getFlash('info') ?>
                <button type="button" class="close">&times;</button>
            </div>
        <?php endif; ?>

        <?= $content ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
</div>