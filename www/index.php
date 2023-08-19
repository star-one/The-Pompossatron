<?php
$ServerPath = $_SERVER['DOCUMENT_ROOT'];
$ServerPath .= "/index-php.php";
include_once($ServerPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>The Pompossatron</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="shortcut icon" href="https://www.perfect-curve.co.uk/shared/french_curve.ico">
  <style>
 mark {
  color: #000;
  background-color: yellow;
  padding: 0 0.5em;
}
.ttsSpeech {
  white-space: pre-wrap;
	/* font: 1em Bitter, Helvetica, Arial, sans-serif; */
	line-height: 1.5em;
}
#speechPlayer {  
  background-color: #eee;
  border: 1px solid #222;
  padding: 0.25em;
}

    .ql-editor p { margin-bottom: 1em; font-size: 1.2em; line-height: 1.5em;}
    h2 { size: 0.8em; }
  </style>
<script>
function strip(html)
{
   html = html.replaceAll(/<p>/gi, "\r\n");
   let doc = new DOMParser().parseFromString(html, 'text/html');
   return doc.body.textContent || "";
}
</script>
</head>
<body>

<div class="container">
  <h1>The Pompossatron</h1>
  <p>How pompous is your text?</p>
  <p>Use this tool to check it against the Plain English Campaign's <a href="https://www.plainenglish.co.uk/the-a-z-of-alternative-words.html" title="The A - Z of alternative words">list of pompous words and phrases and their alternatives</a>.</p>
  <p>
    For the time being this is just a proof of concept and works as a bit of a blunt instrument. I'll refine it if people like it.
  </p>
<div class="row">
  <div class="col-lg-2"> 
  </div>
  <div class="col-lg-8">
      <div id="speechPlayer">Read this text aloud<br /><button id="play">▶️</button><button id="pause" disabled>⏸️</button><button id="stop" disabled>⏹️</button></div>
  <div id="form-container" class="container">
  <form action="." id="theText" method="post">
 <div id="editor-container">
<?=$flaggedOriginalText;?>
</div>
<textarea style="display: none;" id="originalText" name="originalText"><?=$flaggedOriginalText;?></textarea>
    <br />
    <button type="submit"  type="button" class="btn btn-primary">Analyse</button>
  </form>
  </div>
    <div id="speechText" style="display: none;">
<?php
      $ttsText = str_replace("&rsquo;", "\'", strip_tags($flaggedOriginalText));
      $ttsText = str_replace("&#39;", "\'", $ttsText);
      $ttsText = str_replace("&nbsp;", " ", $ttsText);
      $ttsText = str_replace("&mdash;", " - ", $ttsText);
      $ttsText = str_replace("&ndash;", " - ", $ttsText);
?>
      <div id="text"><?=$flaggedOriginalText;?></div>
      <script>
        const text = '<?=$ttsText;?>';
        const originalText = '<?=$flaggedOriginalText;?>';
      </script>
      <script src="/tts.js"></script>
    </div>
  </div>
  <div class="col-lg-2"> 
  </div>
</div>
</div>
  
<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://unpkg.com/quill-html-edit-button@2.2.7/dist/quill.htmlEditButton.min.js"></script>
<!-- Initialize Quill editor -->
<script>
var BackgroundClass = Quill.import('attributors/class/background');
var ColorClass = Quill.import('attributors/class/color');
var SizeStyle = Quill.import('attributors/style/size');
Quill.register(BackgroundClass, true);
Quill.register(ColorClass, true);
Quill.register(SizeStyle, true);
Quill.register("modules/htmlEditButton", htmlEditButton);
  
  var quill = new Quill('#editor-container', {
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      ['bold', 'italic', 'blockquote', 'link'],
      [{ list: 'bullet' }, { list: 'ordered' }],
      [{ 'indent': '-1'}, { 'indent': '+1' }],
    ],
    htmlEditButton: {
      okText: "Save", 
      msg: "Edit the HTML",
      buttonHTML: "&lt;html&gt;",
    }
  },
  placeholder: 'Paste your pompous text here',
  theme: 'snow'
});

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById("originalText").innerHTML = quill.container.firstChild.innerHTML;
            document.getElementById("text").innerHTML = strip(quill.container.firstChild.innerHTML);
        });
</script>  
</body>
</html>
