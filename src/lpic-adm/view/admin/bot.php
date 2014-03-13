<h2>Manage bot status</h2>
<div id="bot-management">
  <button id="restart">Electroshock</button>
</div>
<script type="text/javascript">
  function bot_callbacks()
  {
    bot_button = this;
    console.log(this.id);
    get_server_data('/admin/bot', function(res)
    {
      console.log(res);
    }, [{k: 'bot_action', v: bot_button.id}]);
    this.removeEventListener('click', bot_callbacks, false);
    setTimeout("bot_button.addEventListener('click', bot_callbacks)", 1000);
  }
  last_call = "";
  var buttons = document.querySelectorAll('#bot-management button');
  for(i in buttons)
  {
    buttons.item(i).addEventListener('click', bot_callbacks);
  }
</script>
