<?php
/**
 * looker plugin for Craft CMS 3.x
 *
 * looker url plugin
 *
 * @link      https://www.braze.com
 * @copyright Copyright (c) 2019 Zeyuan Zhao
 */

namespace braze\looker\models;

use braze\looker\Looker;

use Craft;
use craft\base\Model;

/**
 * @author    Zeyuan Zhao
 * @package   Looker
 * @since     1
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string|null
     */
    public $secret = null;

    /**
     * @var string|null
     */
    public $lookersecret = null;
    
    /**
     * @var string|null
     */
    public $embedpath = null;

    /**
     * @var string|null
     */
    public $host = null;

    /**
     * @var string|null
     */
    public $dbuserid = null;
    /**
     * @var string|null
     */
    public $firstname = null;
    /**
     * @var string|null
     */
    public $lastname = null;
    /**
     * @var string|null
     */
    public $groupname = null;


    // Public Methods
    // =========================================================================
    public function path($dashboardid, $dashboardparams = ""){
      if (!empty($dashboardparams)) {
        $dashboardparams = $dashboardparams . '&';
      }
      return  "/login/embed/" . urlencode($this->embedpath . $dashboardid .  "?" . $dashboardparams . "embed_domain=" . Craft::$app->request->getHostInfo());
    }

}
