<?php
namespace CSDatabanking;

use \SoapVar as SoapVar;

class WsseAuthHeader extends \SoapHeader {

    const WSS_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSU_NS  = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';

    function __construct($user, $pass) {

        $created = gmdate('Y-m-d\TH:i:s\Z');
        $nonce = mt_rand();
        $passdigest = base64_encode( pack('H*', sha1( pack('H*', $nonce) . pack('a*',$created).  pack('a*',$pass))));

        $auth = new \stdClass();
        $auth->Username = new SoapVar($user, XSD_STRING, NULL, self::WSS_NS, NULL, self::WSS_NS);
        $auth->Password = new SoapVar($pass, XSD_STRING, NULL, self::WSS_NS, NULL, self::WSS_NS);
        $auth->Nonce = new SoapVar($passdigest, XSD_STRING, NULL, self::WSS_NS, NULL, self::WSS_NS);
        $auth->Created = new SoapVar($created, XSD_STRING, NULL, self::WSS_NS, NULL, self::WSU_NS);

        $username_token = new \stdClass();
        $username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, self::WSS_NS, 'UsernameToken', self::WSS_NS);

        $security_sv = new SoapVar(
            new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, self::WSS_NS, 'UsernameToken', self::WSS_NS),
            SOAP_ENC_OBJECT, NULL, self::WSS_NS, 'Security', self::WSS_NS);
        parent::__construct(self::WSS_NS, 'Security', $security_sv, true);
    }
}
