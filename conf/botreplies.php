<?php if (!defined('APPLICATION')) exit();

/**
 * Set Plugins.ShwaipBot.ReplyOrder to an array of the function names, in order of precedence.
 *    Default: $Configuration['Plugins']['ShwaipBot']['ReplyOrder'] = array('BotShave', 'BotMuffinMan', 'BotSendBeer');
 *    If someone says something that triggers multiple replies, only the first will fire.
 */

/**
 * Simple call and response.
 */
function SayHello($Bot) {
   if ($Bot->SimpleMatch('say hello @vorgo'))
      return $Bot->Mention().' Hello Vorgo!';
}

/**
 * Let users send each other beers thru the bot. But sometimes he's buggy.
 *
 * User: !beer @Lincoln
 * Bot:  /me slides @Lincoln a beer.
 */
function SendBeer($Bot) {
   if ($Bot->PatternMatch('(^|[\s,\.>])\!beer\s@(\w{1,50})\b', $BeerWho)) {
      $Mistakes = array('Diet Coke', 'warm milk', 'prune juice', 'V8', 'frying pan');
      $Reply = '/me slides @'.GetValue(2, $BeerWho).' a ';
      $Reply .= (rand(0,4)) ? 'beer' : $Mistakes[rand(0,4)]; // 20% of the time it's something weird
      return $Reply;
   }
}