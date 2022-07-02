<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m220702_041432_create_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('supplier name'),
            'code' => $this->char(3)->defaultValue(null)->unique()->comment('supplier code'),
            't_status' => "ENUM('ok', 'hold')",
        ]);

        $factory = \Faker\Factory::create();
        for ($i = 0; $i < 1000; $i++) {
            $this->insert('supplier' , [
               'name'=> $factory->name,
                'code'=> $factory->unique()->regexify('[0-9A-Z]{3}'),
                't_status'=>$factory->randomElement(['ok','hold'])
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier}}');
    }
}
