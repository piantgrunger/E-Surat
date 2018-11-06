<?php

namespace app\controllers;

use Yii;
use app\models\SuratPerintahTugas;
use app\models\SuratPerintahTugasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\DetAlatKelengkapan;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * SuratPerintahTugasController implements the CRUD actions for SuratPerintahTugas model.
 */
class SuratPerintahTugasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SuratPerintahTugas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SuratPerintahTugasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SuratPerintahTugas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SuratPerintahTugas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionPrint1($id)
    {
        $content = $this->renderPartial('printspt', [
            'model' => $this->findModel($id),
        ]);
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
   // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
   // A4 paper format
            'format' => Pdf::FORMAT_A4,
   // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
   // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
   // your html content input
            'content' => $content,
   // format content from your own css file if needed or use the
   // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
   // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
    // set mPDF properties on the fly
            'options' => ['title' => 'Cetak Kelompok '],
    // call mPDF methods on the fly
        ]);
        return $pdf->render();
    }
    public function actionCreate()
    {
        $model = new SuratPerintahTugas();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->detailSuratPerintahTugas = Yii::$app->request->post('DetSuratPerintahTugas', []);
                //die(var_dump($model->detailAlatKelengkapan));
                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                }
            } catch (\yii\db\IntegrityException $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Data Tidak Dapat Direvisi Karena Dipakai Modul Lain');
            } catch (\Exception $ecx) {
                $transaction->rollBack();
                throw $ecx;
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SuratPerintahTugas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->detailSuratPerintahTugas = Yii::$app->request->post('DetSuratPerintahTugas', []);
                //die(var_dump($model->detailAlatKelengkapan));
                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                }
            } catch (\yii\db\IntegrityException $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Data Tidak Dapat Direvisi Karena Dipakai Modul Lain');
            } catch (\Exception $ecx) {
                $transaction->rollBack();
                throw $ecx;
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SuratPerintahTugas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (\yii\db\IntegrityException  $e) {
            Yii::$app->session->setFlash('error', "Data Tidak Dapat Dihapus Karena Dipakai Modul Lain");
        }
        return $this->redirect(['index']);
    }

    public function actionAlatKelengkapan($id)
    {
        return  Json::encode(DetAlatKelengkapan::find()
        ->select('tb_d_alat_kelengkapan.*,tb_m_personil.nama_personil,nama_pangkat,status_personil ')
        ->leftJoin('tb_m_personil', "tb_m_personil.id_personil = tb_d_alat_kelengkapan.id_personil")
            ->leftJoin('tb_m_pangkat', "tb_m_personil.id_pangkat = tb_m_pangkat.id_pangkat")

        ->where("id_alat_kelengkapan=$id")

          ->orderBy([new \yii\db\Expression('FIELD (jenis, "Ketua DPRD", "Wakil Ketua DPRD", "Ketua","Wakil Ketua","Sekretaris","Anggota","Staff")')])
        ->asArray()->all());
    }

    /**
     * Finds the SuratPerintahTugas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SuratPerintahTugas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SuratPerintahTugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}