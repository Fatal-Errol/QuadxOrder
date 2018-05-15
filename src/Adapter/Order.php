<?php

namespace QuadxOrder\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class Order {
    private $api_url;
    private $timezone;
    private $client;
    
    /**
     * Order constructor
     * 
     * @param type $api_url The Base URL to be used in queries
     * @param type $timezone The default timezone to be used
     */
    public function __construct($api_url, $timezone = null)
    {
        $this->api_url = $api_url;
        $this->timezone = $timezone;
        
        $headers = ['Content-Type' => 'application/json'];
        
        if (!empty($this->timezone)) {
            $headers['Time-Zone'] = $this->timezone;
        }
        
        $this->client = new Client([
            'headers' => $headers
        ]);
    }
    
    /**
     * Fetches and formats the order data from the API
     * 
     * @param array $product_codes List of order tracking code
     * @return array Formatted meta data of orders
     */
    public function getOrders($product_codes)
    {
        $formatted_output = [
            'items' => [],
            'total_collections' => 0,
            'total_sales' => 0
        ];
        
        $promises = [];
        
        foreach ($product_codes as $key => $code) {
            $promises[$key] = $this->executeAsync('GET', 'orders/'.$code);
        }
        
        $results = Promise\settle($promises)->wait();
        
        foreach ($results as $key => $result) {
            if (!$result || empty($result['value']) || $result['value']->getStatusCode() != 200) {
                //This can be enhanced to handle the error gracefully, possibly
                //by retrying a certain amount of time before failing. In the meantime
                //I'll skip the data for now and inform the user that the data
                //failed to be fetched
                $formatted_output['items'][$key]['tracking_number'] = $product_codes[$key];
                $formatted_output['items'][$key]['status'] = 'failed to fetch data';
                continue;
            }
            
            $body = json_decode($result['value']->getBody(), true);
            
            $current_order = $this->prepareOrderData($body);
            
            $formatted_output['total_collections'] += floatval($current_order['total']);
            $formatted_output['total_sales'] += $this->getOrderTotalSales($current_order);            
            $formatted_output['items'][$key] = $current_order;
        }
        
        $formatted_output['total_collections'] = 
                number_format($formatted_output['total_collections'], 2, '.', '');
        $formatted_output['total_sales'] = 
                number_format($formatted_output['total_sales'], 2, '.', '');
        
        return $formatted_output;
    }
    
    /**
     * Computes the total sales per order
     * 
     * @param array $current_order Order meta data
     * @return float The total order sales of an order
     */
    private function getOrderTotalSales($current_order)
    {
        return floatval($current_order['shipping_fee']) +
                floatval($current_order['insurance_fee']) +
                floatval($current_order['transaction_fee']);   
    }
    
    /**
     * Wrapper method for the async call
     * 
     * @param string $method HTTP method to be used
     * @param string $endpoint URL modifier to be appended in the base URL
     * @param array $data Data to be passed in the request
     * @return object The promise object
     */
    private function executeAsync($method, $endpoint, $data = [])
    {
        if (!empty($this->timezone)) {
            $data['headers']['Time-Zone'] = $this->timezone;
            $data['headers']['Content-Type'] = 'application/json';
        }
        
        return $this->client->requestAsync($method, $this->api_url.$endpoint, $data);
    }
    
    /**
     * Parses and formats the raw order data from the API
     * 
     * @param array $raw_data The data returned by the API
     * @return array The formatted data
     */
    private function prepareOrderData($raw_data)
    {
        $prepared_data = [
            'history' => []
        ];
        
        $accepted_keys = [
            'tracking_number', 'status', 'subtotal', 'shipping', 'tax', 'fee',
            'insurance', 'discount', 'total', 'shipping_fee', 'insurance_fee',
            'transaction_fee'
        ];
        
        foreach ($accepted_keys as $key) {
            $prepared_data[$key] = (!empty($raw_data[$key]) ? $raw_data[$key] : '');
        }
        
        if (!empty($raw_data['tat'])) {
            foreach ($raw_data['tat'] as $status => $timestamp) {
                $prepared_data['history'][$timestamp] = [
                    'date' => date('Y-m-d H:i:s.u', intval($timestamp)),
                    'status' => $status
                ];
            }
            
            ksort($prepared_data['history']);
            $prepared_data['history'] = array_values($prepared_data['history']);
        }
                
        return $prepared_data;
    }
}
