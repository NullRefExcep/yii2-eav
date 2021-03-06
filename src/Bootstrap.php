<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav;


use nullref\eav\behaviors\Formatter;
use nullref\eav\components\TypesManager;
use nullref\eav\features\Editable;
use nullref\eav\features\RangeFilter;
use nullref\eav\features\Select2;
use nullref\eav\models\value\DecimalValue;
use nullref\eav\models\value\IntegerValue;
use nullref\eav\models\value\JsonValue;
use nullref\eav\models\value\OptionValue;
use nullref\eav\models\value\StringValue;
use nullref\eav\models\value\TextValue;
use nullref\eav\types\Decimal;
use nullref\eav\types\Type;
use nullref\eav\types\Types;
use nullref\eav\types\TypeWithOptions;
use nullref\eav\widgets\inputs\DefaultInput;
use nullref\eav\widgets\inputs\MultilineInput;
use nullref\eav\widgets\inputs\OptionInput;
use nullref\eav\widgets\inputs\TextInput;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        if ((($module = $app->getModule(Module::MODULE_ID)) == null) || !($module instanceof Module)) {
            return;
        };

        /** I18n */
        if (!isset($app->get('i18n')->translations['eav*'])) {
            $app->i18n->translations['eav*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => '@nullref/eav/messages',
            ];
        }

        $app->getFormatter()->attachBehavior(Module::MODULE_ID, [
            'class' => Formatter::class,
        ]);

        $this->setupManager(TypesManager::get());
        $this->registerDefaultAttributesConfigProperties($module);
        $this->setupFeatures($module);
    }

    /**
     * @param Module $module
     */
    protected function setupFeatures(Module $module)
    {
        Editable::setup($module);
        Select2::setup($module);
        RangeFilter::setup($module);
    }

    /**
     * @param $module
     */
    protected function registerDefaultAttributesConfigProperties(Module $module)
    {
        $configProperties = [
            'show_in_grid' => function ($activeField) {
                return $activeField
                    ->checkbox([], false)
                    ->label(Yii::t('eav', 'Show in grid'));
            },
            'read_only' => function ($activeField) {
                return $activeField
                    ->checkbox([], false)
                    ->label(Yii::t('eav', 'Read only'));
            },
            'searchable' => function ($activeField) {
                return $activeField
                    ->checkbox([], false)
                    ->label(Yii::t('eav', 'Searchable'));
            },
            'multiple' => function ($activeField) {
                return $activeField
                    ->checkbox([], false)
                    ->label(Yii::t('eav', 'Multiple'));
            },
        ];

        foreach ($configProperties as $prop => $builder) {
            $module->registerAttributesConfigProperty($prop, $builder);
        }
    }

    /**
     * @param $manager
     */
    protected function setupManager(TypesManager $manager)
    {
        // Integer
        $manager->registerType(new Type(Types::TYPE_INT, Yii::t('eav', 'Integer'),
            IntegerValue::class, DefaultInput::class));
        // Decimal
        $manager->registerType(new Decimal(Types::TYPE_DECIMAL, Yii::t('eav', 'Decimal'),
            DecimalValue::class, DefaultInput::class));
        // String
        $manager->registerType(new Type(Types::TYPE_STRING, Yii::t('eav', 'String'),
            StringValue::class, DefaultInput::class));
        // Text
        $manager->registerType(new Type(Types::TYPE_TEXT, Yii::t('eav', 'Text'),
            TextValue::class, TextInput::class));
        // Json
        $manager->registerType(new Type(Types::TYPE_JSON, Yii::t('eav', 'JSON'),
            JsonValue::class, MultilineInput::class));
        // Option
        $manager->registerType(new TypeWithOptions(Types::TYPE_OPTION, Yii::t('eav', 'Option'),
            OptionValue::class, OptionInput::class));

        // Price
        $manager->registerType(new Type(Types::TYPE_PRICE, Yii::t('eav', 'Price'),
            DecimalValue::class, DefaultInput::class));
    }
}
