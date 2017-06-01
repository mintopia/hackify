<?php
    require_once('vendor/autoload.php');
    
    use Mintopia\Hackify\Party;
    
    $party = new Party('58a5c7ebf2d97f12a0a18672');
    $track = $party->getCurrentTrack();
    
    $queue = $party->getQueue();
    
    foreach ($queue as $track) {
        echo str_pad($track->name, 60);
        echo $track->votes;
        echo "\r\n";
    }
    
    $theTrack = null;
    foreach ($queue as $track) {
        if (strpos($track->name, 'Bring Me To Life') !== false) {
            $theTrack = $track;
            break;
        }
    }
    if (!$theTrack) {
        exit();
    }
    
    for ($i = 0; $i < 5; $i++) {
        echo "Adding Vote\r\n";
        $theTrack->vote();
    }