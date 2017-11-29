<?php
/**
 * @package Module List Users for Joomla! 3.x
 * @version $Id: mod_listusers.php 2015-04-09 12:18:00Z 
 * @author Hector Vega
 * @copyright  Copyright (C) 2015 Hector Vega, Systemas HV, All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
**/

defined( '_JEXEC' ) or die;

/* Add database instance  */
$db = JFactory::getDBO();

// Check if component UDDEIM is installed in order to send msg's
// If not installed then does not show icon to send messages.
$db->setQuery("SELECT element FROM  #__extensions WHERE element = 'com_uddeim' and enabled=1");
$db->execute();
$is_enabled_uddeim = $db->loadResult();
$showemaillink  = $params->get('display_email_icon',"Yes");
if($is_enabled_uddeim != 'com_uddeim' ){$showemaillink = 'No'; };

// Check if CB is installed...
$using_cbgallery = "No";
$db0 = JFactory::getDBO();
$db0->setQuery("SELECT element FROM  #__extensions WHERE element = 'com_comprofiler' and enabled=1");
$db0->execute();
$is_enabled_cb = $db0->loadResult();

if($is_enabled_cb != 'com_comprofiler' ){
  echo 'Sorry, Community Builder is not installed.<br />Please go to Joomlapolis.com and download CB Component and install it...';
  return;
}
else
{
// Check version of CB gallery installed... (Default old...)
   $db5 = JFactory::getDBO();
   $db5->setQuery("SELECT element FROM   #__comprofiler_plugin where element = 'cbgallery'  and published = 1");
   $db5->execute();
   $using_cbgallery = $db5->loadResult();
   // cb.profilegallery --> Old Version
   // cbgallery         --> New Version
};

/*  Get all the Parameter's Settings...   */
$limit_show = $params->get('limit_show_users',100);
$omit_users = $params->get('omit_users',62);
$boxwidth = $params->get('boxwidth',"225px");
$background_color = $params->get('boxbackgroundcolor');
$roundedcornersboxmodule = $params->get('roundedcornersboxmodule');
$boxshadowmodule = $params->get('boxshadowmodule');
$poweredbysistemashv = $params->get('poweredbysistemashv','Yes');

$limit_filter_rows = $params->get('limit_filter_rows',5);
$showgallerieslink  = $params->get('display_galleries',"Yes");
$onlywithpic = $params->get('onlywithpic',"Yes");
$AndOnlyWithPic = "";
if($onlywithpic == "Yes"){
  $AndOnlyWithPic = " AND c.avatar IS NOT NULL AND c.avatarapproved = 1";
};

$modulebackgroundcolor= $params->get('modulebackgroundcolor');
$titleline = $params->get('titleline');
$titleline_shadow = $params->get('titleline_shadow','Yes');
$subtitle = $params->get('subtitle');
$title_text_align = $params->get('title_text_align');
$titlefontsize = $params->get('titlefontsize');
$titlefontcolor = $params->get('titlefontcolor');
$subtitlefontcolor = $params->get('subtitlefontcolor');
$subtitlefontsize = $params->get('subtitlefontsize');
$field1fontsize = $params->get('field1fontsize','8px');
$field1fontcolor = $params->get('field1fontcolor','8px');
$field2fontsize = $params->get('field2fontsize','8px');
$field21fontcolor = $params->get('field2fontcolor','8px');
$field3fontsize = $params->get('field3fontsize','8px');
$field3fontcolor = $params->get('field3fontcolor','8px');

/*  Get Field's names to put on queries... */
$cb_field1 = $params->get('cb_field1',"cb_");
$cb_field2 = $params->get('cb_field2',"cb_");
$cb_field3 = $params->get('cb_field3',"cb_");
$cb_filter = $params->get('cb_filterbyfield',"cb_");

// Verify existense of the fields on CB and construct where clause for fields from cb...
$allfields = "";
if(CheckFieldExistOnCB($cb_field1)==0){ $allfields .= ", " .$cb_field1." as Field1"; };
if(CheckFieldExistOnCB($cb_field2)==0){ $allfields .= ", " .$cb_field2." as Field2"; };
if(CheckFieldExistOnCB($cb_field3)==0){ $allfields .= ", " .$cb_field3." as Field3"; };
if(CheckFieldExistOnCB($cb_filter)==0){ $allfields .= ", " .$cb_filter." as Filter"; };

