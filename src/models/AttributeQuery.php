<?php

namespace nullref\eav\models;

use nullref\eav\models\attribute\Option;
use nullref\useful\traits\MappableQuery;
use yii\db\ActiveQuery as BaseActiveQuery;

/**
 * This is the ActiveQuery class for [[Attribute]].
 *
 * @see Attribute
 */
class AttributeQuery extends BaseActiveQuery
{
    use MappableQuery;

    /**
     * @inheritdoc
     * @return Attribute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Attribute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function withOptions()
    {
        return $this->rightJoin(['options' => Option::tableName()], 'options.attribute_id = attribute.id');
    }
}
