<?php
/*
 * Manage.php
 */

class Manage extends Controller
{
  public function hook()
  {
    // TODO check if there is an authorized user
  }

  public function home()
  {
    $questions = Question::findAll();
    $qna = array();
    foreach($questions as $qu)
    {
      $qna[$qu->q_id()] = array(
        'q'   => $qu,
        'a'   => Answer::findByQuestionId($qu->q_id()),
        'lvl' => Level::findById($qu->q_lvl()),
        'lan' => Lang::findById($qu->q_lang())
      );
    }
    $this->data['questions'] = $qna;
  }

  public function edit()
  {
    if ($this->params[3] != "0") 
      $question = Question::findById($this->params[3]);
    else
      $question = new Question();
    
    if(!is_null($question))
    {
      if(isset($_POST['qu'])) 
      {
        $e = $_POST['qu'];
        // Firstly, set question
        $question->q_txt($e['txt']);
        $question->q_lvl(intval($e['lvl']));
        $question->q_lang(intval($e['lang']));
        $question->save();
        $qfa = Question::findLast();
        foreach($e['a'] as $a)
        { // TODO how to do that ? ... :-°
          $an = new Answer();
          $an->a_q_id($qfa->q_id());
          if ($a['is_right'] == "yes") $an->a_is_right(1);
          $an->a_txt($a['txt']);
          $an->save();
        }
      }
    }
    else
    {
      Log::err('Unable to find this question');
    }
  }
}
