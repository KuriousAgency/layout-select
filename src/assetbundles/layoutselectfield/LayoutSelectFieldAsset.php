<?php
/**
 * Layout Select plugin for Craft CMS 3.x
 *
 * select a layout
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2020 Kurious Agency
 */

namespace kuriousagency\layoutselect\assetbundles\layoutselectfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Kurious Agency
 * @package   LayoutSelect
 * @since     0.0.1
 */
class LayoutSelectFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@kuriousagency/layoutselect/assetbundles/layoutselectfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
			'js/LayoutSelectField.js',
			'js/LayoutSelectInput.js',
			'js/LayoutSelectModal.js',
        ];

        $this->css = [
            'css/LayoutSelectField.css',
        ];

        parent::init();
    }
}
