// Modified from original TTS script from https://codersblock.com/blog/javascript-text-to-speech-and-its-many-quirks/

// grab the UI elements to work with
const textEl = document.getElementById('text');
const playEl = document.getElementById('play');
const pauseEl = document.getElementById('pause');
const stopEl = document.getElementById('stop');

// add UI event handlers
playEl.addEventListener('click', play);
pauseEl.addEventListener('click', pause);
stopEl.addEventListener('click', stop);

// set text
// textEl.innerHTML = text;

function play() {
  document.getElementById("speechText").style.display = "block";
  document.getElementById("form-container").style.display = "none";
  if (window.speechSynthesis.speaking) {
    // there's an unfinished utterance
    window.speechSynthesis.resume();
  } else {
    // start new utterance
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.addEventListener('start', handleStart);
    utterance.addEventListener('pause', handlePause);
    utterance.addEventListener('resume', handleResume);
    utterance.addEventListener('end', handleEnd);
    utterance.addEventListener('boundary', handleBoundary);
    window.speechSynthesis.speak(utterance);
  }
}

function pause() {
  window.speechSynthesis.pause();
}

function stop() {
  document.getElementById("speechText").style.display = "none";
  document.getElementById("form-container").style.display = "block";
  window.speechSynthesis.cancel();
  
  // Safari doesn't fire the 'end' event when cancelling, so call handler manually
  handleEnd();
}

function handleStart() {
  playEl.disabled = true;
  pauseEl.disabled = false;
  stopEl.disabled = false;
}

function handlePause() {
  playEl.disabled = false;
  pauseEl.disabled = true;
  stopEl.disabled = false;
  textEl.innerHTML = originalText;
}

function handleResume() {
  playEl.disabled = true;
  pauseEl.disabled = false;
  stopEl.disabled = false;
}

function handleEnd() {
  playEl.disabled = false;
  pauseEl.disabled = true;
  stopEl.disabled = true;
  
  // reset text to remove mark
  textEl.innerHTML = originalText;
}

function handleBoundary(event) {
  if (event.name === 'sentence') {
    // we only care about word boundaries
    return;
  }

  const wordStart = event.charIndex;

  let wordLength = event.charLength;
  if (wordLength === undefined) {
    // Safari doesn't provide charLength, so fall back to a regex to find the current word and its length (probably misses some edge cases, but good enough for this demo)
    const match = text.substring(wordStart).match(/^[a-z\d']*/i);
    wordLength = match[0].length;
  }
  
  // wrap word in <mark> tag
  const wordEnd = wordStart + wordLength;
  const word = text.substring(wordStart, wordEnd);
  const markedText = text.substring(0, wordStart) + '<mark>' + word + '</mark>' + text.substring(wordEnd);
  textEl.innerHTML = "<pre class=\"ttsSpeech\">" + markedText + "</pre>";
}