// Set the queries...
switch ($params->get('display_tag')) {
case "NewGalleries":
    if($using_cbgallery ==  'cbgallery'){
        $query = "SELECT  distinct u.username, u.id, c.avatar  ".$allfields." 
                  FROM    (
                            Select distinct aa.user_id,aa.date
                            from   #__comprofiler_plugin_gallery_items as aa
                            where aa.published = 1 and aa.type = 'photos'
                            order by aa.date desc
                            LIMIT  ".$limit_show."
                          ) q
                  JOIN   #__comprofiler c ON (q.user_id = c.user_id)
                  JOIN   #__users as u ON (q.User_id = u.id)
                  WHERE u.id NOT IN (".$omit_users.") AND u.block =0 AND c.banned = 0 AND c.confirmed = 1 AND c.approved =1 ".$AndOnlyWithPic."
                  ORDER BY q.date desc
                  LIMIT ".$limit_show;
    }
    else {
     if($using_cbgallery == 'cb.profilegallery'){   
            $query = "SELECT  distinct c.cb_pgtotalitems,u.username, u.id, c.avatar ".$allfields." 
                     FROM    (
                               Select distinct aa.pgitemdate,aa.userid
                               from   #__comprofiler_plug_profilegallery as aa
                               where a.published = 1 
                               order by aa.pgitemdate desc
                               LIMIT  ".$limit_show."
                             ) q
                     JOIN   #__comprofiler c ON (q.userid = c.user_id)
                     JOIN   #__users as u ON (q.Userid = u.id)
                     WHERE u.id NOT IN (".$omit_users.") AND u.block =0 AND c.banned = 0 AND c.confirmed = 1 AND c.approved =1 ".$AndOnlyWithPic."
                     ORDER BY pgitemdate desc
                     LIMIT ".$limit_show;
     }        
    };    
    break;
case "OnLine":
    $query = "
    SELECT distinct c.lastupdateDate AS sortfield , u.id, u.username,
    s.userid, c.avatar ".$allfields." 
    FROM    #__users as u 
    INNER JOIN   #__comprofiler c ON (u.id = c.user_id AND u.block = 0 AND c.banned = 0 AND c.confirmed =1 AND c.approved =1 )
    INNER JOIN   #__session s ON (s.userid = u.id AND s.guest = 0) 
    WHERE  u.id NOT IN (".$omit_users.") ".$AndOnlyWithPic."
    ORDER BY s.time DESC 
    LIMIT 0, ".$limit_show;
    break;
case "NewUsers" :
    $query = "
    SELECT u.registerdate AS sortfield , u.id, u.username,
    c.user_id, c.avatar ".$allfields." 
    FROM   #__users u 
    INNER JOIN   #__comprofiler c ON (u.id = c.user_id AND u.block = 0 AND c.banned = 0 AND c.confirmed =1 AND c.approved =1 ) 
    WHERE u.id NOT IN (".$omit_users.") ".$AndOnlyWithPic."
    ORDER BY u.registerdate DESC 
    LIMIT 0, ".$limit_show;
    break;
case "UpdatedUsers" :
    $query = "
    SELECT c.lastupdateDate AS sortfield , u.id, u.username,
    c.user_id, c.avatar ".$allfields." 
    FROM   #__users u 
    INNER JOIN   #__comprofiler c ON (u.id = c.user_id AND u.block = 0 AND c.banned = 0 AND c.confirmed =1 AND c.approved =1 ) 
    WHERE u.id NOT IN (".$omit_users.") ".$AndOnlyWithPic."
    ORDER BY lastupdatedate DESC 
    LIMIT 0,".$limit_show;
    break;
case "UsersMoreVisited" :
    $query = "
    SELECT c.lastupdateDate AS sortfield , u.id, u.username,
    c.user_id, c.avatar ".$allfields." 
    FROM   #__users u 
    INNER JOIN   #__comprofiler c ON (u.id = c.user_id AND u.block = 0  AND c.banned = 0 AND c.confirmed =1 AND c.approved =1 ) 
    WHERE u.id NOT IN (".$omit_users.") ".$AndOnlyWithPic."
    ORDER BY hits DESC
    LIMIT 0, ".$limit_show;
    break;
case "NearUsers" :
    break;
}
$db6 = JFactory::getDBO();
$db6->setQuery($query);
$db6->execute();
$rows = $db6->loadObjectList();

      if(empty($rows)){
        echo '<h1> <span class="shadowtitle">No Data available now.. please come back later...</span></h1>'; 
      } 
      else {
       // Get distinct values to be used on the filtering
  
        if(mb_strlen($cb_filter,'UTF-8')>3){ 
               $db7 = JFactory::getDBO();
                $querydistinct = "
                SELECT  distinct  ".$cb_filter." as FilterValue 
                FROM   #__comprofiler c 
                WHERE c.user_id NOT IN (".$omit_users.") 
                LIMIT ".$limit_filter_rows;

                $db7->setQuery($querydistinct);
                $db7->execute();
                $rowsfilter = $db7->loadObjectList();
                };
      };
  
