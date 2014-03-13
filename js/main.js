/* Copyright 2013 Chris Wilson

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

var audioContext = new AudioContext();
var audioInput = null,
    realAudioInput = null,
    inputPoint = null,
    audioRecorder = null;
var rafID = null;
var analyserContext = null;
var canvasWidth, canvasHeight;
var recIndex = 0;

/* Partie chargement image pour enregistrement*/
var divChargement=document.getElementById('chargement');
var img = document.createElement('img');
img.setAttribute('src', location.href.substr( 0, location.href.lastIndexOf('/') ) +'/css/bootstrap/images/loader.gif');
img.setAttribute('alt', 'chargement');
img.setAttribute('height', '24px');
img.setAttribute('width', '24px');
divChargement.appendChild( img );
divChargement.style.visibility = "hidden";
/*  Fin partie chargement */
/* Message de transfert*/
var transfert =document.getElementById("transfert");
transfert.style.visibility = "hidden";
/*  Fin transfert */
/* Partie chargement image pour enregistrement*/
var chargement2 =document.getElementById("chargement2");
var img = document.createElement('img');
img.setAttribute('src', location.href.substr( 0, location.href.lastIndexOf('/') ) +'/css/bootstrap/images/loader.gif');
img.setAttribute('alt', 'chargement');
img.setAttribute('height', '24px');
img.setAttribute('width', '24px');
chargement2.appendChild( img );
chargement2.style.visibility = "hidden";
/*  Fin partie chargement */
/* Partie pour declarer la balise avec js */
var recordAudio =document.getElementById("recordAudio");
var audio = document.createElement('audio');
audio.setAttribute('id', 'preview');
audio.setAttribute('controls', '');
recordAudio.appendChild( audio );
var EltAudio =document.getElementById("preview");
/* fin balise audio */
/* Compteur */
 var affichageCompteur = document.getElementById("compt");
var compte = 1;
var timer = 0;
function compter()
{
        if(compte <= 1) {
        pluriel = "";
        } else {
        pluriel = "s";
        }
 
    document.getElementById("compt").innerHTML = compte + " seconde" + pluriel;
 
        if(compte > 100000) {
 
        clearInterval(timer);
        }
 
    compte++;
}
/* Fin compteur */
var fileName;
function saveAudio() {
    // audioRecorder.exportMP3( doneEncoding );
    audioRecorder.exportWAV( doneEncoding );
    affichageCompteur.style.visibility = "hidden";
    chargement2.style.visibility = "hidden";
    // could get mono instead by saying
    // audioRecorder.exportMonoWAV( doneEncoding );
}
function listen(stream) {
        chargement2.style.visibility = "hidden";
        EltAudio.src = window.URL.createObjectURL(stream);
        console.log("preview.src : "+EltAudio.src);
        console.log(EltAudio);
        EltAudio.play();
}

function PostBlob(blob, fileName) {
    // FormData
    var formData = new FormData();
    formData.append('audio-filename', fileName);
    formData.append('audio-blob', blob);
    console.log("form data : "+formData);
    // POST the Blob
    xhr('save.php', formData, function (fileURL) {
        console.log("fileURL : "+fileURL);
        preview.src = location.href.substr( 0, location.href.lastIndexOf('/') ) +'/'+ fileURL;
        if(preview.src)
        {
            transfert.innerHTML = "Transfert réussi !!";
            transfert.style.visibility = "visible";
            chargement2.style.visibility = "hidden";
        }
    });
}
function Deposer()
{
    // audioRecorder.exportWAV( doneEncoding2 );
    audioRecorder.exportMP3( doneEncoding2 );
    chargement2.style.visibility = "visible";
}

function drawWave( buffers ) {
    var canvas = document.getElementById( "wavedisplay" );

    drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffers[0] );
}

function doneEncoding( blob ) {
    listen(blob);
}
function doneEncoding2( blob ) {
    fileName = Math.round(Math.random() * 99999999) + 99999999;
    PostBlob(blob, fileName + '.mp3');
    // Recorder.forceDownload( blob, "myRecording" + ((recIndex<10)?"0":"") + recIndex + ".wav" );
    // recIndex++;
}

