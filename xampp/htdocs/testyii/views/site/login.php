<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Tài khoản';
$this->params['breadcrumbs'][] = $this->title;


$activeRegister = Yii::$app->session->hasFlash('registerError');
$activeLogin = Yii::$app->session->hasFlash('error') || Yii::$app->request->get('login') === '1';

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="auth-wrapper position-relative <?= Yii::$app->session->hasFlash('registerError') ? 'active' : '' ?>">
                <div id="particle-background" class="particle-background"></div>

                <div class="form-box d-flex position-relative w-100 h-100">
                    <!-- Login Form -->
                    <div class="form-container login-form p-5 shadow animate__animated animate__fadeInLeft">
                        <h2 class="text-center text-primary mb-3 fw-bold animate__animated animate__pulse">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </h2>

                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="toast-container position-fixed top-0 end-0 p-3">
                                <div class="toast align-items-center text-bg-danger border-0 show fade animate__animated animate__bounceInRight" role="alert">
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <?= Yii::$app->session->getFlash('error') ?>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'action' => ['site/login'],
                            'options' => ['class' => 'mt-4'],
                            'fieldConfig' => [
                                'template' => "<div class=\"form-floating mb-3 animate__animated animate__fadeInUp\">{input}{label}</div>{error}",
                                'labelOptions' => ['class' => 'form-label'],
                                'inputOptions' => ['class' => 'form-control input-glow', 'placeholder' => ' '],
                                'errorOptions' => ['class' => 'invalid-feedback d-block animate__animated animate__shakeX'],
                            ],
                        ]); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <div class="form-check mb-3 animate__animated animate__fadeIn">
                            <?= $form->field($model, 'rememberMe', [
                                'template' => "<div class=\"form-check\">{input} {label}</div>{error}",
                                'labelOptions' => ['class' => 'form-check-label'],
                                'inputOptions' => ['class' => 'form-check-input'],
                            ])->checkbox() ?>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <?= Html::submitButton('<i class="bi bi-box-arrow-in-right"></i> Đăng nhập', ['class' => 'btn btn-primary btn-lg btn-animated']) ?>
                        </div>

                        <div class="text-center">
                            <a href="#" class="btn btn-link text-decoration-none toggle-panel text-secondary animate__animated animate__fadeIn">
                                <i class="bi bi-person-plus"></i> Chưa có tài khoản? Đăng ký
                            </a>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>

                    <!-- Register Form -->
                    <div class="form-container register-form p-5 shadow animate__animated animate__fadeInRight">
                        <h2 class="text-center text-success mb-3 fw-bold animate__animated animate__pulse">
                            <i class="bi bi-person-plus"></i> Đăng ký
                        </h2>
                        <?php if (Yii::$app->session->hasFlash('registerError')): ?>
                            <div class="toast-container position-fixed top-0 end-0 p-3">
                                <div class="toast align-items-center text-bg-danger border-0 show fade animate__animated animate__bounceInRight" role="alert">
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <?= Yii::$app->session->getFlash('registerError') ?>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


                        <?php $form = ActiveForm::begin([
                            'id' => 'register-form',
                            'action' => ['site/register'],
                            'options' => ['class' => 'mt-4'],
                            'fieldConfig' => [
                                'template' => "<div class=\"form-floating mb-3 animate__animated animate__fadeInUp\">{input}{label}</div>{error}",
                                'labelOptions' => ['class' => 'form-label'],
                                'inputOptions' => ['class' => 'form-control input-glow', 'placeholder' => ' '],
                                'errorOptions' => ['class' => 'invalid-feedback d-block animate__animated animate__shakeX'],
                            ],
                        ]); ?>

                        <?= $form->field($registerModel, 'username')->textInput() ?>
                        <?= $form->field($registerModel, 'password')->passwordInput() ?>

                        <div class="d-grid gap-2 mb-3">
                            <?= Html::submitButton('<i class="bi bi-person-plus"></i> Đăng ký', ['class' => 'btn btn-success btn-lg btn-animated']) ?>
                        </div>

                        <div class="text-center">
                            <a href="#" class="btn btn-link text-decoration-none toggle-panel text-secondary animate__animated animate__fadeIn">
                                <i class="bi bi-box-arrow-in-left"></i> Đã có tài khoản? Đăng nhập
                            </a>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

                <div class="overlay-panel d-none d-md-flex align-items-center justify-content-center">
                    <div class="text-center text-white animate__animated animate__zoomIn">
                        <h3 class="mb-3">Chào mừng bạn!</h3>
                        <p>Vui lòng đăng nhập hoặc đăng ký để tiếp tục.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- External CSS/JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

