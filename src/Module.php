<?php

namespace nullref\eav;

use nullref\core\interfaces\IAdminModule;
use nullref\core\interfaces\IHasMigrateNamespace;
use nullref\eav\components\TypesManager;
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
    protected $typesManager;

    protected $attributesConfigProperties = [];

    /**
     * @param $prop
     * @param $builder
     */
    public function registerAttributesConfigProperty($prop, $builder)
    {
        $this->attributesConfigProperties[$prop] = $builder;
    }

    /**
     * {property} => {builder}
     * @return array
     */
    public function getAttributesConfigProperties()
    {
        return $this->attributesConfigProperties;
    }

    /**
     * @return array
     */
    public static function getAdminMenu()
    {
        return [
            'order' => 100,
            'label' => Yii::t('eav', 'EAV'),
            'icon' => FA::_LIST_ALT,
            'roles' => ['eav'],
            'items' => [
                [
                    'label' => Yii::t('eav', 'Attribute Sets'),
                    'url' => ['/eav/admin/set'],
                    'icon' => FA::_TAGS,
                    'roles' => ['eav'],
                ],
                [
                    'label' => Yii::t('eav', 'Attributes'),
                    'url' => ['/eav/admin/attribute'],
                    'icon' => FA::_LIST,
                    'roles' => ['eav'],
                ],
                [
                    'label' => Yii::t('eav', 'Options'),
                    'url' => ['/eav/admin/option'],
                    'icon' => FA::_LIST,
                    'roles' => ['eav'],
                ],
            ],
        ];
    }

    /**
     * @return Manager
     */
    public function getTypesManager()
    {
        if ($this->typesManager == null) {
            $this->typesManager = new TypesManager();
        }
        return $this->typesManager;
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
