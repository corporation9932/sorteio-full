<?php
class NitroAPI {
    private $api_key = 'SUA_API_KEY_AQUI';
    private $base_url = 'https://api.nitropagamentos.com.br/v1/';
    
    public function createPixPayment($amount, $description, $customer_data) {
        $data = [
            'amount' => $amount,
            'description' => $description,
            'customer' => $customer_data,
            'payment_method' => 'pix',
            'expires_in' => 1800 // 30 minutos
        ];
        
        return $this->makeRequest('payments', 'POST', $data);
    }
    
    public function checkPaymentStatus($payment_id) {
        return $this->makeRequest('payments/' . $payment_id, 'GET');
    }
    
    private function makeRequest($endpoint, $method, $data = null) {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->base_url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key,
                'Content-Type: application/json'
            ]
        ]);
        
        if ($data && $method !== 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        return [
            'status_code' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
}
?>