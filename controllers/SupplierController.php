<?php

namespace app\controllers;

use app\models\Supplier;
use yii\db\Query;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;

class SupplierController extends Controller
{

    /**
     * @var Supplier
     */
    private $supplier;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->supplier = new Supplier();
    }

    /**
     * Displays supplier list.
     *
     * @return string
     */
    public function actionIndex()
    {
        $provider = $this->supplier->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $this->supplier,
            'dataProvider' => $provider,
        ]);
    }

    /**
     * Export selected supplier models
     *
     * @return void
     * @throws RangeNotSatisfiableHttpException
     */
    public function actionExport()
    {
        $handle = fopen('php://temp', 'wr+');
        fputcsv($handle, [
            'id', 'name', 'code', 't_status'
        ]);

        $ids = $this->request->post('ids');

        if (!empty($ids)) {
            $query = Supplier::find()->where(['in', 'id', explode(',',  $ids)]);
        } else {
            $dataProvider = $this->supplier->search($this->request->queryParams);
            $query = $dataProvider->query;
        }

        /**
         * @var Query $query
         */
        foreach ($query->each() as $supplier) {
            /**
             * @var Supplier $supplier
             */
            fputcsv($handle, [
                $supplier->id,
                $supplier->name,
                $supplier->code,
                $supplier->t_status,
            ]);
        }

        $this->response->sendStreamAsFile($handle, date('YmdHis') . '.csv', ['mimeType' => 'text/csv']);
    }

}
