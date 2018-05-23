Yii2 EAV
===============



WIP

Module for EAV (entity attribute value) anti pattern

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist nullref/yii2-eav "*"
```

or add

```
"nullref/yii2-eav": "*"
```

to the require section of your `composer.json` file.

Then You have run console command for install this module and run migrations:

```
php yii module/install nullref/yii2-eav
```

Pay attention that if you don't use our [application template](https://github.com/NullRefExcep/yii2-boilerplate) 
it needs to change config files structure to have ability run commands that show above.

Please check this [documentation section](https://github.com/NullRefExcep/yii2-core#config-structure)

Setup
-----

Add behavior to target model

```php
public function behaviors()
{
    return [
        /** ... **/
        'eav' => [
            'class' => Entity::class,
            'entity' => function () {
                return new EntityModel([
                    'sets' => [
                        Set::findOne(['code' => 'product']), //product -- set from db
                    ],
                ]);
            },
        ],
    ];
}
```

Create set and attribute for it in admin panel

Add attributes widget to entity edit form

```php
<?= app\modules\eav\widgets\Attributes::widget([
    'form' => $form,
    'model' => $model,
]) ?>
```

And [translations](https://github.com/NullRefExcep/yii2-core#translation-overriding)

