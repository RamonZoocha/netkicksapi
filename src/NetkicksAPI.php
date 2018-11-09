<?php

use Medoo\Medoo;

require_once('config.php');

class NetkicksAPI {

  /**
   * Does the login, returns true if the user is found.
   *
   * @param $player
   * @param $password
   *
   * @return bool
   */
  public function login($player, $password) {

    global $db;

    // Try to login by email first, $user is email here.
    $result = $db->count('players', [
      'email' => $player,
      'password' => $password,
      ]);

    if($result == 0) {
      // If the player is not found by email, try to login by name.
      $result = $db->count('players', [
        'name' => $player,
        'password' => $password,
      ]);
    }

    return ($result > 0);

  }

  /**
   * Register a new player.
   *
   * @param $params
   *
   * @return array|mixed
   */
  public function registerPlayer($params) {

    global $db;

    $db->insert('players', [
      'name' => $params['name'],
      'password' => $params['password'],
      'email' => $params['email'],
    ]);

    return $db->id() > 0;

  }

  /**
   * Returns a player object based on its global ID, name or email.
   *
   * @param $email
   *
   * @return array|mixed
   */
  public function getPlayer($player, $fields) {

    global $db;

    $player = $db->get('players', $fields, [
      'OR' => [
        'global_id' => $player,
        'name' => $player,
        'email' => $player,
      ]
    ]);

    if($player != NULL) {
      $player['team'] = $this->getTeamIdFromPlayerId($player['global_id']);
      unset($player['password']);
    }

    return $player;

  }

  /**
   * Returns the ID of the team a player belongs to.
   *
   * @param $player_global_id
   *
   * @return array|mixed
   */
  public function getTeamIdFromPlayerId($player_global_id) {

    global $db;

    $team_id = $db->get('squads', 'team', ['player' => $player_global_id]);

    return $team_id;

  }

  /**
   * Returns a team object based on its name or ID.
   *
   * @param $team
   *
   * @return array|mixed
   */
  public function getTeam($team, $fields){

    global $db;

    $team = $db->get('teams', $fields, [
      'OR' => [
        'id' => $team,
        'name' => $team,
      ]
    ]);

    if($team != NULL)
      $team['founder'] = $this->getPlayer($team['founder'], ['name'])['name'];

    return $team;
  }

  /**
   * Updates the player's last login timestamp.
   *
   * @param $id
   */
  public function updatePlayerLastLogin($id) {

    global $db;

    $db->update('players', ['last_login' => date('Y-m-d G:i:s')], ['global_id' => $id]);

  }
}
