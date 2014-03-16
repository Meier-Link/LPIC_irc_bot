<?php
/*
 * Level.php
 */

class Level implements Model
{
  private static $TABLE = "level";
  private static $FIELDS = "le_id, le_name";
  
  private $le_id   = 0;
  private $le_name = '';
  
  // Attributs
  public function le_id()
  {
    return $this->le_id;
  }
  
  public function le_name()
  {
    return $this->le_name;
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
    $questions = $db->query($query, "Level");
    if (!is_array($questions)) $questions = array($questions);
    return $questions;
  }
  
  public static function findById($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE le_id=:le_id";
    $db = DbConnect::getInstance();
    $question = $db->query($query, "Level", array(':le_id' => $id));
    return $question;
  }
 
  public function save($force = true){}
  
  public function delete(){}
}

