<?php
require_once('Yahoo.inc');

if ($_GET['tabNum']){
   echo build_tabs($_GET['tabNum']);
} else {
   //define application key constants
   define("CONSUMER_KEY","<your_app_consumer_key_from_YDN>");
   define("CONSUMER_SECRET","<your_app_consumer_key_secret_from_YDN>");   

   //initialize 2-legged OAuth session
   $yahooSession = new YahooApplication(CONSUMER_KEY, CONSUMER_SECRET);
   
   //set up static html / style block
   $tabNum = ($_GET['tabNum']) ? $_GET['tabNum'] : 1;
   $tabCode = "<style>
            div.pad5{ padding:5px }
            div.tabGroup{ position:relative; border-bottom:0; }
            div.tabGroup div{ background-color:#fff; float:left; width:50px; border:1px solid #795089; 
                          border-bottom:0; padding:5px 10px; text-align:center; 
                          font:bold 12px arial,helvetica,sans-serif; cursor:pointer; }
            div.tabGroup div a{ color:#795089; text-decoration:none; }
            div.tabGroup div.selected{ background-color:#795089; }
            div.tabGroup div.selected a{ color:#fff; }
            div.tabGroup div:hover{ background-color:#e2ceea; }
            div.tabGroup div.selected:hover{ background-color:#795089; }
            div.tabContent{ border:1px solid #795089; border-top:10px solid #795089; padding:5px 10px; }
            div.myBox{ width:232px; height:44px; background-image:url(http://l.yimg.com/a/i/ww/beta/y3.gif); }
            </style>";
   $tabCode .= "<div class='pad5'>" . build_tabs($tabNum) . '</div>';
   
   $guid = "change-to-your-GUID";
   if (!$yahooSession->setSmallView($guid, $tabCode)){
      //echo "UNABLE TO SET SMALL VIEW";
   }
   
   echo $tabCode;
}
   
function build_tabs($tabNum){
   $tabCode = "<div id='tabContainer'><div class='tabGroup'>";
   
   //create tab set using the tabNum as the selected tab
   for ($i = 1; $i < 4; $i++){
      $isSelected = ($i === intval($tabNum)) ? 'selected' : '';
      $tabCode .= "<div class=\"$isSelected\"><yml:a params=\"?tabNum=$i\" 
                   replace=\"tabContainer\">Tab $i</yml:a></div>";
   }
   
   //create tab content
   $tabContent = '';
   switch($tabNum){
      case 1: $tabContent = 'Hi <yml:name uid="viewer" />! This is tab content 1!'; break;
      case 2: $tabContent = 'Here is my profile badge:<br /><yml:user-badge />'; break;
      case 3: $tabContent = 'My Pics:<br /><yml:image 
                             src="http://farm1.static.flickr.com/195/449142212_c2e72f83d7.jpg"
                        height="166" width="250" alt="Faceball" />'; break;
      default: break;
   }
   
   //build tab content with defined string
   $tabCode .= "<br style='clear:both'></div><div class='tabContent'>$tabContent</div></div>";
   return $tabCode;
}
?>
