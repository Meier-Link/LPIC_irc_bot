<?php

class Question implements Model
{
  private static $TABLE = "question";
  private static $FIELDS = "q_id, q_txt, q_lvl, q_lang";
  
  private $q_id   = 0;
  private $q_txt  = '';
  private $q_lvl  = 0;
  private $q_lang = 0;
  
  private static  $table_size = 1;
  
  // Attributs
  public function q_id()
  {
    return $this->q_id;
  }
  
  public function q_txt($value = null)
  {
    if ($value != null) $this->q_txt = $value;
    return $this->q_txt;
  }
  
  public function q_lvl($value = null)
  {
    if ($value != null && is_numeric($value)) $this->q_lvl = $value;
    return $this->q_lvl;
  }
  
  public function q_lang($value = null)
  {
    if ($value != null && is_numeric($value)) $this->q_lang = $value;
    return $this->q_lang;
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
    $questions = $db->query($query, "Question");
    if (!is_array($questions)) $questions = array($questions);
    return $questions;
  }
  
  public static function findById($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE q_id=:q_id";
    $db = DbConnect::getInstance();
    $question = $db->query($query, "Question", array(':q_id' => $id));
    return $question;
  }
 
  public function save($force = false)
  {
    $params = array();
    if ($this->q_id == 0 || $force)
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
        if ($field != "q_id")
          $query .= " " . $field . " = :" . $field . ", ";
        //if ($field != "u_pwd")
        $params[':' . $field] = $this->$field;
      }
      $query = rtrim($query, ", ") . " WHERE q_id = :q_id";
    }
    
    $db = DbConnect::getInstance();
    $db->query($query, null, $params);
    return true;
  }
  
  public function delete()
  {
    $query = "DELETE FROM " . self::$TABLE . " WHERE q_id=:q_id";
    $params = array(':q_id' => $this->q_id);
    
    $db = DbConnect::getInstance();
    return $db->query($query, null, $params);
  }

  public static function findLast()
  {
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " ORDER BY q_id DESC LIMIT 1;";
    
    $db = DbConnect::getInstance();
    return $db->query($query, "Question");
  }
}
