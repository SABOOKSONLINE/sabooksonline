const stickyBanner = document.querySelector("#sticky-banner");
const stickySlider = document.querySelector(".sticky-slider");
let stickyChildId = Math.floor(Math.random() * stickySlider.childElementCount);
let sliderInterval;

const getNextStickyId = (currentId, maxId) => {
    let nextId = currentId + 1;
    if (nextId > maxId) nextId = 0;
    return nextId;
};

const showSticky = (id, elements) => {
    for (let i = 0; i < elements.childElementCount; i++) {
        elements.children[i].classList.toggle("show-sticky", i === id);
    }
};

const startStickySlider = (slider, interval = 7000) => {
    if (sliderInterval) clearInterval(sliderInterval);

    const childrenCount = slider.childElementCount;

    showSticky(stickyChildId, slider);

    sliderInterval = setInterval(() => {
        stickyChildId = getNextStickyId(stickyChildId, childrenCount - 1);
        showSticky(stickyChildId, slider);
    }, interval);
};

const stopStickySlider = () => {
    clearInterval(sliderInterval);
};

stickyBanner.addEventListener("mouseenter", stopStickySlider);
stickyBanner.addEventListener("mouseleave", () =>
    startStickySlider(stickySlider, 7000)
);

startStickySlider(stickySlider, 7000);

const closePopupBannerBtn = document.querySelector("#close-popup-banner");
const popupBannerBg = document.querySelector(".popup-banner-bg");
const popupBanner = document.querySelector(".popup-banner");

const openPopupBanner = () => {
    popupBannerBg.classList.remove("hide-banner-bg");
    popupBanner.classList.remove("hide-banner");
};

const closePopupBanner = () => {
    popupBannerBg.classList.add("hide-banner-bg");
    popupBanner.classList.add("hide-banner");
};

const currentDate = new Date();
const currentDay = currentDate.getDate();

const showPopupBanner = () => {
    const lastBannerPopup = localStorage.getItem("popup-banner");

    if (!lastBannerPopup || parseInt(lastBannerPopup) !== currentDay) {
        setTimeout(() => {
            openPopupBanner();
        }, 5000);
    }
};

showPopupBanner();

closePopupBannerBtn.addEventListener("click", () => {
    localStorage.setItem("popup-banner", currentDay);
    closePopupBanner();
    console.log("closed");
});
