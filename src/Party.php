<?php
    namespace Mintopia\Hackify;
    
    use GuzzleHttp\Client;
    
    class Party
    {
        const FESTIFY_BASE_URL = 'http://festify.us';
        
        protected $id;
        
        public function __construct($partyId)
        {
            $this->id = $partyId;
        }
        
        
        public function getCurrentTrack()
        {
            $result = $this->get('/currentTrack');
            return new Track($this, $result);
        }
        
        public function getQueue()
        {
            $return = [];
            $result = $this->get('/queue');
            foreach ($result as $data) {
                $return[] = new Track($this, $data);
            }
            return $return;
        }
        
        public function get($url, $params = [])
        {
            $args = [];
            if ($params) {
                $args['query'] = $params;
            }
          
            return $this->makeRequest('GET', $url, $args);
        }
        
        public function post($url, $data)
        {
            $args = [];
            if ($data) {
                $args['json'] = $data;
            }
            return $this->makeRequest('POST', $url, $args);
        }
        
        protected function makeRequest($method, $url, $params = [])
        {
            $fullUrl = self::FESTIFY_BASE_URL . '/api/parties/' . $this->id . $url;
            $client = new Client;
            $response = $client->request($method, $fullUrl, $params);
            return json_decode($response->getBody());
        }
    }
    