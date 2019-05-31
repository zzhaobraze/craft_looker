<?php
/**
 * looker plugin for Craft CMS 3.x
 *
 * looker url plugin
 *
 * @link      https://www.braze.com
 * @copyright Copyright (c) 2019 Zeyuan Zhao
 */

namespace braze\looker\variables;

use braze\looker\Looker;
use stdClass;
use Craft;

/**
 * @author    Zeyuan Zhao
 * @package   Looker
 * @since     1
 */
class LookerVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param string $dashboardid
     * @param array|string $modelname
     * @return string
     */
     // https://github.com/looker/looker_embed_sso_examples
    public function getDashboardSSO($dashboardid="", $modelname = [], $dashboardparams = "")
    {
        $settings = Looker::$plugin->getSettings();

        $secret = $settings->secret ;
        $host = $settings->host ;
        $path = $settings->path($dashboardid, $dashboardparams);

        $json_nonce = json_encode(md5(uniqid()));
        $json_current_time = json_encode(time());
        $json_session_length = json_encode(86400);
        $json_external_user_id = json_encode($settings->dbuserid);
        $json_first_name = json_encode($settings->firstname);
        $json_last_name = json_encode($settings->lastname);
        $json_permissions = json_encode( array ("see_user_dashboards","access_data" ) );
        $json_models = json_encode( $modelname  );
        $json_group_ids = json_encode(array());  // just some example group ids
        $json_external_group_id = json_encode($settings->groupname);
        $json_user_attributes = json_encode(new stdClass());  // just some example attributes
        // NOTE: accessfilters must be present and be a json hash. If you don't need access filters then the php
        // way to make an empty json hash as an alternative to the below seems to be:
        // $accessfilters = array (
        //   $modelname  =>  array ( "view_name.dimension_name" => $value )
        // );
        $json_accessfilters = json_encode(new stdClass());

        $stringtosign = "";
        $stringtosign .= $host . "\n";
        $stringtosign .= $path . "\n";
        $stringtosign .= $json_nonce . "\n";
        $stringtosign .= $json_current_time . "\n";
        $stringtosign .= $json_session_length . "\n";
        $stringtosign .= $json_external_user_id . "\n";
        $stringtosign .= $json_permissions . "\n";
        $stringtosign .= $json_models . "\n";
        $stringtosign .= $json_group_ids . "\n";
        $stringtosign .= $json_external_group_id . "\n";
        $stringtosign .= $json_user_attributes . "\n";
        $stringtosign .= $json_accessfilters;

        $signature = trim(base64_encode(hash_hmac("sha1", utf8_encode($stringtosign), $secret, $raw_output = true)));
        // , $raw_output = true

        $queryparams = array (
            'nonce' =>  $json_nonce,
            'time'  =>  $json_current_time,
            'session_length'  =>  $json_session_length,
            'external_user_id'  =>  $json_external_user_id,
            'permissions' =>  $json_permissions,
            'models'  =>  $json_models,
            'group_ids' => $json_group_ids,
            'external_group_id' => $json_external_group_id,
            'user_attributes' => $json_user_attributes,
            'access_filters'  =>  $json_accessfilters,
            'first_name'  =>  $json_first_name,
            'last_name' =>  $json_last_name,
            'force_logout_login'  =>  false,
            'signature' =>  $signature
        );

        $querystring = "";
        foreach ($queryparams as $key => $value) {
          if (strlen($querystring) > 0) {
            $querystring .= "&";
          }
          if ($key == "force_logout_login") {
            $value = "true";
          }
          $querystring .= "$key=" . urlencode($value);
        }
        $result = "https://" . $host . $path . "?" . $querystring;
        return $result;
    }
    public function getDashboardURL($dashboardid="", $modelname = [])
    {
      $settings = Looker::$plugin->getSettings();
      $host = $settings->host ;
      $embedpath = $settings->embedpath;
      $result = "https://" . $host . $embedpath . $dashboardid;
      return $result;
    }
    /**
     * @return string
     */
    public function getPartnerUrl()
    {
      return 'https://' . Looker::$plugin->getSettings()->host ;
    }
}
