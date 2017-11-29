<?php
/**
 * @package Module List Users for Joomla! 3.x
 * @version $Id: mod_listusers.php 2015-04-09 12:18:00Z 
 * @author Hector Vega
 * @copyright  Copyright (C) 2015 Hector Vega, Systemas HV, All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
**/

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

//JHtml::_( 'behavior.keepalive' );

// To Do's
//
// Show icono nophoto de CB
// XML de Cambio de Lenguaje
// Cambio de Template Bottom and Rigth
// Use Div's or Table's
// Check isotope pagination

?>

<head>

  <link rel="stylesheet" href="./modules/mod_listusers/css/isotope.css" type="text/css" />
  <link rel="stylesheet" href="./modules/mod_listusers/css/colorbox.css" type="text/css" />

 
  <style type="text/css">
      <?php 
         foreach ($rowsfilter as $filteritem){
               echo '.element-item.'.$filteritem->FilterValue.' { width: '.$boxwidth.'; background: '.$background_color.' }';
         }

//  text-shadow: 0 0 100px #fff, 0 0 10px #fff, 0 0 30px #fff, 0 0 40px #ff00de, 0 0 5px #ff00de, 0 0 40px #ff00de, 0 0 25px #ff00de, 0 0 20px #ff00de;
    if($titleline_shadow == 'Yes'){      
    echo  ".shadowtitle {
            text-shadow :  7px 7px 6px rgba(150, 150, 150, 1);
            color       :  ".$titlefontcolor.";
            font-size   :  ".$titlefontsize.";}";
    } else
    {
    echo  ".shadowtitle {
      color       :  ".$titlefontcolor.";
      font-size   :  ".$titlefontsize.";}";  
    
    };    
      ?>

 </style>

 <style>
    .boxprofile {
        text-align:center;
        padding:5px;
        width:100%;
        height:100%;
    }
    
   .divshadow {
       max-height:   <?php echo $titlefontsize; ?> ;
       height    : 100%; 
       text-align:  <?php echo $title_text_align; ?> ;
       
    }
    .avatardiv {
        height: 150px; 
        vertical-align: middle; 
        
    }
    .linksdiv {
        margin: 0 auto;
        display: flex;
        justify-content: center;
        background-color: rgba(255, 0, 0, 0.2);
        position: relative;
     
    }    
    .linksdiv1 {
        margin: 0 auto;
        display: flex;
        justify-content: center;
        
     
    }     
    
    .zoom_img img{
        margin:50px;
        height:auto;
        width:100px;
        -moz-transition:-moz-transform 0.5s ease-in; 
        -webkit-transition:-webkit-transform 0.5s ease-in; 
        -o-transition:-o-transform 0.5s ease-in;
/*
    -ms-transition: all 1s ease; 
    transition: all 1s ease;
*/
    }
    .zoom_img img:hover{
        -moz-transform:scale(3); 
        -webkit-transform:scale(3);
        -o-transform:scale(3);
        -ms-transform:scale(3); 
        transform:scale(3);
        box-shadow:0px 0px 30px gray;
        z-index: 50;
    }    
</style> 


</head>

<!-- Module background color, ROunded corners and Shadow box main container ....................... -->
<?php
 
  $roundcorner = "";
  if($roundedcornersboxmodule=='Yes'){
     $roundcorner = ' -webkit-border-radius: 20px; -moz-border-radius: 20px;  border-radius: 20px;';
     
  };

  $shadowcontainer = "";
  if($boxshadowmodule=='Yes'){
     $shadowcontainer = ' -webkit-box-shadow: 7px 7px 5px 0px rgba(50, 50, 50, 0.75); 
          -moz-box-shadow:    7px 7px 5px 0px rgba(50, 50, 50, 0.75); 
           box-shadow:         7px 7px 5px 0px rgba(50, 50, 50, 0.75);';
  };

  echo '<div style="background-color: '.$modulebackgroundcolor.';'.$roundcorner.$shadowcontainer.'">';

?>

