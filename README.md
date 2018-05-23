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
            $valueModel->addJoin($query, Product::tableName());
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
    ], nullref\eav\helpers\Grid::getGridColumns($searchModel), [
    //... 
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ]),
]); ?>
```

To configure which columns will be shown in grid go to attribute update page and select "Show on grid" checkbox.


Translations
------------

And [translations](https://github.com/NullRefExcep/yii2-core#translation-overriding)

