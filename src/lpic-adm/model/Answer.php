<?php

class Answer implements Model
{
  private static $TABLE = "answer";
  private static $FIELDS = "a_id, a_q_id, a_is_right, a_txt";
  
  private $a_id       = 0;
  private $a_q_id     = 0;
  private $a_is_right = 0;
  private $a_txt      = '';
  
  private static  $table_size = 1;
  
  // Attributs
  public function a_id()
  {
    return $this->a_id;
  }
  
  public function a_q_id($value = null)
  {
    if ($value != null && is_numeric($value)) $this->a_q_id = $value;
    return $this->a_q_id;
  }
  
  public function a_is_right($value = null)
  {
    if ($value != null && is_numeric($value)) $this->a_is_right = $value;
    return $this->a_is_right;
  }
  
  public function a_txt($value = null)
  {
    if ($value != null) $this->a_txt = $value;
    return $this->a_txt;
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
    $answers = $db->query($query, "Answer");
    if (!is_array($questions)) $questions = array($questions);
    return $answers;
  }
  
  public static function findById($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE a_id=:a_id";
    $db = DbConnect::getInstance();
    $answer = $db->query($query, "Answer", array(':a_id' => $id));
    return $answer;
  }

  public function findByQuestionId($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE a_q_id=:q_id";
    $db = DbConnect::getInstance();
    $answers = $db->query($query, "Answer", array(':q_id' => $id));
    return $answers;
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
        if ($field != "a_id")
          $query .= " " . $field . " = :" . $field . ", ";
        //if ($field != "u_pwd")
        $params[':' . $field] = $this->$field;
      }
      $query = rtrim($query, ", ") . " WHERE a_id = :a_id";
    }
    
    $db = DbConnect::getInstance();
    $db->query($query, null, $params);
    return true;
  }
  
  public function delete()
  {
    $query = "DELETE FROM " . self::$TABLE . " WHERE a_id=:a_id";
    $params = array(':a_id' => $this->a_id);
    
    $db = DbConnect::getInstance();
    return $db->query($query, null, $params);
  }
}
