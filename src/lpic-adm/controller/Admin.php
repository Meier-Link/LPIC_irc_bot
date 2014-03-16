<?php

class Admin extends Controller
{
  private $_bot_path = "/home/meier/dev/python/ircbot/lpic_bot/test/";
  
  public function hook()
  {
    // Check if current user can access to administration section
    if (!$this->isAdmin())
      $this->action = 'forbidden';
      //$this->forbidden();
  }
  
  public function home()
  {
    $this->title = "Query page";
    if (isset($_POST['query']))
    {
      $rslt = Query::send($_POST['query']);
      $this->data['res'] = $rslt;
    }
  }

  public function bot()
  {
    $this->title = "Bot management";
    if (isset($_POST['bot_action']))
    {
      //Log::inf($_POST['bot_action']);
      if ($_POST['bot_action'] == 'restart')
      {
        $cmd = "nohup " . $this->_bot_path . "LPIC_Bot.py &";
        $ll = system($cmd, $rslt);
        Log::inf("L'exécution de la commande à renvoyé le code " . $rslt . ". And last line contain :<br/>" . $ll);
      }
      $this->template('json');
    }
  }
  
  public function users()
  {
    if(isset($_POST['user']))
    {
      $pu = $_POST['user'];
      $uid = $this->params['3'];
      if($uid == "0")
        $u = new User();
      else
        $u = User::findById($uid);
      if(!is_null($u))
      {
        /*if($u->u_id() == $_SESSIO['user']->u_id()
        {
          Log::err('Changing your own informations is forbidden !');
        }*/
        if($pu['pwd'] != "") $u->u_pwd(crypt($pu['pwd']));
        if($pu['is_manager'] == "1") $u->u_is_manager(1);
        $u->save();
        Log::inf('User edited !');
      }
      else
      {
        Log::err('User not found !!!');
      }
    }
    $this->data['users'] = User::findAll();
  }
}

