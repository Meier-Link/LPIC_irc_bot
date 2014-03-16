<?php

class User implements Model
{
  private static $TABLE = "user";
  private static $FIELDS = "u_id, u_pseudo, u_start, u_pwd, u_is_manager";
  
  private $u_id         = 0;
  private $u_pseudo     = 'guest';
  private $u_start      = '0000-00-00 00:00:00';
  private $u_pwd        = '';
  private $u_is_manager = 0;
  private $u_mail       = '';
  
  private static  $table_size = 1;
  
  // Attributs
  public function u_id()
  {
    return $this->u_id;
  }
  
  public function u_pseudo($value = null)
  {
    if ($value != null) $this->u_pseudo = $value;
    return $this->u_pseudo;
  }
  
  // XXX compatibility method
  public function u_name($value = null)
  {
    return $this->u_pseudo($value);
  }
  
  public function u_start()
  {
    return $this->u_start;
  }
  
  public function u_pwd($value = null)
  {
    if(!is_null($value)) $this->u_pwd = $value;
    return $this->u_pwd;
  }

  public function u_is_manager($is_manager = null)
  {
    if(!is_null($is_manager))
    {
      if($is_manager != 0) $is_manager = 1;
      $this->u_is_manager = $is_manager;
    }
    
    return $this->u_is_manager;
  }
  
  public function u_mail($value = null)
  {
    if ($value != null) $this->u_mail = $value;
    return $this->u_mail;
  }
  
  // @Implmented methods
  public static function table_size($force = false)
  {
    if (is_null(self::$table_size) || $force)
    {
      $query = "SELECT COUNT(*) AS table_size FROM " . self::$TABLE;
      $db = DbConnect::getInstance();
      self::$table_size = $db->query($query)['table_size'];
    }
    return self::$table_size;
  }
  
  public static function findAll($page_num = 1, $by_page = 0)
  {
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE;
    $db = DbConnect::getInstance();
    $users = $db->query($query, "User");
    if (!is_array($users)) $users = array($users);
    return $users;
  }
  
  public static function findById($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE u_id=:u_id";
    $db = DbConnect::getInstance();
    $user = $db->query($query, "User", array(':u_id' => $id));
    return $user;
  }
  
  public function save($force = false)
  {
    $params = array();
    if ($this->u_id == 0 || $force)
    {
      $query = "INSERT INTO " . self::$TABLE . " (" . self::$FIELDS . ") VALUES (";
      $fields = explode(', ', self::$FIELDS);
      foreach($fields as $field)
      {
        $query .= ":" . $field . ", ";
        $params[':' . $field] = $this->$field;
      }
      $query = rtrim($query, ", ") . ");";
    }
    else
    {
      $query = "UPDATE " . self::$TABLE . " SET ";
      $fields = explode(', ', self::$FIELDS);
      foreach($fields as $field)
      {
        if ($field != "u_id")
          $query .= " " . $field . " = :" . $field . ", ";
        //if ($field != "u_pwd")
        $params[':' . $field] = $this->$field;
      }
      $query = rtrim($query, ", ") . " WHERE u_id = :u_id";
    }
    var_dump($this->u_pwd);
    
    $db = DbConnect::getInstance();
    $db->query($query, null, $params);
    return true;
  }
  
  public function delete()
  {
    $query = "DELETE FROM " . self::$TABLE . " WHERE u_id=:u_id";
    $params = array(':u_id' => $this->u_id);
    
    $db = DbConnect::getInstance();
    return $db->query($query, null, $params);
  }
  
  //@Specific methods
  public static function findByLoginPwd($upseudo, $upwd)
  {
    $adm = Conf::get('ADMIN');
    if ($upseudo == $adm['LOGIN'])
    {
      if ($adm['PSSWD'] != null)
      {
        if ($upwd == $adm['PSSWD'])
        {
          $user = new User();
          $user->u_pseudo($upseudo);
          $user->u_pwd($upwd);
          return $user;
        }
        else
        {
          return null;
        }
      }
    }
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE u_pseudo=:u_pseudo";
    $params = array(':u_pseudo' => $upseudo);
    $db = DbConnect::getInstance();
    $user = $db->query($query, 'User', $params);
    if (crypt($upwd, $user->u_pwd()) == $user->u_pwd)
      return $user;
    return null;
  }
}
