<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\SuratPerintahTugas */

$this->title = Yii::t('app', 'Buat {modelClass}: ', [
    'modelClass' => 'Realisasi SPPD',
]).$model->no_spt;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Daftar Surat Perintah Perjalanan Dinas'), 'url' => ['index-sppd']];
$this->params['breadcrumbs'][] = Yii::t('app', 'SPPD');
$formatter = \Yii::$app->formatter;

?>

<?php
        Modal::begin([
                'header' => '<h4>Data Realisasi Biaya</h4>',
                'id' => 'modal',
                'size' => 'modal-lg',
            ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

<div class="surat-perintah-tugas-update">


    <h3><?= Html::encode($this->title); ?></h3>
    <h4>Tanggal Kunjungan : <?= $formatter->asDate($model->tgl_awal); ?>&nbsp; -&nbsp; <?= $formatter->asDate($model->tgl_akhir); ?></h4>
    <h4>Dasar : <?= $model->dasar; ?></h4>
    <h4>Untuk : <?= $model->untuk; ?></h4>
    <h4>Tujuan : <?= $model->tujuan; ?></h4>
    <h4>Kendaraan : <?= $model->kendaraan; ?></h4>
    <h4>Kegiatan : <?= $model->nama_kegiatan; ?></h4>
    <h4>Kota : <?= $model->nama_kota; ?></h4>
    <div class="panel panel-info"   >
<div class="panel-heading"> <strong> Data Personil</strong>

</div>
    <table id="table-detail" class="table table-condensed table-bordered table-hover table-stripped">
    <thead>
        <tr class="active">
              <th>#</th>
           <th>Nama</th>
           <th>Pangkat</th>
           <th>Status</th>
           <th>Jabatan</th>
          


        </tr>
        <?= \mdm\widgets\TabularInput::widget([
        'id' => 'detail-grid2',
        'allModels' => $model->detailSuratPerintahTugas,
        'model' => \app\models\DetSuratPerintahTugas::className(),
        'tag' => 'tbody',
        'form' => null,
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_realisasi',
        'clientOptions' => [
            'btnAddSelector' => '#btn-add2',
        ],
    ]);
    ?>
    </thead>
    

    </table>
</div>




</div>
