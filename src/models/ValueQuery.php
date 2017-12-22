<?php

namespace nullref\eav\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Value]].
 *
 * @see Value
 */
class ValueQuery extends ActiveQuery
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
        return parent::one($db);
    }
}
