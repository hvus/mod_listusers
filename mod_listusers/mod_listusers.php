<?php
/**
 * @package Module List Users for Joomla! 3.x
 * @version $Id: mod_listusers.php 2015-04-09 12:18:00Z 
 * @author Hector Vega
 * @copyright  Copyright (C) 2015 Hector Vega, Systemas HV, All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
**/

//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');


$document = & JFactory::GetDocument();

//$document->addScript(JURI::base() . 'modules/mod_listusers/js/overlib_mini.js');

//This is the parameter we get from our xml file above
$userCount = $params->get('usercount');

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';


//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_listusers', $params->get('layout', 'default'));


ns1.susyswingers.com	carl.ns.cloudflare.com
        
ns2.susyswingers.com	daisy.ns.cloudflare.com
        
        