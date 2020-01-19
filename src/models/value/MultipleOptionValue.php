<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models\value;


use nullref\eav\models\Value;
use nullref\eav\models\ValueQuery;
use yii\db\ActiveQuery;

class OptionValue extends Value
{
    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['value', 'each', 'rule' => ['integer']],
        ]);
    }

    /**
     * @inheritdoc
     * @return ValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ValueQuery(get_called_class());
    }

}