//---------------------------------------------------------------------------------------------------------------------
//  Function to pick Images on the galleries and number of pics
//---------------------------------------------------------------------------------------------------------------------

Function GetImages($UserId,$galleryused)
{		
        echo '<div id="box'.$UserId.'" style="display:none">';

        $db8 = JFactory::getDBO();

        if($galleryused ==  'cbgallery'){
        $query1 = "SELECT Value, Title, Description, folder, id
                   FROM  #__comprofiler_plugin_gallery_items as a
                   WHERE a.user_id = ".$UserId." and a.published = 1 and a.type = 'photos'";
         } else
        {
          $query1 = "SELECT a.pgitemfilename as Value,a.pgitemtitle as Title,a.pgitemdescription as Description, '' as folder
                     FROM   #__comprofiler_plug_profilegallery as a
                     WHERE a.userid = ".$UserId." and a.pgitempublished = 1 and a.pgitemapproved = 1"; 
        };

        $db8->setQuery($query1);
        $db8->execute();

        $rows1 = $db8->loadObjectList();

        foreach ($rows1 as $row1){
                        //$thumb = "tn".$row1->Value;

                        $image = $row1->Value;
                        $title = $row1->Title;
                        if($galleryused ==  'cbgallery'){
                             $link_image =  JURI::base() . "index.php?option=com_comprofiler&view=pluginclass&plugin=cbgallery&action=items&func=show&type=photos&id=".
                             $row1->id."&user=".$UserId;  
                        } else
                        {
                           $link_image =  JURI::base() . "images/comprofiler/plug_profilegallery/".$UserId."/".$image;
                        };
                        echo '<p><a class="groupslider'.$UserId.'"  href="'.$link_image.'" title="'.$title.'">'.$image.'</a></p>';
                        
                      }
 
    		echo '</div>';
};

  Function CountImages($UserId,$galleryused){

     $db9 = JFactory::getDBO();
     if($galleryused ==  'cbgallery'){

     $sql = "SELECT Count(*) 
             FROM  #__comprofiler_plugin_gallery_items as a
             WHERE a.user_id = ".$UserId." and a.published = 1 and a.type = 'photos'";
    } else
    {
      $sql = "SELECT Count(*) 
              FROM  #__comprofiler_plug_profilegallery as a
              WHERE a.userid = ".$UserId." and a.pgitempublished = 1 and a.pgitemapproved = 1";
    };

     $db9->setQuery($sql);
     $db9->execute();
     $value = $db9->loadResult();

     Return $value;
};    		


//  Function to check if fieldname on paramaters exist on CB

Function CheckFieldExistOnCB($FieldName)
{
    $Value = 1;

    if(mb_strlen($FieldName,'UTF-8')>3){
 
      $db13 = JFactory::getDBO();
      $db13->setQuery("SELECT name FROM  #__comprofiler_fields WHERE name = '".$FieldName."'");
      $db13->execute();
      $exist_field = $db13->loadResult();
      if($exist_field != $FieldName ){
          echo 'Sorry, Community Builder has not the field name '.$FieldName.'<br />Please check parameters on the module configuration...';
          $Value = 1;
        } 
        else {
          $Value = 0;
        };
    };

    Return $Value;  
};