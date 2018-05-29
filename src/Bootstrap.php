<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav;


use nullref\eav\behaviors\Formatter;
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
    }
}
