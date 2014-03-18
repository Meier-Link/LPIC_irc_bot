<?php
/*
 * Manage.php
 */

class Manage extends Controller
{
  public function hook()
  {
    // TODO check if there is an authorized user
    if(isset($_SESSION['user']) && !is_null($_SESSION['user']))
    {
      if(!$this->isAdmin() && $_SESSION['user']->u_is_manager() != 1)
        $this->forbidden();
    }
    else
    {
      $this->forbidden();
    }
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
  
  public function delete()
  {
    $this->template('json');
    if($_POST['q_id'])
    {
      $q = Question::findById($_POST['q_id']);
      if(!is_null($q))
      {
        foreach(Answer::findByQuestionId($q->q_id()) as $a)
        {
          $a->delete();
        }
        $q->delete();
      }
      Log::inf('question n°' . $_POST['q_id'] . ' deleted');
    }
  }

  public function edit()
  {
    if(!isset($this->params[3]) || $this->params[3] == "")
    {
      Log::err('No question to edit !');
      $this->notfound();
      return;
    }
    
    $qfa = null;
    // Firstly, set question
    if($this->params[3] != "0")
    {
      $qu = Question::findById($this->params[3]);
      if(isset($_POST['q']))
      {
        $e = $_POST['q'];
        $qu->q_txt($e['txt']);
        $qu->q_lvl(intval($e['lvl']));
        $qu->q_lang(intval($e['lang']));
        $qu->save();
      }
      $qfa = $qu;
    }
    else
    {
      $qu = new Question();
      $an = array();
      if(isset($_POST['q']))
      {
        $e = $_POST['q'];
        $qu->q_txt($e['txt']);
        $qu->q_lvl(intval($e['lvl']));
        $qu->q_lang(intval($e['lang']));
        $qu->save();
      }
      $qfa = Question::findLast();
    }
    
    // And then, manage answers
    if(!is_null($qfa))
    {
      $an = Answer::findByQuestionId($qfa->q_id());
      if(isset($_POST['a'])) 
      {
        $a = $_POST['a'];
        foreach($a as $k => $a)
        { 
          // TODO how to do that ? ... :-°
          //$an = new Answer();
          //$an->a_q_id($qfa->q_id());
          //if ($a['is_right'] == "yes") $an->a_is_right(1);
          //$an->a_txt($a['txt']);
          //$an->save();
          if($k == 'new') // Manage new answers
          {
            Log::inf('New answer');
          }
          else // Manage old answers
          {
            $an = Answer::findById($k);
            if(!is_null($an))
            {
              //$an->
              Log::inf('Answer ' . $an->a_id() . ' ');
            }
            else
            {
              Log::err("Question not found !");
            }
          }
        }
      }
    }
    else
    {
      Log::err('Unable to find this question');
    }
    $this->data['q'] = $qfa;
    $this->data['lvl'] = Level::findAll();
    $this->data['lang'] = Lang::findAll();
    $this->data['a'] = $an;
  }
}
