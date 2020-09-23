<?php

namespace Sunnysideup\EcommerceAdvancedRetailConnection\Api;

use SilverStripe\Core\Config\Config;
use SilverStripe\View\ViewableData;

// $obj = Injector::inst()->get('AdvanceRetailConnector');
// $obj->getAvailability(123)

class AdvanceRetailConnector extends ViewableData
{
    private static $base_url = '';

    private $debug = false;

    private $startTime = 0;

    public function setDebug($b)
    {
        $this->debug = $b;
    }

    /**
     * @param  array $productCodes
     * e.g.  111, 112
     *
     * @return array
     *  e.g.
     *   111 => 3,
     *   112 => 4
     */
    public function getAvailability($productCodes, $branchID = null)
    {
        if ($this->debug) {
            $this->startTime = microtime(true);
            echo '<hr /><hr /><h1>' . implode(',', $productCodes) . '</h1>';
        }
        $array = [
            'content' => [
                'itemIds' => $productCodes,
                'branchId' => $branchID,
                'branchIdsExcluded' => [],
                'availableSince' => null,
                'onlyStoresWithStock' => false,
            ],
            'header' => [
                'createdAt' => 'tba', //"2016-09-15T09:54:18.5898548+12:00",
                'branchNumber' => 0,
                'workstationNumber' => 0,
                'operatorCode' => 'system',
                'sessionId' => 'tba', //17FD6B16-2DA2-44FD-87EF-1F77072B8C68
            ],
        ];
        if ($this->debug) {
            $data = json_encode($array, JSON_PRETTY_PRINT);
            echo '<h2>submitting</h2>';
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        } else {
            $data = json_encode($array);
        }
        $url = Config::inst()->get(AdvanceRetailConnector::class, 'base_url') . '/ares/product/2.0/rest/StockAvailability';
        if ($this->debug) {
            echo '<h2>to</h2>' . $url;
        }

        $response = $this->executeRequest(
            $url,
            $method = 'POST',
            $data
        );
        // parse the XML body
        $productsAvailable = [];
        if ($response && isset($response->result)) {
            foreach ($response->result as $branchData) {
                if (isset($branchData->itemId)) {
                    if (! isset($productsAvailable[$branchData->itemId])) {
                        $productsAvailable[$branchData->itemId] = 0;
                    }
                    if (isset($branchData->available)) {
                        $productsAvailable[$branchData->itemId] += intval($branchData->available);
                    }
                }
            }
        }
        if ($this->debug) {
            echo '<h2>response: ' . implode(',', $productsAvailable) . '</h2><pre>';
            print_r($response);
            echo '</pre>';
            $timeTaken = round((microtime(true) - $this->startTime) * 1000) . ' microseconds (1000 microseconds in one second)';
            echo '<h2>Time Taken: ' . $timeTaken . '</h2>';
        }

        return $productsAvailable;
    }

    /**
     * @param  array $productCodes
     * e.g.  111, 112
     *
     * @return array
     *  e.g.
     *   111 => 3,
     *   112 => 4
     */
    public function getInventoryChanges()
    {
        if ($this->debug) {
            $this->startTime = microtime(true);
        }

        $array = [
            'content' => [
                'fullCatalogue' => true,
                'since' => '',
            ],
            'header' => [
                'createdAt' => 'tba', //"2016-09-15T09:54:18.5898548+12:00",
                'branchNumber' => 0,
                'workstationNumber' => 0,
                'operatorCode' => 'system',
                'sessionId' => 'tba', //17FD6B16-2DA2-44FD-87EF-1F77072B8C68
            ],
        ];

        if ($this->debug) {
            $data = json_encode($array, JSON_PRETTY_PRINT);
            echo '<h2>submitting</h2>';
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        } else {
            $data = json_encode($array);
        }

        $url = Config::inst()->get(AdvanceRetailConnector::class, 'base_url') . '/ares/product/2.0/rest/inventoryChanges';

        if ($this->debug) {
            echo '<h2>to</h2>' . $url;
        }

        $response = $this->executeRequest(
            $url,
            $method = 'POST',
            $data
        );

        // parse the XML body
        $inventory = [];

        if ($response && isset($response->result)) {
            foreach ($response->result as $product) {
                if (isset($product->itemId)) {
                    $inventory[$product->itemId] = [
                        'description' => $product->description,
                        'itemId' => $product->itemId,
                        'action' => $product->action,
                        'sellingPrice' => $product->sellingPrice,
                    ];
                }
            }
        }

        if ($this->debug) {
            echo '<pre>';
            print_r($inventory);
            echo '</pre>';
            $timeTaken = round((microtime(true) - $this->startTime) * 1000) . ' microseconds (1000 microseconds in one second)';
            echo '<h2>Time Taken: ' . $timeTaken . '</h2>';
        }

        return $inventory;
    }

    private function executeRequest($url, $method, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1); //timeout in seconds
        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }
        $response = curl_exec($ch);

        if ($response) {
            $response = json_decode($response);
        } elseif ($this->debug) {
            echo '<hr />ERROR IN GETTING RESPONSE!<hr /><pre>';
            echo 'Curl error: ' . curl_error($ch);
            $info = curl_getinfo($ch);
            var_dump($info);
            echo '</pre>';
        }

        curl_close($ch);
        return $response;
    }
}
