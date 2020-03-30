Yii2 EAV
===============


[![Latest Stable Version](https://poser.pugx.org/nullref/yii2-eav/v/stable)](https://packagist.org/packages/nullref/yii2-eav) [![Total Downloads](https://poser.pugx.org/nullref/yii2-eav/downloads)](https://packagist.org/packages/nullref/yii2-eav) [![Latest Unstable Version](https://poser.pugx.org/nullref/yii2-eav/v/unstable)](https://packagist.org/packages/nullref/yii2-eav) [![License](https://poser.pugx.org/nullref/yii2-eav/license)](https://packagist.org/packages/nullref/yii2-eav)


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
use nullref\eav\behaviors\Entity;
use nullref\eav\models\attribute\Set;
use nullref\eav\models\Entity as EntityModel;

/**
 * ...
 * @property EntityModel $eav
 * ...
 */
class Product extends \yii\db\ActiveRecord
    //...
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
    //...
}
```

Create set and attribute for it in admin panel

Add attributes widget to entity edit form

```php
<?= \nullref\eav\widgets\Attributes::widget([
    'form' => $form,
    'model' => $model,
]) ?>
```

If you need some dynamic configuration sets of your model you can use method `afterFind()`:

```php
public function afterFind()
{
    $this->attachBehavior('eav', [
        'class' => Entity::class,
        'entity' => function () {
            $setIds = $this->getCategories()->select('set_id')->column();
            $setIds[] = Set::findOne(['code' => 'product'])->id;
            return new EntityModel([
                'sets' => Set::findAll(['id' => array_unique($setIds)]),
            ]);
        },
    ]);
    parent::afterFind();
}
```

In above example we have many-to-many relation product model with category which has set_id column.

Pay attention that this example could caused n+1 query problem. To prevent this problem use query caching or memoization.
For example, change:
```php
\nullref\eav\models\attribute\Set::findOne(['code' => 'product']),
```
to
```php
\nullref\useful\helpers\Memoize::call([Set::class, 'findOne'],[['code' => 'product']]),
```

Using in search model 
---------------------

If you need filtering your records by eav fields you need to modify `YourModelSearch::search()` method by following code:

```php
    //...
    if (!$this->validate()) {
        return $dataProvider;
    }
    //...
    foreach ($this->eav->getAttributes() as $key => $value) {

        $valueModel = $this->eav->getAttributeModel($key)->createValue();

        $valueModel->setScenario('search');
        $valueModel->load(['value' => $value], '');
        if ($valueModel->validate(['value'])) {
            $valueModel->addJoin($query, self::tableName());
            $valueModel->addWhere($query);
        }
    }
    //...
    return $dataProvider;
```

To output columns in gridview use `nullref\eav\helpers\Grid::getGridColumns()`:

```php
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge([
    //... 
        'name',
    ], \nullref\eav\helpers\Grid::getGridColumns($searchModel), [
    //... 
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ]),
]); ?>
```

To configure which columns will be shown in grid go to attribute update page and select "Show on grid" checkbox.


Customization
-------------

To add custom types you need to use type `\nullref\eav\components\Manager`.
To get more details please check `\nullref\eav\Bootstrap::setupManager` as example of configuring base types.

You could call `\nullref\eav\components\Manager::registerType` at bootstrap phase and define you own types of attributes.

Method `registerType` takes one argument by type `\nullref\eav\models\Type` this class contains all info about particular type:

- name (unique string)
- label
- value model class (based on `\nullref\eav\widgets\AttributeInput`)
- form input class (based on `\nullref\eav\models\Value`)

```php
Manager::get()->registerType(new Type(
    Types::TYPE_IMAGE, 
    Yii::t('eav', 'Image'), 
    JsonValue::class, 
    ImageInput::class)
);
```

Filtering attributes
--------------------

If you need filter EAV attributes you could use `filterAttributes` and pass callable there:
```php

'eav' => [
    'class' => Entity::class,
    'entity' => function () {
        return new EntityModel([
            'sets' => [
                Memoize::call([Set::class, 'findOne'], [['code' => 'product']]),
            ],
            'filterAttributes' => function ($attributes) {
                $fieldCheckerService = Yii::$container->get(CheckerService::class);
                $result = [];
                foreach ($attributes as $code => $attr) {
                    if ($fieldCheckerService->isAllowedForClass(self::class, $code)) {
                        $result[$code] = $attr;
                    }
                }
                return $result;
            }
        ]);
    },
],
```

Translations
------------

And [translations](https://github.com/NullRefExcep/yii2-core#translation-overriding)

