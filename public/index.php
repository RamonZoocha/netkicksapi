<?php

require_once ('../vendor/autoload.php');
require_once  ('../src/NetkicksAPI.php');

$klein = new \Klein\Klein();
$api = new NetkicksAPI();

$klein->respond('GET', '/players/login', function ($request, $response) {

  global $api;
  $params = $request->paramsGet()->all();

  if (!isset($params['player']) || !isset($params['password'])) {
    $response->code(500);
    return;
  }

  $success = $api->login($params['player'], $params['password']);

  if ($success){

    $player = $api->getPlayer($params['player'], '*');
    $response->json($player, NULL);

    if ($player != NULL)
      $api->updatePlayerLastLogin($player['global_id']);

  }

  else
    $response->code(500);

});

$klein->respond('GET', '/players/register', function ($request, $response) {

  global $api;

  $params = $request->paramsGet()->all();

  $success = $api->registerPlayer($params);

  if($success)
    $response->code(200);
  else
    $response->code(500);

  $response->lock();

});

$klein->respond('GET', '/players/[:player]', function ($request, $response) {
  global $api;

  $player = $api->getPlayer($request->player, '*');

  $response->json($player, NULL);

});

$klein->respond('GET', '/teams/[:team]', function ($request, $response) {

  global $api;

  $team = $api->getTeam($request->team, '*');

  $response->json($team, NULL);

});

$klein->respond('GET', '/test', function ($request, $response) {

  global $api;

  $team = $api->getTeam($request->team);

  $response->json($team, NULL);

});

$klein->dispatch();