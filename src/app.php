<?php

require(dirname(__DIR__) . '/vendor/autoload.php');

require('functions.php');

require('links.php');

$app = new \atlatl\Core();

function handleUpload($state) {
    
}

$app->serve([
    '^/$' => function($state) {
        return rendertpl('frontpage', [
            'max_file_size' => bytes_to_megabytes(max_upload_size()),
            'title' => 'Jyraphe, share files freely',
        ]);
    },
    'POST:^/ajax/upload$' => function($state) {
        $filename = @$_SERVER['HTTP_X_FILE_NAME'];
        if(!$filename) {
            die();
        }

        $upload_path = paths('var') . '/' . $filename;

        file_put_contents($upload_path, file_get_contents('php://input'));

        $links = new JyrapheLinks();
        $link = $links->makeLink($upload_path);

        //header("Content-Type: application/json");
        //echo json_encode(['link' => $link]);
        return $state->server->siteUrl($link);
    },
    'POST:^/$' => function($state) {
        $file_path = $state->request->getFile('file', paths('var'));
        
        if($file_path == paths('var')) {
            die("The upload failed. Most likely the 'var' folder doesn't exist.");
        }
        
        $links = new JyrapheLinks();
        $link = $links->makeLink($file_path);

        return rendertpl('frontpage', [
            'max_file_size' => bytes_to_megabytes(max_upload_size()),
            'title' => 'Jyraphe, share files freely',
            'link' => $state->server->siteUrl($link),
            'rand' => rand(1000, 9999),
        ]);
    },
    '/([a-zA-Z0-9]+)$' => function($state, $link) {
        $links = new JyrapheLinks();
        $filepath = $links->getLink($link);

        if(!$filepath) {
            return "not found";
        }

        if(!file_exists($filepath)) {
            return "file not found";
        }
        
        $file_mime = mime_content_type($filepath);

        $stat = stat($filepath);
        $file_name = basename($filepath);
        $file_size = $stat['size'];

        header('Content-Length: ' . $file_size);
        header('Content-Type: ' . $file_mime);

        if($state->request->get('dl') || !mime_is_viewable($file_mime)) {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        
        readfile($filepath);
        die(); // TODO: Maybe rewrite to use a proper response object?
    },
    '/install' => function($state) {
        $links = new JyrapheLinks();
        $links->prepareDB();
    }
]);
