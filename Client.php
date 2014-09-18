<?php
namespace CSDatabanking;

class Client {

    const JAVA_NS = 'java:cz.csas.mci.cbl.bom.pub';

    /**
     * @var \SoapClient
     */
    private $client;

    public function __construct(\SoapClient $client) {
        $this->client = $client;
    }

    public function getTransactionHistory(Account $account, Filter $filter) {
        $response = $this->client->getTransactionHistory([
            "account"=> $this->convertAccountToSoapVar($account),
            "filter" => $this->convertFilterToSoapVar($filter),
            "pgInfo" => '',
            "allPages" => true,
        ]);

        if (isset($response->return->TrnHistoryElements)) {
            if (!is_array($response->return->TrnHistoryElements)) {
                $transactions = array($response->return->TrnHistoryElements);
            } else {
                $transactions = $response->return->TrnHistoryElements;
            }

            return array_map(function ($trn) {
                return new Transaction($trn);
            }, $transactions);
        } else {
            return array();
        }
    }

    public function getIncomingTransactions(Account $account, Filter $filter) {
        return array_filter($this->getTransactionHistory($account, $filter), function ($trn){
            /** @var $trn Transaction */
            return $trn->isIncommingTransaction();
        });
    }

    private function convertAccountToSoapVar(Account $account)
    {
        $struct = new \stdClass();
        $struct->Bankcode = new \SoapVar($account->bankcode, XSD_INT, null, null, null, self::JAVA_NS);
        $struct->Prefix = new \SoapVar($account->prefix, XSD_INT, null, null, null, self::JAVA_NS);
        $struct->Number = new \SoapVar($account->number, XSD_LONG, null, null, null, self::JAVA_NS);
        return new \SoapVar($struct, XSD_ANYTYPE);
    }

    private function convertFilterToSoapVar(Filter $filter)
    {
        $struct = new \stdClass();
        $struct->AccountingMode = new \SoapVar('A', XSD_STRING, null, null, null, self::JAVA_NS);
        $struct->DtStart = new \SoapVar($filter->dateStart->format('c'), XSD_DATE, null, null, null, self::JAVA_NS);
        $struct->DtEnd = new \SoapVar($filter->dateEnd->format('c'), XSD_DATE, null, null, null, self::JAVA_NS);
        return new \SoapVar($struct, SOAP_ENC_OBJECT);
    }

    public function requestDebug() {
        echo "Last Request:\n\n";
        echo $this->client->__getLastRequestHeaders()."\n\n";
        echo self::tidyit($this->client->__getLastRequest())."\n\n";
        echo $this->client->__getLastResponseHeaders()."\n\n";
        echo self::tidyit($this->client->__getLastResponse())."\n";

    }

    private static function tidyit($in) {
        $tidy = new \Tidy;
        $tidy->parseString($in, [
            'indent'         => true,
            'input-xml'   => true,
            'wrap'           => 200
        ], 'utf8');
        return $tidy;
    }
}
