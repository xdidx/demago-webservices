<?php
include 'common.php';

$config = array(
    'server' => 'irc.twitch.tv',
    'port' => 6667,
    'channel' => '#xdidx',
    'name' => 'xdidx',
    'nick' => 'xdidx',
    'pass' => 'oauth:b4l3sh0wkxpocxco9udzz4wc9zlf3o' //http://twitchapps.com/tmi/
);


$server = array();
$server['connect'] = fsockopen($config['server'], $config['port']);

if ($server['connect']) {
    SendData("PASS " . $config['pass'] . "\n\r");
    SendData("NICK " . $config['nick'] . "\n\r");
    SendData("USER " . $config['nick'] . "\n\r");
    SendData("JOIN " . $config['channel'] . "\n\r");

    while (!feof($server['connect']) ) {
        $response = fgets($server['connect'], 128);

        $messageRequest = Database::getConnection()->prepare('INSERT INTO message(content, date) values(?,?)');
        $messageRequest->execute(array($response, time()));

        if (preg_match('/PING/', $response, $matches)) {
            SendData("PONG\n\r");
        }

        if (preg_match('/PRIVMSG #(.{1,20}) :(.+)/', $response, $matches)) {
            $username = $matches[1];
            $message = $matches[2];

            if (preg_match('/vote:(.{1,20})/', $message, $voteInformations)) {
                $possibilityCode = substr($voteInformations[1], 0, strlen($voteInformations[1])-1);
                $possibilityCode = explode(' ', $possibilityCode)[0];

                $possibility = Database::getOne('possibilities', array('code' => $possibilityCode));
                if ($possibility) {
                    $idea = Database::getOne('ideas', array('id' => $possibility->idea));
                    if ($idea) {
                        $idea->removeVote($username);

                        $vote = new Vote();
                        $vote->possibility = $possibility->id;
                        $vote->user = $username;
                        $vote->save();

                        echo 'Vote '.$possibility->name.' ('.$possibility->id.') ajoute pour '.$username."\n";
                    } else {
                        echo "Idee non trouve\n";
                    }
                } else {
                    echo 'Possibilite inexistante : '.htmlspecialchars(trim($voteInformations[1]))."\n";
                }
            }
        }
    }
    fclose($server['connect']);
}

function SendData($cmd)
{
    global $server;
    fwrite($server['connect'], $cmd, strlen($cmd));
}
