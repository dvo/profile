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

if(isset($_POST['comment'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    $comments = simplexml_load_file('data/public/comments.xml');
    $comment = $comments->addChild('comment');
    $comment->addChild('id', $_POST['id']);
    $comment->addChild('url', $_POST['url']);
    $comment->addChild('body', $_POST['comment']);
    $comment->addChild('user', $_POST['user']);        
    file_put_contents('data/public/comments.xml', $comments->asXML());
    // format the xml   
    $doc = new DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->load('data/public/comments.xml');
    $doc->formatOutput = true;
    file_put_contents('data/public/comments.xml', $doc->saveXML());
    }
}
if(isset($_POST['post'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    $posts = simplexml_load_file('data/public/posts.xml');
    $post = $posts->addChild('post');
    $post->addChild('id', $_POST['id']);
    $post->addChild('url', $_POST['url']);
    $post->addChild('body', $_POST['post']);
    $post->addChild('user', $_POST['user']);        
    file_put_contents('data/public/posts.xml', $posts->asXML());
    // format the xml   
    $doc = new DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->load('data/public/posts.xml');
    $doc->formatOutput = true;
    file_put_contents('data/public/posts.xml', $doc->saveXML());
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

if(isset($_POST['reaction'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    $likes = simplexml_load_file('data/public/likes.xml');
    $exists = false;
    foreach ($likes->item as $item)
    {
        if ($item->url == $_POST['url'])
        {
            $exists = true;
            if ($_POST['type'] == 'like'){
               $item->liked = 'true';
            }
            if ($_POST['type'] == 'dislike'){
                $item->liked = 'false';
            }
            if ($_POST['type'] == 'undo'){
                unset($item[0]);
            }
        }
    }    
    
    if (!$exists){
    $like = $likes->addChild('item');
    $like->addChild('url', $_POST['url']);
        if ($_POST['type'] == 'like'){
            $like->addChild('liked', 'true');
        }
        if ($_POST['type'] == 'dislike'){
            $like->addChild('liked', 'false');
        } 
    }
        
    file_put_contents('data/public/likes.xml', $likes->asXML());
    // format the xml   
    $doc = new DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->load('data/public/likes.xml');
    $doc->formatOutput = true;
    file_put_contents('data/public/likes.xml', $doc->saveXML());
    }
}

if(isset($_POST['sync'])){
    $password = md5($_POST['password']);
    $xml = new SimpleXMLElement('data/private/user.xml', 0, true);
    if ($password == $xml->password) {
    $peers = simplexml_load_file('data/public/peers.xml');
    $peer = $peers->addChild('url', $_POST['url']);
    file_put_contents('data/public/peers.xml', $peers->asXML());
    // format the xml   
    $doc = new DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->load('data/public/peers.xml');
    $doc->formatOutput = true;
    file_put_contents('data/public/peers.xml', $doc->saveXML());
    }
}

?>
