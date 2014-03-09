<?php

class User implements Model
{
  private static $TABLE = "user";
  private static $FIELDS = "u_id, u_name, u_pwd, u_is_manager, u_mail";
  
  private $u_id         = 0;
  private $u_name       = 'guest';
  private $u_pwd        = '';
  private $u_is_manager = 0;
  private $u_mail       = '';
  
  private static  $table_size = 1;
  
  // Attributs
  public function u_id()
  {
    return $this->u_id;
  }
  
  public function u_name($value = null)
  {
    if ($value != null) $this->u_name = $value;
    return $this->u_name;
  }
  
  public function u_pwd($value = null)
  {
    return $this->u_pwd;
  }

  public function u_is_manager()
  {
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
  
  public function save($force = true)
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
  public static function findByLoginPwd($uname, $upwd)
  {
    $adm = Conf::get('ADMIN');
    if ($uname == $adm['LOGIN'])
    {
      if ($adm['PSSWD'] != null)
      {
        if ($upwd == $adm['PSSWD'])
        {
          $user = new User();
          $user->u_name($uname);
          $user->u_pwd($upwd);
          return $user;
        }
        else
        {
          return null;
        }
      }
    }
    $query = "SELECT " . self::$FIELD . " FROM " . self::$TABLE . " WHERE u_name=:u_name";
    $params = array(':u_name' => $uname);
    $db = DbConnect::getInstance();
    $user = $db->query($query, 'User', $params);
    if (crypt($upwd, $user->u_pwd()) == $user->u_pwd)
      return $user;
    return null;
  }
}
