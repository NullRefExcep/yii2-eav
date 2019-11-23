<?php

namespace nullref\eav;

use nullref\core\interfaces\IAdminModule;
use nullref\core\interfaces\IHasMigrateNamespace;
use nullref\eav\components\Manager;
use nullref\eav\models\Type;
use nullref\eav\models\value\IntegerValue;
use nullref\eav\models\value\JsonValue;
use nullref\eav\models\value\OptionValue;
use nullref\eav\models\value\StringValue;
use nullref\eav\models\value\TextValue;
use nullref\eav\widgets\inputs\DefaultInput;
use nullref\eav\widgets\inputs\OptionInput;
use nullref\eav\widgets\inputs\TextInput;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\base\Module as BaseModule;

/**
 * eav module definition class
 */
class Module extends BaseModule implements IAdminModule, IHasMigrateNamespace
{
    const MODULE_ID = 'eav';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'nullref\eav\controllers';

    /**
     * @return array
     */
    public static function getAdminMenu()
    {
        return [
            'order' => 100,
            'label' => Yii::t('eav', 'EAV'),
            'icon' => FA::_LIST_ALT,
            'items' => [
                [
                    'label' => Yii::t('eav', 'Attribute Sets'),
                    'url' => ['/eav/admin/set'],
                    'icon' => FA::_TAGS,
                ],
                [
                    'label' => Yii::t('eav', 'Attributes'),
                    'url' => ['/eav/admin/attribute'],
                    'icon' => FA::_LIST,
                ],
                [
                    'label' => Yii::t('eav', 'Options'),
                    'url' => ['/eav/admin/option'],
                    'icon' => FA::_LIST,
                ],
            ],
        ];
    }

    protected $manager;

    /**
     * @return Manager
     */
    public function getManager()
    {
        if ($this->manager == null) {
            $this->manager = new Manager();
        }
        return $this->manager;
    }

    /**
     * Return path to folder with migration with namespaces
     *
     * @param $defaults
     * @return array
     */
    public function getMigrationNamespaces($defaults)
    {
        return ['nullref\eav\migration_ns'];
    }
}