/* Điều chỉnh style */
<style>
.auth-wrapper {
    position: relative;
    min-height: 600px;
    overflow: hidden;
    background: linear-gradient(45deg, #6b7280, #1e40af);
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    display: flex;
}

.particle-background {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    z-index: 0;
}

.form-box {
    display: flex;
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 1;
}

.form-container {
    width: 50%;
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    transition: transform 0.6s ease-in-out, opacity 0.6s ease-in-out;
    position: absolute;
    top: 0;
    height: 100%;
}

.login-form {
    left: 0;
    transform: translateX(0);
    opacity: 1;
}

.register-form {
    right: 0;
    transform: translateX(0);
    opacity: 0;
    display: none;
}

.auth-wrapper.active .login-form {
    transform: translateX(-100%);
    opacity: 0;
    display: none;
}

.auth-wrapper.active .register-form {
    transform: translateX(-100%);
    opacity: 1;
    display: block;
}

.overlay-panel {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 50%;
    height: 100%;
    background: #0d6efd;
    transition: transform 0.6s ease-in-out;
    z-index: 2;
    border-radius: 1rem;
    color: white;
    padding: 2rem;
    pointer-events: none;
}

.overlay-panel {
    right: 0;
    transform: translateX(0);
}

.auth-wrapper.active .overlay-panel {
    transform: translateX(-100%);
}

/* Cải thiện màu chữ */
h2.text-primary, h2.text-success {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Thêm bóng chữ để tăng độ tương phản */
}

.text-secondary.toggle-panel {
    color: #111827 !important; /* Màu xám đậm thay vì text-secondary */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); /* Thêm bóng nhẹ */
    font-weight: 500; /* Làm đậm chữ */
}

.overlay-panel .text-white {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4); /* Thêm bóng chữ cho văn bản trong overlay */
    font-weight: 600; /* Làm đậm chữ */
}

/* Các style còn lại giữ nguyên */
.input-glow {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.input-glow:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    transform: scale(1.02);
}

.btn-animated {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-animated::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    width: 0; height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.btn-animated:hover::after {
    width: 200%;
    height: 200%;
}

.btn-animated:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.form-floating > .form-control {
    height: calc(3.5rem + 2px);
    line-height: 1.25;
    padding: 1rem 0.75rem 0 0.75rem;
}

.form-floating label {
    transition: all 0.3s ease;
    padding: 0 0.2rem;
    background-color: transparent;
    color: #374151;
    font-size: 1rem;
}

.form-floating input:focus + label,
.form-floating input:not(:placeholder-shown) + label {
    transform: translateY(-2rem) scale(0.75);
    opacity: 1;
    color: #1e40af;
    background-color: transparent;
}

.toast {
    z-index: 1055;
    backdrop-filter: blur(10px);
    background: rgba(220, 38, 38, 0.9);
    animation-duration: 0.5s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    particlesJS('particle-background', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: '#ffffff' },
            shape: { type: 'circle' },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: {
                enable: true,
                distance: 150,
                color: '#ffffff',
                opacity: 0.4,
                width: 1
            },
            move: {
                enable: true,
                speed: 2,
                direction: 'none',
                random: true,
                straight: false,
                out_mode: 'out',
                bounce: false
            }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: { enable: true, mode: 'repulse' },
                onclick: { enable: true, mode: 'push' },
                resize: true
            }
        },
        retina_detect: true
    });

    // Toggle between login and register
    const wrapper = document.querySelector('.auth-wrapper');
    const toggleButtons = document.querySelectorAll('.toggle-panel');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            wrapper.classList.toggle('active');
        });
    });

    // Toast auto show
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.forEach(function (toastEl) {
        new bootstrap.Toast(toastEl).show();
    });

    // Input pulse effect
    document.querySelectorAll('.input-glow').forEach(input => {
        input.addEventListener('focus', () => {
            input.classList.add('animate__animated', 'animate__pulse');
        });
        input.addEventListener('blur', () => {
            input.classList.remove('animate__animated', 'animate__pulse');
        });
    });
});
</script>
