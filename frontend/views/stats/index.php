<?php

use yii\helpers\Url;

$this->title = 'Раздел статистики';

// Создать окошки для разных отчетов
// подгружать отчеты в pjax

?>
<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Отчет по задачам</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool">
                        <a href="<?= Url::to(['/stats/report-for-client', 'client_id' => current(\common\models\Clients::getMyClientIds())]) ?>">
                            <i class="fa fa-external-link"></i>
                        </a>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="loadAjax" data-url="<?= Url::to(['stats/report-for-client', 'client_id' => current(\common\models\Clients::getMyClientIds())]) ?>"></div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Оплаты</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool">
                        <a href="<?= Url::to(['/stats/report-on-year']) ?>">
                            <i class="fa fa-external-link"></i>
                        </a>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="loadAjax" data-url="<?= Url::to(['stats/report-on-year']) ?>"></div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<?php

$this->registerJs(<<<JS
$(document).ready(function() {
  var loadBlocks = $('.loadAjax');
  
  loadBlocks.each(function(k ,v) {
    var url = $(v).attr('data-url');
    if (url) {
        $.ajax({
          url: url,
          success: function(data) {
            $(v).append(data);
          },
        });
    } else {
        $(v).append('not url');
    }
    
  });
});
JS
);
