<?php
namespace CSDatabanking;

use \DateTime as DateTime;

class Transaction {

    private $data;

    public function __construct(\stdClass $data) {
        $this->data = $data;
    }

    public function __toString() {
        $array = $this->toArray();
        return implode(" ",array_map(function ($key, $value) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }
            return "$key: $value";
        }, array_keys($array), $array));
    }

    public function toArray() {
        $relevantData = array_intersect_key((array) $this->data, array_flip([
            "ReferenceNumber",
            "PayerVariableSymbol",
            "ConstantSymbol",
            "SpecificSymbol",
            "OtherAccountName",
            "StornoFlag",
            "AccCurrency",
            "Currency",
            "MessageForPrincipal",
            "MessageToRecipient",
            "TransactionTypeDescription",
        ]));
        $relevantData['TargetAccount'] = $this->data->TargetAccount->Prefix."-".$this->data->TargetAccount->Number."/".str_pad($this->data->TargetAccount->Bankcode,4,0,STR_PAD_LEFT);
        $relevantData['AccountingDate'] = new DateTime($this->data->AccountingDate);
        $relevantData['EffectiveDate'] = new DateTime($this->data->EffectiveDate);
        $relevantData['AccAmount'] = floatval($this->data->AccAmount);
        $relevantData['Amount'] = floatval($this->data->Amount);
        return $relevantData;
    }

    public function isIncommingTransaction() {
        return $this->data->AccAmount > 0;
    }

}
