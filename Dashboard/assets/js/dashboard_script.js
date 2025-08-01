document.getElementById("sidebarToggle").addEventListener("click", function () {
    let sidebar = document.getElementById("dashboardSidebar");
    sidebar.classList.toggle("d-none");
    sidebar.classList.toggle("d-flex");
});

const abNarrator = document.getElementById("audiobook_narrator");
const abReleaseDate = document.getElementById("release_date");

const aditAb = document.getElementById("adit_ab");

const abPopBg = document.querySelector(".ab_pop_bg");
const popAbForm = document.querySelector(".ab_pop_form");

const closeAbPop = document.querySelector(".close_ab_pop_form");

const openAbPop = () => {
    abPopBg.classList.toggle("pop_form_active");
    popAbForm.classList.toggle("pop_form_active");
};

abNarrator.addEventListener("click", () => {
    openAbPop();
});

abReleaseDate.addEventListener("click", () => {
    openAbPop();
});

aditAb.addEventListener("click", () => {
    openAbPop();
});

closeAbPop.addEventListener("click", () => {
    openAbPop();
});

if (abNarrator.value) {
    const popFormButtton = document.querySelector("#pop_form_btn");
    const popBg = document.querySelector(".pop_bg");
    const popForm = document.querySelector(".pop_form");
    const closePop = document.querySelector(".close_pop_form");

    const popSampleButton = document.querySelector("#sample_pop_btn");
    const popSampleBg = document.querySelector(".sample_pop_bg");
    const popSampleForm = document.querySelector(".sample_pop_form");
    const closeSampleForm = document.querySelector(".close_sample_pop_form");

    const openSamplePopForm = () => {
        popSampleBg.classList.toggle("pop_form_active");
        popSampleForm.classList.toggle("pop_form_active");
    };

    popSampleButton.addEventListener("click", () => {
        openSamplePopForm();
    });

    closeSampleForm.addEventListener("click", () => {
        openSamplePopForm();
    });

    const editChapterBtns = document.querySelectorAll(".edit_chapter");

    const chapterForm = document.querySelector("#chapter_form");
    const chapterId = document.querySelector("#chapter_id");
    const chapterNo = document.querySelector("#chapter_number");
    const chapterTitle = document.querySelector("#chapter_title");
    const chapterUrl = document.querySelector("#audio_url");
    const chapterUpdateBtn = document.querySelector("#chapter_btn");

    const openPop = () => {
        popBg.classList.toggle("pop_form_active");
        popForm.classList.toggle("pop_form_active");
    };

    popFormButtton.addEventListener("click", () => {
        openPop();
    });

    closePop.addEventListener("click", () => {
        openPop();
    });

    editChapterBtns.forEach((editBtn) => {
        editBtn.addEventListener("click", () => {
            const chapterData = {
                chapter_id: editBtn.dataset.chapterId,
                chapter_number: editBtn.dataset.chapterNumber,
                chapter_title: editBtn.dataset.chapterTitle,
                audio_url: editBtn.dataset.audioUrl,
            };

            chapterForm.action =
                "/dashboards/listings/updateAudioChapter/" +
                chapterData["chapter_id"];

            chapterId.value = chapterData["chapter_id"];
            chapterNo.value = chapterData["chapter_number"];
            chapterTitle.value = chapterData["chapter_title"];
            chapterUrl.src = "/cms-data/audiobooks/" + chapterData["audio_url"];
            chapterUrl.load();

            chapterUpdateBtn.innerText = "Update Chapter";

            openPop();

            console.log(chapterForm);
        });
    });
}
