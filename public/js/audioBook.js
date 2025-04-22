jQuery(document).ready(function () {
    const bookTitle = $(".audio-book-title .title");

    const bacward = $("#ctrl-backward");
    const play = document.querySelector("#ctrl-play");
    const forward = $("#ctrl-forward");

    let playing = false;

    let audio;

    const initializeAudio = () => {
        audio = new Audio("/public/js/audio/Intro.mp3");
    };

    initializeAudio();

    const playAudio = () => {
        audio.play();
        play.firstElementChild.classList.remove("fa-play");
        play.firstElementChild.classList.add("fa-pause");
        playing = true;
    };

    const pauseAudio = () => {
        audio.pause();
        play.firstElementChild.classList.remove("fa-pause");
        play.firstElementChild.classList.add("fa-play");
        playing = false;
    };

    play.addEventListener("click", () => {
        if (!playing) {
            playAudio();
        } else {
            pauseAudio();
        }
    });
});
