<?php

namespace nullref\eav\models;

/**
 * This is the ActiveQuery class for [[Attribute]].
 *
 * @see Attribute
 */
class AttributeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
}
