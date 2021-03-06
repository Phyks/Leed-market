<?php
/*
@name Url Clean
@author Olivier <http://olivierlebris.me>
@link http://cybride.net/olivier
@licence CC by nc sa http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
@version 2.1.0
@description Used to cleanup url from some crap (xtor, utm_) and use url id of RSS feed for clean Feedbrner(feedproxy), feedsportal url
*/

function urlclean_plugin_link(&$events){
	foreach($events as $event){
        $link = $event->getLink();

        if (preg_match('#feedproxy#',$link) or preg_match('#feedsportal#',$link)){
            $events_load = new Event();
            $event_load = $events_load->load(array('id'=>$event->getid()));
            $link = $event_load->getGuid();
        }
        $link = preg_replace("/[&#?]xtor=(.)+/", "", $link);
        $link = preg_replace("/utm_([^&#]|(&amp;))+&*/", "", $link);
        $link = preg_replace("/\?&/", "", $link);
        if (isset($link[strlen($link) -1])){
            if ($link[strlen($link) -1] == '?')
                $link = substr($link, 0, strlen($link) -1);
        }

        $event->setLink($link);
	}
}

Plugin::addHook("index_post_treatment", "urlclean_plugin_link");  
?>