<?php

namespace nullref\eav\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Value]].
 *
 * @see Value
 */
class MultipleValueQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Value[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Value|array|null
     */
    public function one($db = null)
    {
        $items = parent::all($db);

        if (count($items)>0){

            $values = [];
            for($i = 0; $i < count($items); $i++){

            }
            return $items[0];
        }

        return null;
    }
}
