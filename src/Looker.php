<?php
/**
 * looker plugin for Craft CMS 3.x
 *
 * looker url plugin
 *
 * @link      https://www.braze.com
 * @copyright Copyright (c) 2019 Zeyuan Zhao
 */

namespace braze\looker;

use braze\looker\variables\LookerVariable;
use braze\looker\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class Looker
 *
 * @author    Zeyuan Zhao
 * @package   Looker
 * @since     1
 *
 */
class Looker extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Looker
     */
    public static $plugin;

    public $hasCpSettings = true;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1';
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('looker', LookerVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'looker',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'looker/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
