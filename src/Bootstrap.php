<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav;


use nullref\eav\behaviors\Formatter;
use nullref\eav\components\Manager;
use nullref\eav\models\Type;
use nullref\eav\models\Types;
use nullref\eav\models\TypeWithOptions;
use nullref\eav\models\value\DecimalValue;
use nullref\eav\models\value\IntegerValue;
use nullref\eav\models\value\JsonValue;
use nullref\eav\models\value\OptionValue;
use nullref\eav\models\value\StringValue;
use nullref\eav\models\value\TextValue;
use nullref\eav\widgets\inputs\DefaultInput;
use nullref\eav\widgets\inputs\MultilineInput;
use nullref\eav\widgets\inputs\OptionInput;
use nullref\eav\widgets\inputs\TextInput;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;
use Yii;

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

        $this->setupManager();
    }

    /**
     *
     */
    protected function setupManager()
    {
        $manager = Manager::get();
        // Integer
        $manager->registerType(new Type(Types::TYPE_INT, Yii::t('eav', 'Integer'),
            IntegerValue::class, DefaultInput::class));
        // Decimal
        $manager->registerType(new Type(Types::TYPE_DECIMAL, Yii::t('eav', 'Decimal'),
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
    }
}
