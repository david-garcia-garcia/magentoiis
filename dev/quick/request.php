<?php

include_once('_secure.inc');

header("Content-Type: text/html; charset=utf-8");

echo json_encode($_SERVER);