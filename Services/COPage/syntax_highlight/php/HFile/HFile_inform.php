<?php
$BEAUT_PATH = realpath(".")."/Services/COPage/syntax_highlight/php";
if (!isset ($BEAUT_PATH)) return;
require_once("$BEAUT_PATH/Beautifier/HFile.php");
  class HFile_inform extends HFile{
   function HFile_inform(){
     $this->HFile();	
/*************************************/
// Beautifier Highlighting Configuration File 
// 
/*************************************/
// Flags

$this->nocase            	= "0";
$this->notrim            	= "0";
$this->perl              	= "0";

// Colours

$this->colours        	= array("blue", "purple", "gray", "brown", "blue");
$this->quotecolour       	= "blue";
$this->blockcommentcolour	= "green";
$this->linecommentcolour 	= "green";

// Indent Strings

$this->indent            	= array();
$this->unindent          	= array();

// String characters and delimiters

$this->stringchars       	= array();
$this->delimiters        	= array("~", "!", "@", "%", "^", "&", "*", "(", ")", "-", "+", "=", "|", "\\", "/", "{", "}", "[", "]", ":", ";", "\"", "'", "<", ">", " ", ",", "	", ".", "?");
$this->escchar           	= "";

// Comment settings

$this->linecommenton     	= array("");
$this->blockcommenton    	= array("");
$this->blockcommentoff   	= array("");

// Keywords (keyword mapping to colour number)

$this->keywords          	= array(
			"/L10" => "", 
			"\"Inform\"" => "", 
			"Line" => "", 
			"Comment" => "", 
			"=" => "", 
			"!" => "", 
			"String" => "", 
			"Chars" => "", 
			"\"'" => "", 
			"File" => "", 
			"Extensions" => "", 
			"H" => "", 
			"INF" => "", 
			"box" => "1", 
			"break" => "1", 
			"bold" => "1", 
			"continue" => "1", 
			"do" => "1", 
			"else" => "1", 
			"font" => "1", 
			"for" => "1", 
			"fixed" => "1", 
			"give" => "1", 
			"if" => "1", 
			"inversion" => "1", 
			"jump" => "1", 
			"move" => "1", 
			"new_line" => "1", 
			"objectloop" => "1", 
			"print" => "1", 
			"print_ret" => "1", 
			"quit" => "1", 
			"read" => "1", 
			"remove" => "1", 
			"restore" => "1", 
			"return" => "1", 
			"rfalse" => "1", 
			"rtrue" => "1", 
			"roman" => "1", 
			"reverse" => "1", 
			"save" => "1", 
			"spaces" => "1", 
			"string" => "1", 
			"style" => "1", 
			"switch" => "1", 
			"until" => "1", 
			"underline" => "1", 
			"while" => "1", 
			"Abbreviate" => "2", 
			"Array" => "2", 
			"Attribute" => "2", 
			"Class" => "2", 
			"Constant" => "2", 
			"Default" => "2", 
			"Dictionary" => "2", 
			"End" => "2", 
			"Endif" => "2", 
			"Extend" => "2", 
			"Fake_action" => "2", 
			"Global" => "2", 
			"Ifdef" => "2", 
			"Ifndef" => "2", 
			"Ifnot" => "2", 
			"Iftrue" => "2", 
			"Iffalse" => "2", 
			"Import" => "2", 
			"Include" => "2", 
			"Ifv3" => "2", 
			"Ifv5" => "2", 
			"Link" => "2", 
			"Lowstring" => "2", 
			"Message" => "2", 
			"Nearby" => "2", 
			"Object" => "2", 
			"Property" => "2", 
			"Release" => "2", 
			"Replace" => "2", 
			"Serial" => "2", 
			"Switches" => "2", 
			"Statusline" => "2", 
			"System_file" => "2", 
			"Stub" => "2", 
			"score" => "2", 
			"time" => "2", 
			"Trace" => "2", 
			"Verb" => "2", 
			"Version" => "2", 
			"creature" => "3", 
			"first" => "3", 
			"held" => "3", 
			"last" => "3", 
			"meta" => "3", 
			"multi" => "3", 
			"multiheld" => "3", 
			"multiexcept" => "3", 
			"multiinside" => "3", 
			"noun" => "3", 
			"number" => "3", 
			"only" => "3", 
			"replace" => "3", 
			"scope" => "3", 
			"special" => "3", 
			"topic" => "3", 
			"absent" => "4", 
			"animate" => "4", 
			"add_to_scope" => "4", 
			"after" => "4", 
			"article" => "4", 
			"articles" => "4", 
			"before" => "4", 
			"clothing" => "4", 
			"conealed" => "4", 
			"container" => "4", 
			"cant_go" => "4", 
			"capacity" => "4", 
			"door" => "4", 
			"daemon" => "4", 
			"describe" => "4", 
			"description" => "4", 
			"door_dir" => "4", 
			"door_to" => "4", 
			"d_to" => "4", 
			"edible" => "4", 
			"enterable" => "4", 
			"each_turn" => "4", 
			"e_to" => "4", 
			"female" => "4", 
			"found_in" => "4", 
			"initial" => "4", 
			"inside_description" => "4", 
			"invent" => "4", 
			"in_to" => "4", 
			"general" => "4", 
			"grammar" => "4", 
			"light" => "4", 
			"lockable" => "4", 
			"locked" => "4", 
			"life" => "4", 
			"list_together" => "4", 
			"male" => "4", 
			"moved" => "4", 
			"neuter" => "4", 
			"name" => "4", 
			"n_to" => "4", 
			"nw_to" => "4", 
			"ne_to" => "4", 
			"on" => "4", 
			"open" => "4", 
			"openable" => "4", 
			"orders" => "4", 
			"out_to" => "4", 
			"pluralname" => "4", 
			"proper" => "4", 
			"parse_name" => "4", 
			"plural" => "4", 
			"react_after" => "4", 
			"react_before" => "4", 
			"scenery" => "4", 
			"scored" => "4", 
			"static" => "4", 
			"supporter" => "4", 
			"switchable" => "4", 
			"short_name" => "4", 
			"short_name_indef" => "4", 
			"s_to" => "4", 
			"se_to" => "4", 
			"sw_to" => "4", 
			"talkable" => "4", 
			"transparent" => "4", 
			"time_left" => "4", 
			"time_out" => "4", 
			"visited" => "4", 
			"u_to" => "4", 
			"workflag" => "4", 
			"worn" => "4", 
			"when_closed" => "4", 
			"when_open" => "4", 
			"when_off" => "4", 
			"when_on" => "4", 
			"with_key" => "4", 
			"w_to" => "4", 
			"ActionsOn" => "5", 
			"ActionsOff" => "5", 
			"Attack" => "5", 
			"Answer" => "5", 
			"Ask" => "5", 
			"AskFor" => "5", 
			"Burn" => "5", 
			"Blow" => "5", 
			"Buy" => "5", 
			"CommandsOn" => "5", 
			"CommandsOff" => "5", 
			"CommandsRead" => "5", 
			"Close" => "5", 
			"Consult" => "5", 
			"Cut" => "5", 
			"Climb" => "5", 
			"Drop" => "5", 
			"Disrobe" => "5", 
			"Dig" => "5", 
			"Drink" => "5", 
			"Empty" => "5", 
			"Enter" => "5", 
			"Exit" => "5", 
			"Examine" => "5", 
			"Eat" => "5", 
			"Fullscore" => "5", 
			"Fill" => "5", 
			"Goto" => "5", 
			"Gonear" => "5", 
			"GetOff" => "5", 
			"Go" => "5", 
			"GoIn" => "5", 
			"Give" => "5", 
			"Inv" => "5", 
			"InvTall" => "5", 
			"InvWide" => "5", 
			"Insert" => "5", 
			"Jump" => "5", 
			"JumpOver" => "5", 
			"Kiss" => "5", 
			"LMode1" => "5", 
			"LMode2" => "5", 
			"LMode3" => "5", 
			"Look" => "5", 
			"Lock" => "5", 
			"Listen" => "5", 
			"LookUnder" => "5", 
			"LetGo" => "5", 
			"Mild" => "5", 
			"Miscellany" => "5", 
			"NotifyOn" => "5", 
			"NotifyOff" => "5", 
			"No" => "5", 
			"Objects" => "5", 
			"Open" => "5", 
			"Order" => "5", 
			"Pronouns" => "5", 
			"Places" => "5", 
			"Predictable" => "5", 
			"PutOn" => "5", 
			"Pray" => "5", 
			"Pull" => "5", 
			"Push" => "5", 
			"PushDir" => "5", 
			"PluralFound" => "5", 
			"Prompt" => "5", 
			"Quit" => "5", 
			"Restart" => "5", 
			"Restore" => "5", 
			"RoutinesOn" => "5", 
			"RoutinesOff" => "5", 
			"Remove" => "5", 
			"Rub" => "5", 
			"Receive" => "5", 
			"Save" => "5", 
			"SciptOn" => "5", 
			"ScriptOff" => "5", 
			"Score" => "5", 
			"Scope" => "5", 
			"Search" => "5", 
			"Show" => "5", 
			"SwitchOn" => "5", 
			"SwitchOff" => "5", 
			"Smell" => "5", 
			"Sorry" => "5", 
			"Strong" => "5", 
			"Swim" => "5", 
			"Swing" => "5", 
			"Set" => "5", 
			"SetTo" => "5", 
			"Squeeze" => "5", 
			"Sing" => "5", 
			"Sleep" => "5", 
			"TraceOn" => "5", 
			"TraceOff" => "5", 
			"TraceLevel" => "5", 
			"TimersOn" => "5", 
			"TimersOff" => "5", 
			"Transfer" => "5", 
			"Think" => "5", 
			"Taste" => "5", 
			"Touch" => "5", 
			"Tie" => "5", 
			"Turn" => "5", 
			"ThrowAt" => "5", 
			"ThrownAt" => "5", 
			"TheSame" => "5", 
			"Unlock" => "5", 
			"Verify" => "5", 
			"Xpurloin" => "5", 
			"Xabstract" => "5", 
			"Xtree" => "5", 
			"Yes" => "5", 
			"Wear" => "5", 
			"Wake" => "5", 
			"WakeOther" => "5", 
			"Wave" => "5", 
			"WaveHands" => "5", 
			"Wait" => "5");

// Special extensions

// Each category can specify a PHP function that returns an altered
// version of the keyword.
        
        

$this->linkscripts    	= array(
			"" => "donothing", 
			"1" => "donothing", 
			"2" => "donothing", 
			"3" => "donothing", 
			"4" => "donothing", 
			"5" => "donothing");
}


function donothing($keywordin)
{
	return $keywordin;
}

}?>
