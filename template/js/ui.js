let mainUi = {};

mainUi.toggleModal = (targetId, toggleClass = "modalActive") => {
    $(`#${targetId}`).toggleClass(toggleClass);
}

mainUi.closeModal = (toggleClass = "modalActive") => {
    $(`.${toggleClass}`).removeClass(toggleClass);
}