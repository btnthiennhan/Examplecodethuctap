<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Danh sách';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <?= $this->render('index_tab') ?>

    <div class="container mb-3" style="position: relative;">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'row g-2', 'id' => 'search-form', 'autocomplete' => 'off']
        ]); ?>

        <div class="col-md-10" style="position: relative;">
            <?= $form->field($searchModel, 'name', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tìm theo tên...',
                    'id' => 'search-name',
                    'autocomplete' => 'off'
                ]
            ])->label(false) ?>

            <!-- Dropdown suggest -->
            <div id="suggest-list" style="
                position: absolute; 
                top: 100%; 
                left: 0; 
                right: 0; 
                background: white; 
                border: 1px solid #ccc; 
                z-index: 1000; 
                display: none; 
                max-height: 200px; 
                overflow-y: auto;
                border-radius: 0 0 0.375rem 0.375rem;
            "></div>
        </div>

        <div class="col-md-2 d-grid">
            <?= Html::submitButton('<i class="bi bi-search"></i> Tìm', ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
                    
    <div id="product-list" class="row g-4">
        <!-- Hiển thị danh sách sản phẩm như cũ -->
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="col-md-4">
                <div class="card shadow-lg border-0 h-100 hover-shadow position-relative overflow-hidden transition-all">
                    <?php if (!empty($model->image)): ?>
                        <div class="img-hover-zoom">
                            <img src="<?= Html::encode($model->image) ?>" class="card-img-top" alt="Hình ảnh">
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold mb-2 text-primary-emphasis transition-text">
                            <?= Html::encode($model->name) ?>
                        </h5>
                        <p class="text-danger fw-semibold fs-6 mb-2">Giá: <?= number_format($model->price, 0, ',', '.') ?>₫</p>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <?= Html::a('<i class="bi bi-cart-plus"></i> Thêm vào giỏ', ['cart/add', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                            <div class="d-flex gap-1">
                                <?= Html::a('<i class="bi bi-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-info']) ?>
                                <?= Html::a('<i class="bi bi-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                <?= Html::a('<i class="bi bi-trash"></i>', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'data' => [
                                        'confirm' => 'Bạn có chắc muốn xoá mục này?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
    var $input = $('#search-name');
    var $suggest = $('#suggest-list');

    $input.on('input', function() {
        var term = $(this).val().trim();
        if (term.length < 2) {
            $suggest.hide();
            return;
        }
        $.ajax({
            url: '<?= Url::to(['suggest']) ?>',
            data: {term: term},
            success: function(data) {
                if (data.length === 0) {
                    $suggest.hide();
                    return;
                }
                var html = '';
                data.forEach(function(item) {
                    html += '<div class="suggest-item" style="padding: 5px 10px; cursor:pointer;">' + 
                                $('<div>').text(item.label).html() + 
                            '</div>';
                });
                $suggest.html(html).show();
            }
        });
    });

    // Click chọn suggest
    $suggest.on('click', '.suggest-item', function() {
        var text = $(this).text();
        $input.val(text);
        $suggest.hide();
        $('#search-form').submit();
    });

    // Ẩn suggest khi click ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search-name, #suggest-list').length) {
            $suggest.hide();
        }
    });

    // Nhấn enter submit form bình thường
    $input.on('keydown', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            $('#search-form').submit();
        }
    });
});
</script>

<!-- Canvas star field -->
<canvas id="starfield"></canvas>

<style>
#starfield {
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none; /* để không chắn chuột */
    z-index: 9999;
    background: transparent;
}
</style>

<script>
// Starfield effect with mouse move to control speed & direction
(function(){
    const canvas = document.getElementById('starfield');
    const ctx = canvas.getContext('2d');
    let width, height;

    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    }
    window.addEventListener('resize', resize);
    resize();

    // Star class
    class Star {
        constructor() {
            this.reset();
        }
        reset() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 1.2 + 0.3;
            this.speedX = 0;
            this.speedY = Math.random() * 0.3 + 0.1;
            this.opacity = Math.random() * 0.5 + 0.5;
        }
        update(speedX, speedY) {
            this.x += speedX * this.size;
            this.y += this.speedY + speedY * this.size;

            if(this.y > height) this.y = 0;
            if(this.x > width) this.x = 0;
            if(this.x < 0) this.x = width;
        }
        draw(ctx) {
            ctx.beginPath();
            ctx.fillStyle = 'rgba(255, 255, 255,' + this.opacity + ')';
            ctx.shadowColor = 'white';
            ctx.shadowBlur = 4;
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    const starsCount = 150;
    const stars = [];
    for(let i=0; i<starsCount; i++){
        stars.push(new Star());
    }

    // Speed influenced by mouse move
    let speedX = 0;
    let speedY = 0;
    let lastMouseX = null;
    let lastMouseY = null;

    document.addEventListener('mousemove', function(e){
        if(lastMouseX !== null && lastMouseY !== null){
            speedX = (e.clientX - lastMouseX) * 0.05; // chỉnh hệ số tốc độ
            speedY = (e.clientY - lastMouseY) * 0.05;
        }
        lastMouseX = e.clientX;
        lastMouseY = e.clientY;
    });

    function animate(){
        ctx.clearRect(0, 0, width, height);
        stars.forEach(star => {
            star.update(speedX, speedY);
            star.draw(ctx);
        });
        // Giảm dần speed để hiệu ứng mượt
        speedX *= 0.92;
        speedY *= 0.92;
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
<style>
/* giữ nguyên style cũ... */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 1rem;
}
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
}
.card-img-top {
    max-height: 200px;
    object-fit: cover;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
    transition: transform 0.5s ease;
}
.img-hover-zoom {
    overflow: hidden;
    border-radius: 1rem 1rem 0 0;
}
.img-hover-zoom img:hover {
    transform: scale(1.08);
}
.card-title {
    transition: color 0.3s ease;
}
.card:hover .card-title {
    color: #0d6efd;
}


</style>