function toggleRecording( e ) {
    if (e.classList.contains("recording")) {
        // stop recording
        audioRecorder.stop();
        e.classList.remove("recording");
        audioRecorder.getBuffers( drawWave );
         divChargement.style.visibility = "hidden";
         transfert.style.visibility = "hidden";
         clearInterval(timer);
         compte = 1;

    } else {
            transfert.style.visibility = "hidden";
        divChargement.style.visibility = "visible";
        compte = 1;
        affichageCompteur.style.visibility = "visible";
        timer = setInterval('compter()',1000);
        // start recording
        if (!audioRecorder)
            return;
        e.classList.add("recording");
        audioRecorder.clear();
        audioRecorder.record();
    }
}

function convertToMono( input ) {
    var splitter = audioContext.createChannelSplitter(2);
    var merger = audioContext.createChannelMerger(2);

    input.connect( splitter );
    splitter.connect( merger, 0, 0 );
    splitter.connect( merger, 0, 1 );
    return merger;
}

function cancelAnalyserUpdates() {
    window.cancelAnimationFrame( rafID );
    rafID = null;
}

function updateAnalysers(time) {
    if (!analyserContext) {
        var canvas = document.getElementById("analyser");
        canvasWidth = canvas.width;
        canvasHeight = canvas.height;
        analyserContext = canvas.getContext('2d');
    }

    // analyzer draw code here
    {
        var SPACING = 3;
        var BAR_WIDTH = 1;
        var numBars = Math.round(canvasWidth / SPACING);
        var freqByteData = new Uint8Array(analyserNode.frequencyBinCount);

        analyserNode.getByteFrequencyData(freqByteData); 

        analyserContext.clearRect(0, 0, canvasWidth, canvasHeight);
        analyserContext.fillStyle = '#F6D565';
        analyserContext.lineCap = 'round';
        var multiplier = analyserNode.frequencyBinCount / numBars;

        // Draw rectangle for each frequency bin.
        for (var i = 0; i < numBars; ++i) {
            var magnitude = 0;
            var offset = Math.floor( i * multiplier );
            // gotta sum/average the block, or we miss narrow-bandwidth spikes
            for (var j = 0; j< multiplier; j++)
                magnitude += freqByteData[offset + j];
            magnitude = magnitude / multiplier;
            var magnitude2 = freqByteData[i * multiplier];
            analyserContext.fillStyle = "hsl( " + Math.round((i*360)/numBars) + ", 100%, 50%)";
            analyserContext.fillRect(i * SPACING, canvasHeight, BAR_WIDTH, -magnitude);
        }
    }
    
    rafID = window.requestAnimationFrame( updateAnalysers );
}

function toggleMono() {
    if (audioInput != realAudioInput) {
        audioInput.disconnect();
        realAudioInput.disconnect();
        audioInput = realAudioInput;
    } else {
        realAudioInput.disconnect();
        audioInput = convertToMono( realAudioInput );
    }

    audioInput.connect(inputPoint);
}

function gotStream(stream) {
    inputPoint = audioContext.createGain();

    // Create an AudioNode from the stream.
    realAudioInput = audioContext.createMediaStreamSource(stream);
    audioInput = realAudioInput;
    audioInput.connect(inputPoint);

//    audioInput = convertToMono( input );

    analyserNode = audioContext.createAnalyser();
    analyserNode.fftSize = 2048;
    inputPoint.connect( analyserNode );

    audioRecorder = new Recorder( inputPoint );

    zeroGain = audioContext.createGain();
    zeroGain.gain.value = 0.0;
    inputPoint.connect( zeroGain );
    zeroGain.connect( audioContext.destination );
    updateAnalysers();
}

function xhr(url, data, callback) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            callback(request.responseText);
        }
    };
    request.open('POST', url);
    request.send(data);
}

function initAudio() {
        if (!navigator.getUserMedia)
            navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        if (!navigator.cancelAnimationFrame)
            navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
        if (!navigator.requestAnimationFrame)
            navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;

    navigator.getUserMedia({audio:true}, gotStream, function(e) {
            alert('Error getting audio');
            console.log(e);
        });
}

window.addEventListener('load', initAudio );