<!-- Title  and Subtitle lines ..................... -->

    <?php
      if(!empty($rows)){
         echo '<div class ="divshadow">'; 
         echo '<h1 class="shadowtitle">'.$titleline.'</h1>'; 
         echo '</div>';
         echo '<p style ="margin : 10px; font-size:'.$subtitlefontsize.'; color:'.$subtitlefontcolor.'">'.$subtitle.'</p>';
      };
     ?>
  
<!-- Filters ........................................ -->

    <div id="filters" class="button-group js-radio-button-group">
      	<button class="buttonfilter is-checked" data-filter="*">All</button>
          <?php 
          if(!empty($rows)){  
          foreach ($rowsfilter as $filteritem){
                 echo '<button class="buttonfilter" data-filter=".'.$filteritem->FilterValue.'">'.$filteritem->FilterValue.'</button>';
      	     };
          };
          ?>
    </div>

<!-- Main Content ..................... -->

    <div class="isotope">
    	
    <?php 
      if(!empty($rows)){
      foreach ($rows as $row){
           
    		$profillink = JROUTE::_("index.php?option=com_comprofiler&amp;task=userProfile&amp;user=".$row->id."&amp;Itemid=55");
    		$width = '800px';
    		$width = ', WIDTH, \''.$width .'\'';
    		$title = ', CAPTION, \''.$row->username.'\'';
    		$style = 'width=100%';
                $text  = '';
    		$tooltip = "./images/comprofiler/".$row->avatar;
    		$mousover = 'return overlib(unescape(\''. addslashes( $tooltip ) .'\')'. $title .', BELOW, RIGHT'. $width .');';
    		$tip = '<a href="#" onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</a>';
        	
    ?>
 
        <div class="element-item <?php echo $row->Filter; ?>" data-category="<?php echo $row->Filter; ?>">

             <div class = "boxprofile">                  
              
                <div class="avatardiv zoom_img" >  
            			
            	<a href="/index.php?option=com_comprofiler&amp;task=userProfile&amp;user=<?php echo $row->id;?>&amp;Itemid=55" 
                       >
                    <img class ="userfoto" 
                    style="display: block; 
                             width: 85%; 
                             height : 85%;
                             max-height: 90%;   
                             left: 0;  
                             right: 0;  
                             top: 0;  
                             bottom: 0;  
                             margin: auto;-webkit-touch-callout: none;
                              -webkit-user-select: none;
                              -webkit-box-shadow: 9px 10px 9px 2px rgba(0,0,0,0.53);
                              -moz-box-shadow: 9px 10px 9px 2px rgba(0,0,0,0.53);
                              box-shadow: 9px 10px 9px 2px rgba(0,0,0,0.53);
                              " 
                              src="./images/comprofiler/<?php if(substr($row->avatar, 0, 7)=='gallery'){echo $row->avatar;} else {echo $row->avatar;} ?>" 
                              alt="" border="0"  
                        />
                </a>
                  
                <a href="/index.php?option=com_comprofiler&task=userProfile&user=<?php echo $row->id;?>&Itemid=55"><?php echo $row->username; ?></a>
             
                </div>
            </div>  
             	             
            <div> 
             <div class = "linksdiv" >
              
 
                     <?php 
                          if(mb_strlen($cb_field1,'UTF-8')>3) { echo  $row->Field1." <br />"; }; 
                          if(mb_strlen($cb_field2,'UTF-8')>3) { echo  $row->Field2." <br />"; };
                          if(mb_strlen($cb_field3,'UTF-8')>3) { echo  $row->Field3;           };
                      ?>  
             </div>    
            </div>
            
              <?php 
                
                  if($showgallerieslink == "Yes" || $showemaillink == "Yes"){
                        echo '<div class = "linksdiv">';  
                       
                        if($showgallerieslink == "Yes")
                        {
                             echo '<div class = "linksdiv1">';   
                                 echo '<a href="javascript:void(0);" onclick=" bgslide('; echo $row->id; echo ')">';

                                 echo CountImages($row->id,$using_cbgallery)."    ";
                                 echo '   <img src="./modules/mod_listusers/images/camera.png" 
                                           alt="" border="0" height="20px" width="20px" />';
                                 echo '</a>';
                                 echo '     ';
                             echo '</div>';      
                          
                        };

                       if($showemaillink == "Yes")
                       {
                           echo '<div class = "linksdiv1">';  
                              echo '<a href="index.php?option=com_uddeim&task=new&recip='. $row->id.'">';
                              echo '<img src="./modules/mod_listusers/images/mail.png" alt="email" border="0" /></a>'; 
                           echo '</div>';     
                       };
                       
                      echo "</div>";
                };
  
              ?>

    <!--/div-->
		
	<?php

          if($using_cbgallery != "No") {
               GetImages($row->id,$using_cbgallery);
          };
        
        ?>

	</div>  <!-- Close div element item -->          
            
       <?php
   
       // Close for Loop for each row...
       };
      }; // Close for Not empty
   
       ?>


    </div>  <!-- close isotope div-->

