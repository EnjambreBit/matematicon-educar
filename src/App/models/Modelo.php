<?php
namespace Social\models;

use Edufw\datasources\redis\RedisList;
/**
 * Description of Actions
 *
 * @author lmoya
 */
class Actions extends RedisList
{
  const KEY = 'actions';
  const ACTION_PUBLICACION = 1;
  const ACTION_COMENTARIO = 2;
  
  public function __construct()
  {
    parent::__construct(null, self::KEY);
  }
  
  public function insertarPublicacion($pub_id, $usr_sid)
  {
    $action = array();
    $action['act_tipo'] = self::ACTION_PUBLICACION;
    $action['act_time'] = time();
    $action['act_data']['pub_id'] = $pub_id;
    $action['act_data']['usr_sid'] = $usr_sid;
    return $this->leftPush(json_encode($action));
  }
}
