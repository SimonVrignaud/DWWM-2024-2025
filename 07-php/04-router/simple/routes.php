<?php

require_once __DIR__.'/router.php';

get('/04-router/simple/$id', "pages/test.php");

any("/404", "pages/404.php");