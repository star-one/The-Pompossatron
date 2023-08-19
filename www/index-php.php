<?php
$ServerPath = $_SERVER['DOCUMENT_ROOT'];
$ServerPath .= "/wordlist.php";
include_once($ServerPath);

$originalText = $_REQUEST['originalText'];

$numWords = str_word_count($originalText);
$theWords = str_word_count($originalText, 1);

$numWordlist = count($wordlist);

$flaggedOriginalText = str_replace($wordlist[0], "<span class=\"ql-bg-yellow\">" . $wordlist[0] . "</span>", $originalText);
for($pompous=1; $pompous<$numWordlist; $pompous++)
{
  $flaggedOriginalText = str_replace($wordlist[$pompous], "<span class=\"ql-bg-yellow\">" . $wordlist[$pompous] . "</span>", $flaggedOriginalText);
}
?>
