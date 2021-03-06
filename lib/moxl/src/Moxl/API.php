<?php

namespace Moxl;

use Movim\Session;

class API
{
    static function iqWrapper($xml = false, $to = false, $type = false, $id = false)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $iq = $dom->createElementNS('jabber:client', 'iq');
        $dom->appendChild($iq);

        $me = \App\User::me();

        if ($me->id && $me->session->resource) {
            $iq->setAttribute(
                'from',
                $me->id.'/'.$me->session->resource);
        }

        if($to != false) {
            $iq->setAttribute('to', $to);
        }

        if($type != false) {
            $iq->setAttribute('type', $type);
        }

        global $language;

        $session = Session::start();
        if($id == false) $id = $session->get('id');
        $iq->setAttribute('id', $id);

        if(isset($language)) {
            $iq->setAttribute('xml:lang', $language);
        }

        if($xml != false) {
            if(is_string($xml)) {
                $f = $dom->createDocumentFragment();
                $f->appendXML($xml);
                $iq->appendChild($f);
            } else {
                $xml = $dom->importNode($xml, true);
                $iq->appendChild($xml);
            }
        }

        return $dom->saveXML($dom->documentElement);
    }

    /*
     *  Call the request class with the correct XML
     */
    static function request($xml)
    {
        writeXMPP($xml);
    }
}
