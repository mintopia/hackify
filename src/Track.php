<?php
    namespace Mintopia\Hackify;
    
    use Carbon\Carbon;
    
    class Track
    {
        public $name;
        public $spotifyID;
        public $isFallbackTrack = false;
        public $playing = false;
        public $votes = 0;
        public $played;
        public $added;
        public $party;
        
        public function __construct($party, $data = null)
        {
            $this->party = $party;
            $this->populateFromData($data);
        }
        
        public function populateFromData($data)
        {
            if (!$data) {
                return;
            }
            
            $this->name = $data->name;
            $this->spotifyID = $data->spotifyID;
            $this->isFallbackTrack = (bool) $data->isFallbackTrack;
            $this->playing = (bool) $data->playing;
            $this->votes = (int) $data->votes;
            if ($data->played) {
                new Carbon($data->played);
            }
            $this->added = new Carbon($data->added);
        }
        
        public function vote()
        {
            $data = (object) [
                'name' => $this->name,
                'spotifyID' => $this->spotifyID
            ];
            $result = $this->party->post('/queue', $data);
            $this->populateFromData($result);
        }
    }