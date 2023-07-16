function mergeAndControlAudio() {
  const audioContext = new (window.AudioContext || window.webkitAudioContext)();
  const audioFiles = document.querySelectorAll('input[type="file"]');
  const audioBuffers = [];
  let completedCount = 0;

  // Function to load audio files and create audio buffers
  function loadAudio(file, index) {
    const reader = new FileReader();
    reader.onload = function (e) {
      audioContext.decodeAudioData(e.target.result, function (buffer) {
        audioBuffers[index] = buffer;
        completedCount++;

        // Merge audio buffers once all files are loaded
        if (completedCount === audioFiles.length) {
          const totalLength = audioBuffers.reduce(
            (acc, buffer) => Math.max(acc, buffer.duration),
            0
          );

          // Create audio nodes
          const masterGain = audioContext.createGain();
          masterGain.connect(audioContext.destination);

          const bassEQ = new BiquadFilterNode(audioContext, {
            type: "lowshelf",
            frequency: 500,
            gain: 0,
          });

          const midEQ = new BiquadFilterNode(audioContext, {
            type: "peaking",
            Q: Math.SQRT1_2,
            frequency: 1500,
            gain: 0,
          });

          const trebleEQ = new BiquadFilterNode(audioContext, {
            type: "highshelf",
            frequency: 3000,
            gain: 0,
          });

          // Connect nodes to create audio processing chain
          bassEQ.connect(midEQ);
          midEQ.connect(trebleEQ);
          trebleEQ.connect(masterGain);

          // Start playing the merged audio
          for (let i = 0; i < audioBuffers.length; i++) {
            const buffer = audioBuffers[i];
            const audioSource = audioContext.createBufferSource();
            audioSource.buffer = buffer;
            audioSource.connect(bassEQ);
            audioSource.start(0);
          }

          // Update audio effect parameters
          const volume = document.getElementById("volume");
          const bass = document.getElementById("bass");
          const mid = document.getElementById("mid");
          const treble = document.getElementById("treble");

          volume.addEventListener("input", (e) => {
            const value = parseFloat(e.target.value);
            masterGain.gain.setTargetAtTime(
              value,
              audioContext.currentTime,
              0.01
            );
          });

          bass.addEventListener("input", (e) => {
            const value = parseFloat(e.target.value);
            bassEQ.gain.setTargetAtTime(value, audioContext.currentTime, 0.01);
          });

          mid.addEventListener("input", (e) => {
            const value = parseFloat(e.target.value);
            midEQ.gain.setTargetAtTime(value, audioContext.currentTime, 0.01);
          });

          treble.addEventListener("input", (e) => {
            const value = parseFloat(e.target.value);
            trebleEQ.gain.setTargetAtTime(
              value,
              audioContext.currentTime,
              0.01
            );
          });

          // Visualizer code
          const visualizer = document.getElementById("visualizer");
          const analyserNode = new AnalyserNode(audioContext, { fftSize: 256 });
          masterGain.connect(analyserNode);

          function drawVisualizer() {
            requestAnimationFrame(drawVisualizer);
            const bufferLength = analyserNode.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);
            analyserNode.getByteFrequencyData(dataArray);
            const width = visualizer.width;
            const height = visualizer.height;
            const barwidth = width / bufferLength;

            const canvasContext = visualizer.getContext("2d");
            canvasContext.clearRect(0, 0, width, height);

            dataArray.forEach((item, index) => {
              const y = ((item / 255) * height) / 2;
              const x = barwidth * index;

              canvasContext.fillStyle = `hsl(${(y / height) * 400},100%,50%)`;
              canvasContext.fillRect(x, height - y, barwidth, y);
            });
          }

          drawVisualizer();
        }
      });
    };
    reader.readAsArrayBuffer(file);
  }

  // Load each audio file
  for (let i = 0; i < audioFiles.length; i++) {
    loadAudio(audioFiles[i].files[0], i);
  }
}
resize();
function resize() {
  visualizer.width = visualizer.clientWidth * window.devicePixelRatio;
  visualizer.height = visualizer.clientHeight * window.devicePixelRatio;
}
/////////////
const realFileBtn1 = document.getElementById("file1");
const realFileBtn2 = document.getElementById("file2");
const realFileBtn3 = document.getElementById("file3");
// const customBtn = document.getElementById("custom-button");
const customTxt1 = document.getElementById("custom-text1");
const customTxt2 = document.getElementById("custom-text2");
const customTxt3 = document.getElementById("custom-text3");

realFileBtn1.addEventListener("click", function () {
  realFileBtn1.click();
});

realFileBtn1.addEventListener("change", function () {
  if (realFileBtn1.value) {
    customTxt1.innerHTML = realFileBtn1.value.match(
      /[\/\\]([\w\d\s\.\-\(\)]+)$/
    )[1];
  } else {
    customTxt1.innerHTML = "No file chosen, yet.";
  }
});

realFileBtn2.addEventListener("click", function () {
  realFileBtn2.click();
});

realFileBtn2.addEventListener("change", function () {
  if (realFileBtn2.value) {
    customTxt2.innerHTML = realFileBtn2.value.match(
      /[\/\\]([\w\d\s\.\-\(\)]+)$/
    )[1];
  } else {
    customTxt2.innerHTML = "No file chosen, yet.";
  }
});

realFileBtn3.addEventListener("click", function () {
  realFileBtn3.click();
});

realFileBtn3.addEventListener("change", function () {
  if (realFileBtn3.value) {
    customTxt3.innerHTML = realFileBtn3.value.match(
      /[\/\\]([\w\d\s\.\-\(\)]+)$/
    )[1];
  } else {
    customTxt3.innerHTML = "No file chosen, yet.";
  }
});
