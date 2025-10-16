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
