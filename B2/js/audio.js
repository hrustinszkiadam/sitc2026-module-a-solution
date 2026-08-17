// B2 — Turntable

const tracks = ["audio/DrunkonSoju.wav", "audio/LostSomewhere.wav"];
let currentTrack = 0;

const player = new Audio(tracks[currentTrack]);

const wheel = document.querySelector(".wheel");
const toneArm = document.querySelector(".tone-arm");
const offButton = document.getElementById("off");
const nextButton = document.getElementById("next");
const volume = document.getElementById("volume");

player.volume = Number(volume.value);

function play() {
  player.play();
  wheel.classList.add("animation");
  toneArm.classList.add("animation");
}

function stop() {
  player.pause();
  player.currentTime = 0;
  wheel.classList.remove("animation");
  toneArm.classList.remove("animation");
}

// Pressing the record starts the song.
wheel.addEventListener("click", function () {
  if (player.paused) {
    play();
  }
});

// Off stops the song and puts the record and the arm back to their rest position.
offButton.addEventListener("click", stop);

// Switch to the other track, and keep playing if a song was already running.
nextButton.addEventListener("click", function () {
  const wasPlaying = !player.paused;

  currentTrack = (currentTrack + 1) % tracks.length;
  player.src = tracks[currentTrack];

  if (wasPlaying) {
    // restart the arm animation from the beginning for the new song
    toneArm.classList.remove("animation");
    void toneArm.offsetWidth;
    play();
  }
});

volume.addEventListener("input", function () {
  player.volume = Number(volume.value);
});

// When the song is over everything returns to its original state.
player.addEventListener("ended", stop);
