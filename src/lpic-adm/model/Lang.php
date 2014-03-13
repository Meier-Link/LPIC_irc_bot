
<?php

class Lang implements Model
{
  private static $TABLE = "lang";
  private static $FIELDS = "la_id, la_short";
  
  private $la_id   = 0;
  private $la_short  = '';

  // Attributs
  public function la_id()
  {
    return $this->la_id;
  }
  
  public function la_short()
  {
    return $this->la_short;
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
    $questions = $db->query($query, "Lang", null, true);
    if (!is_array($questions)) $questions = array($questions);
    return $questions;
  }

  public static function findById($id)
  {
    if ($id < 1)
      return null;
    
    $query = "SELECT " . self::$FIELDS . " FROM " . self::$TABLE . " WHERE la_id=:la_id";
    $db = DbConnect::getInstance();
    $question = $db->query($query, "Lang", array(':la_id' => $id));
    return $question;
  }
  
  public function save($force = true){}

  public function delete(){}
}
