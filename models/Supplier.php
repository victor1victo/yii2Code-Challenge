<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string $t_status
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^(>|<|>=|<=)*\d+$/'],
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'T Status',
        ];
    }

    //查询

    public function search($params)
    {

        //首先我们先获取一个ActiveQuery

        $query = self::find();
        //然后创建一个ActiveDataProvider对象

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>50
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'code',
                't_status',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->id)) {
            preg_match('/^(>|<|>=|<=)*(\d+)$/', $this->id, $matches);
            list(, $operate, $value) = $matches;
            if (empty($operate)) {
                $operate = '=';
            }
            $query->andWhere([
                $operate, 'id', $value
            ]);
        }

        $query->andFilterWhere([
            't_status' => $this->t_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;

    }

}
