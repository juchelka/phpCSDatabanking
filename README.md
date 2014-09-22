phpCSDatabanking
================

Mini library to load transactions from Business 24 Databanking Česká spořitelna for PHP

**Work in progress!**

example:
```
$banking = new DataBanking();
$client = $banking->createClient($username, $password);

$account = new Account($prefix, $number, $bankcode);
$filter = new Filter($start, $end);

$transactions = $client->getIncomingTransactions($account, $filter);
```

TODO
----
* tests
* complete annotations
* composer package
* travis?
