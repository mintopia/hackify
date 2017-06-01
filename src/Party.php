<?php
    namespace Mintopia\Hackify;
    
    use GuzzleHttp\Client;
    use Mintopia\Hackify\Exceptions\NotAuthorisedException;
    
    class Party
    {
        const FESTIFY_BASE_URL = 'http://festify.us';
        
        protected $id;
        protected $adminPassword;
        
        public function __construct($partyId)
        {
            $this->id = $partyId;
        }
        
        public function setAdminPassword($password)
        {
            $this->adminPassword = $password;
        }
        
        public function isAdmin()
        {
            return $this->adminPassword !== null;
        }
        
        public function next()
        {
            if (!$this->isAdmin())
            {
                throw new NotAuthorisedException('Admin password not specified');
            }
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

        public function vote($spotifyId)
        {
            $data = (object) [
                'spotifyID' => $spotifyID
            ];
            $result = $this->post('/queue', $data);
            return new Track($this, $result);
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
            if ($this->adminPassword) {
                if (isset($params['headers'])) {
                    $params['headers'] = [];
                }
                $params['headers']['Admin-Password'] = $this->adminPassword;
            }
            $fullUrl = self::FESTIFY_BASE_URL . '/api/parties/' . $this->id . $url;
            $client = new Client;
            $response = $client->request($method, $fullUrl, $params);
            return json_decode($response->getBody());
        }
    }
    