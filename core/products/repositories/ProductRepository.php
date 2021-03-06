<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:11
 */

namespace app\core\products\repositories;

use app\core\base\BaseRepository;
use app\core\cart\forms\OrderProductForm;
use app\core\categories\CategoryRepository;
use app\core\NotFoundException;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\Sort;
use app\core\products\forms\ProductForm;
use app\core\workWithFiles\DataPathImage;
use app\core\workWithFiles\helpers\GetWebDir;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $categories_id
 * @property string $metaDescription
 * @property string $metaTitle
 * @property string $description
 * @property int $count
 * @property double $price
 * @property double $old_price
 * @property string $code
 * @property int $active
 * @property int $sort
 * @property int $new_prod
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CategoryRepository $category
 * @property ProductImagesRepository[] $images
 */
class ProductRepository extends BaseRepository
{
    use Sort;

    /** @var OrderProductForm */
    public $form;
    /** @var DataPathImage[]|DataPathImage */
    public $imagesGallery;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class],
        ];
    }

    /**
     * @param ProductForm $form
     * @param bool $sort
     */
    public function insertValues($form, bool $sort = false)
    {
        if ($sort) {
            if ($count = $this->countProducts($form->categories_id)) {
                if ($form->sort <= $count) {
                    static::updateAllCounters(
                        ['sort' => 1],
                        ['and', ['=', 'categories_id', $form->categories_id], ['>=', 'sort', $form->sort]]
                    );
                }
            }
        }

        InsertValuesHelper::insertValues($this, $form, [
            'name',
            'alias',
            'categories_id',
            'metaDescription',
            'metaTitle',
            'description',
            'count',
            'price',
            'old_price',
            'code',
            'active',
            'new_prod',
            'sort',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CategoryRepository::class, ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProductImagesRepository::class, ['products_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @param $category_id
     * @return CategoryRepository
     */
    public static function getCategoryForId($category_id)
    {
        return CategoryRepository::findOne($category_id);
    }

    /**
     * @param string $categories_id
     * @return int|string
     */
    public function countProducts(string $categories_id)
    {
        return static::find()->where(['categories_id' => $categories_id])->count();
    }

    public function getWebDir()
    {
        return GetWebDir::getWebDir([$this->category->type_category, $this->category->id, $this->id]);
    }


    /**
     * @param string $code
     * @return null|static
     */
    public function getItemByCode(string $code)
    {
        if (!$object = static::findOne(['code' => $code])) {
            throw new NotFoundException('Продукт по артикулу не найден!');
        }
        return $object;
    }
}