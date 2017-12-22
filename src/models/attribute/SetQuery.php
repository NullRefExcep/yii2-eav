<?php

namespace nullref\eav\models\attribute;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Set]].
 *
 * @see Set
 */
class SetQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Set[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Set|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
