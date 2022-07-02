<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Supplier */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers list';
$this->params['breadcrumbs'][] = $this->title;



$css = <<< CSS
.filters .form-control {
height: auto!important;
}
.pagination li{
padding: 10px;
}
CSS;
$this->registerCss($css);
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::beginForm(Url::to('/supplier/export'), 'post', [
            'id' => 'export-form',
            'target' => '_blank'
        ]) ?>
        <?= Html::hiddenInput('ids') ?>
        <?= Html::button('Export', [
            'id' => 'export-selected',
            'class' => 'btn btn-primary',
            'disabled' => true,
        ]) ?>
        <?= Html::endForm() ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'raw',
                'attribute' => '',
                'header' => "<input type='checkbox' id='all-check' />",
                'value' => function ($data) {
                    return "<input type='checkbox' class='i-check' value={$data['id']}>";
                },
            ],
            'id',
            'name',
            'code',
            [
                'label'     => 'T Status',
                'attribute' => 't_status',
                'filter' => [
                    'ok' => 'OK',
                    'hold' => 'Hold',
                ],
                'filterOptions' => [
                    'prompt' => 'ALL',
                    'class' => 'form-control',
                    'id' => null,
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php
$script = <<< JS
let allCheck = $('#all-check')
, exportBtn = $('#export-selected')
, exportForm = $('#export-form')
$(document).delegate('#all-check' , 'click' , function() {
    let status = $(this).prop('checked');
    $('.i-check').prop('checked' , status)
    checkICheckLen()
})

$(document).delegate('.i-check' , 'click' , function() {
    checkICheckLen()
})

$(document).delegate('#export-selected' , 'click' , function () {
    let ids = []
    $('.i-check:checked').each((i , v)=>{
        ids.push(v.value)
    })
    
    $('[name=ids]').val(ids.join(','))
    
    exportForm.submit()
})


function checkICheckLen () {
    let checkedLen = $('.i-check:checked').length
    , iCheckLen = $('.i-check').length
    
    if (checkedLen === iCheckLen) {
        allCheck.prop('checked' , true)
    }else {
        allCheck.prop('checked' , false)
    }
    
    if (checkedLen > 0) {
        exportBtn.prop('disabled' , false)
    }else {
        exportBtn.prop('disabled' , true)
    }
}
JS;
$this->registerJs($script);
?>