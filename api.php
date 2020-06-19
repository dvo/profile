<?php

if(isset($_POST['post'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    $fileName = "data/public/posts/" . $_POST['id'] . ".xml";
    $xml = new DOMDocument('1.0');
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = true;
    $post = $xml->createElement("post");
    $url = $xml->createElement('url', $_POST['url']);
    $body = $xml->createElement('body', $_POST['post']);
    $post->appendChild($url);
    $post->appendChild($body);
    $xml->appendChild($post);
    $xml->save($fileName);
    }
}
if(isset($_POST['change'])){
    $old = md5($_POST['o_password']);
    $new = md5($_POST['n_password']);
    $c_new = md5($_POST['c_n_password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if (strcasecmp($old, $xml->password) == 0) {
        if ($new = $c_new){
            $xml->password = $new;
            $xml->asXML('data/private/user.xml');
            $result = '1';
        } else {
            $result = '2';
        }
    } else {
        $result = '3';
    }
    echo $result;
    exit;
}
if(isset($_POST['message'])){
    $inbox = simplexml_load_file('data/private/inbox.xml');
    $msg = $inbox->addChild('message');
    $msg->addChild('from', $_POST['from']);
    $msg->addChild('subject', $_POST['subject']);
    $msg->addChild('body', $_POST['message']);
    file_put_contents('data/private/inbox.xml', $inbox->asXML());
    // format the xml   
    $doc = new DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->load('data/private/inbox.xml');
    $doc->formatOutput = true;
    file_put_contents('data/private/inbox.xml', $doc->saveXML());
}

if(isset($_POST['getMail'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    header ("Content-Type:text/xml");
    $inbox = file_get_contents('data/private/inbox.xml');
    echo $inbox;
    }
}
?>