</div>  <!-- close main module div-->
    <!-- div id = 'Contenedor' style="display:none">  </div-->

    <!-- Begin scripts area .............................................................................-->

    <script src="./modules/mod_listusers/js/jquery-1.11.2.min.js"></script>
    <script src="./modules/mod_listusers/js/jquery.colorbox-min.js"></script>
    <!--
    $document = & JFactory::GetDocument();
    $document->addScript(JURI::base() . 'modules/mod_listusers/js/jquery-1.11.2.min.js');
    $document->addScript(JURI::base() . 'modules/mod_listusers/js/jquery.colorbox-min.js);
    -->

    <script>

    function bgslide(obj) {

            function MM_openBrWindow(theURL,winName,features) { 
            window.open(theURL,winName,features);
            };

            jQuery('.cboxPhoto').on("contextmenu", function(e) {
                    console.log(e);
                    return false;
                    });
                                                        
        var $link = 'a.groupslider'+obj;
        jQuery($link).colorbox({ 
            rel: $link,
            slideshow:true,
            open: true,
            photo:true,
            width:"95%", 
            height:"95%",
            maxWidth :"100%",
            maxheight:"95%",	
            innerHeight:"100%",
            innerWidth :"100%",
            slideshowStop : "Stop Slideswhow",
            slideshowStart:"Start Slideshow",
            current : "Image {current} of {total}" ,
            scalePhotos: true	 					   
            });
    };


     var resizeTimer;
     function resizeColorBox()
        {
            if (resizeTimer) clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                    if (jQuery('#cboxOverlay').is(':visible')) {
                            jQuery.colorbox.load(true);
                    }
            }, 300);
        }

     // Resize Colorbox when resizing window or changing mobile device orientation
     jQuery(window).resize(resizeColorBox);
     window.addEventListener("orientationchange", resizeColorBox, false);

    </script>

    <script src="./modules/mod_listusers/js/isotope.pkgd.min.js"></script>
  
    <script>     
 <!-- Begin scripts area .............................................................................-->
        jQuery(document).ready(function(){ 
        document.oncontextmenu = function() {return false;};

 <!-- Begin scripts area .............................................................................-->
        jQuery(document).mousedown(function(e){ 
          if( e.button == 2 ) { 
              // alert('Download images are not allowed...'); 
              return false; 
              } 
            return true; 
          }); 
        });

     		$( function() {
     
      		var $container = jQuery('.isotope').isotope({
        						itemSelector: '.element-item'
        						//,masonry: { }
      							});
  <!-- Begin scripts area .............................................................................-->
          jQuery('.isotope').isotope({
            itemSelector: '.item'
              //,masonry: {}
          });
      		// bind filter button click
      		jQuery('#filters').on( 'click', 'button', function() {
        						var filterValue = jQuery( this ).attr('data-filter');
         						console.log(filterValue);
         						$container.isotope({ filter: filterValue });
           						});

      		// change is-checked class on buttons
      		jQuery('.button-group').each( function( i, buttonGroup ) {
        						var $buttonGroup = jQuery( buttonGroup );
        						$buttonGroup.on( 'click', 'button', function() {
          										$buttonGroup.find('.is-checked').removeClass('is-checked');
      											jQuery( this ).addClass('is-checked');
        										});
      							});
      
    		});    
    </script>

<?php
   
    if($poweredbysistemashv == 'Yes'){
            Echo '<p style="color:black; text-align: Center">Powered by <a href="http://www.sistemashv.com">Sistemas HV</a></p>';
       };

?>

  
