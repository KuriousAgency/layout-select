<?php
/**
 * Layout Select plugin for Craft CMS 3.x
 *
 * select a layout
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2020 Kurious Agency
 */

namespace kuriousagency\layoutselect\fields;

use kuriousagency\layoutselect\LayoutSelect;
use kuriousagency\layoutselect\assetbundles\layoutselectfield\LayoutSelectFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Kurious Agency
 * @package   LayoutSelect
 * @since     0.0.1
 */
class LayoutSelectField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $options;

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('layout-select', 'Layout Select');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        // $rules = array_merge($rules, [
        //     ['options', 'text'],
        // ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
		if (!$value) {
			return $value;
		}
//Craft::dd($value);
		foreach ($this->options as $option) {
			$id = str_replace('{asset:','',str_replace('}','',$option[0]));
			if ($id == $value) {
				return [
					"id" => $id,
					"data" => Json::decode($option[1]),
				];
			}
		}
		
		return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
		//Craft::dd($value);
		return parent::serializeValue($value['id'], $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'layout-select/_components/fields/LayoutSelectField_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(LayoutSelectFieldAsset::class);
// if ($value != null){Craft::dd($value['id']);}
        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
		Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').LayoutSelectField(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'layout-select/_components/fields/LayoutSelectField_input',
            [
                'name' => $this->handle,
				'value' => $value['id'] ?? $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
