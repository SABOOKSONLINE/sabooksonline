jQuery(document).ready(function () {
    const bookTitle = $(".audio-book-title .title");

    const bacward = $("#ctrl-backward");
    const play = document.querySelector("#ctrl-play");
    const forward = $("#ctrl-forward");

    const chapters = document.querySelectorAll(".chapter");

    let audio_list = $(".audio-chapters").children();

    let playing = false;
    let audio;

    let selectAudio = audio_list.eq(0);

    const initializeAudio = (audioUrl = "") => {
        updateTitle();
        if (!audioUrl) {
            audioUrl = selectAudio.attr("audio_url");
        }

        audio = new Audio("/public/audio/" + audioUrl);
        selectChapterByUrl(audioUrl);
    };

    const updateTitle = (chapter = "") => {
        if (!chapter) {
            bookTitle.text(selectAudio.attr("audio_url"));
        } else {
            bookTitle.text(chapter);
        }
    };

    const continuePlaying = () => {
        if (playing) {
            playAudio();
        } else {
            pauseAudio();
        }
    };

    const deactiveChapterSelections = () => {
        chapters.forEach((chapter) => {
            chapter.classList.remove("active-chapter");
        });
    };

    const selectChapterByUrl = (url) => {
        deactiveChapterSelections();

        chapters.forEach((chapter) => {
            if (chapter.getAttribute("audio_url") === url) {
                chapter.classList.add("active-chapter");
            }
        });
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

    const forwardPlay = () => {
        let selectedAudioIndex = selectAudio.index();
        if (selectedAudioIndex + 1 < audio_list.length) {
            selectedAudioIndex++;
        }

        selectAudio = audio_list.eq(selectedAudioIndex);

        audio.pause();
        initializeAudio(selectAudio.attr("audio_url"));
        continuePlaying();
    };

    const backwardPlay = () => {
        let selectedAudioIndex = selectAudio.index();
        if (selectedAudioIndex - 1 >= 0) {
            selectedAudioIndex--;
        }

        selectAudio = audio_list.eq(selectedAudioIndex);

        audio.pause();
        initializeAudio(selectAudio.attr("audio_url"));
        continuePlaying();
    };

    play.addEventListener("click", () => {
        if (!playing) {
            playAudio();
        } else {
            pauseAudio();
        }
    });

    forward.on("click", function () {
        forwardPlay();
        updateTitle();
    });

    bacward.on("click", function () {
        backwardPlay();
        updateTitle();
    });

    chapters.forEach((chapter) => {
        chapter.addEventListener("click", function () {
            chapter_url = chapter.getAttribute("audio_url");
            deactiveChapterSelections();

            chapter.classList.add("active-chapter");

            audio.pause();
            initializeAudio(chapter_url);
            updateTitle(chapter_url);
            continuePlaying();
        });
    });
});
