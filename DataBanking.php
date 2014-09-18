<?php
namespace CSDatabanking;

require_once __DIR__ . '/WsseAuthHeader.php';
require_once __DIR__ . '/Transaction.php';
require_once __DIR__ . '/Account.php';
require_once __DIR__ . '/Filter.php';
require_once __DIR__ . '/Client.php';

class DataBanking {

    public function createClient($username, $password) {
        $client = new \SoapClient(__DIR__."/databankingService.wsdl", [
            'trace' => 1,
            'exceptions' => 1,
        ]);

        $client->__setSoapHeaders(array(new WsseAuthHeader($username, $password)));
        return new Client($client);
    }


}
