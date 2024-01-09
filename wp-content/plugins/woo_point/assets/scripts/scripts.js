const hiddenClass = 'd-none';
let isEditAll = false;
let step = 1;
let ids = [];

function showModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(id);
  modalOverlay.classList.remove(hiddenClass);
  modal.classList.remove(hiddenClass);
}

function hideModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(id);
  modalOverlay.classList.add(hiddenClass);
  modal.classList.add(hiddenClass);
  step = 1;
}

function changeModalContent(id) {
  for (let i = 1; i <= 4; i++) {
    if (i === step) {
      document.getElementById(`content-step-${i}-edit-${id}`).classList.remove(hiddenClass);
    } else {
      document.getElementById(`content-step-${i}-edit-${id}`).classList.add(hiddenClass);
    }
  }
}

function handleChangeValue(tags, classes) {
  tags.forEach((tag, index) => {
    tag.addEventListener('change', function(e) {
      classes[index].innerHTML = e.target.value;
    });
  });
}

function openEditModal(id) {
  step = isEditAll ? 1 : 2;
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-edit-ranking-${id}`);
  const modalNext = document.getElementById(`modal-next-edit-${id}`);
  const modalPrev = document.getElementById(`modal-prev-edit-${id}`);
  const modalUpdate = document.getElementById(`modal-update-edit-${id}`);
  const uploadImageBtn = document.getElementById(`upload-image-button-edit-${id}`);
  modalOverlay.classList.remove(hiddenClass);
  modal.classList.remove(hiddenClass);
  modalPrev.classList.add(hiddenClass);
  modalUpdate.classList.add(hiddenClass);
  modalNext.classList.remove(hiddenClass);
  for (let i = 1; i <= 4; i++) {
    document.getElementById(`step-${i}-edit-${id}`).classList.remove('current');
    document.getElementById(`step-${i}-edit-${id}`).classList.remove('active');
  }
  document.getElementById(`step-${step}-edit-${id}`).classList.add('current');
  for (let i = step - 1; i > 0; i--) {
    document.getElementById(`step-${i}-edit-${id}`).classList.add('active');
  }
  changeModalContent(id);

  !ids.includes(id) && modalNext.addEventListener('click', function() {
    step++;
    changeModalContent(id);
    modalPrev.classList.remove(hiddenClass);
    document.getElementById(`step-${step}-edit-${id}`).classList.add('current');
    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}-edit-${id}`).classList.add('active');
    }
    if (step === 4) {
      modalNext.classList.add(hiddenClass);
      modalUpdate.classList.remove(hiddenClass);
    }
  });

  !ids.includes(id) && modalPrev.addEventListener('click', function() {
    document.getElementById(`step-${step}-edit-${id}`).classList.remove('current');
    step--;
    document.getElementById(`step-${step}-edit-${id}`).classList.remove('active');
    if (step === 1 || (step === 2 && isEditAll === false)) {
      modalPrev.classList.add(hiddenClass);
    }
    if (step === 3) {
      modalNext.classList.remove(hiddenClass);
      modalUpdate.classList.add(hiddenClass);
    }
    changeModalContent(id);
  });

  /* Image Upload */
  !ids.includes(id) && uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    aw_uploader = wp.media({
      title: 'Đổi hình ảnh',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      var attachment = aw_uploader.state().get('selection').first().toJSON();
      document.getElementById(`image-url-edit-${id}`).value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = document.getElementById(`image-wrapper-edit-${id}`);
      wrapper.innerHTML = '';
      wrapper.appendChild(img);
    })
    .open();
  });

  /* Handle rank name */
  const rankNames = document.getElementsByName('name');
  const showRanks = document.getElementsByClassName('show-rank');
  const showRanksFinal = document.getElementsByClassName('show-rank-final');
  handleChangeValue(rankNames, showRanks);
  handleChangeValue(rankNames, showRanksFinal);

  /* Handle minimum spending */
  const minimumSpending = document.getElementsByName('minimum_spending');
  const showMinimumSpendingFinal = document.getElementsByClassName('show-minimum-spending-final');
  handleChangeValue(minimumSpending, showMinimumSpendingFinal);

  /* Handle price sale off */
  const priceSaleOff = document.getElementsByName('price_sale_off');
  const showPriceSaleOffFinal = document.getElementsByClassName('show-price-sale-off-final');
  handleChangeValue(priceSaleOff, showPriceSaleOffFinal);

  /* Handle price sale off max */
  const priceSaleOffMax = document.getElementsByName('price_sale_off_max');
  const showPriceSaleOffMaxFinal = document.getElementsByClassName('show-price-sale-off-max-final');
  handleChangeValue(priceSaleOffMax, showPriceSaleOffMaxFinal);

  /* Handle limit input */
  const inputs = document.getElementsByName('is_limit');
  const div = document.getElementsByClassName('is-limit-content');
  const divFinal = document.getElementsByClassName('is-limit-content-final');
  const priceSaleOffMaxByClass = document.getElementsByClassName('price-sale-off-max');
  inputs.forEach((input, index) => {
    input.addEventListener('click', function() {
      if (input.checked) {
        div[index].classList.remove(hiddenClass);
        div[index].previousElementSibling.classList.add(hiddenClass);
        divFinal[index].classList.remove(hiddenClass);
        divFinal[index].previousElementSibling.classList.add(hiddenClass);
      } else {
        priceSaleOffMaxByClass[index].value = '';
        div[index].classList.add(hiddenClass);
        div[index].previousElementSibling.classList.remove(hiddenClass);
        divFinal[index].classList.add(hiddenClass);
        divFinal[index].previousElementSibling.classList.remove(hiddenClass);
      }
    });
  });

  ids.push(id);
}

window.addEventListener('load', function() {
  /* Common */
  const removeMessageBtn = document.getElementById('remove-message');
  removeMessageBtn && removeMessageBtn.addEventListener('click', function() {
    document.getElementById('message').classList.add(hiddenClass);
  });

  /* Tabs */
  const tabs = document.querySelectorAll('ul.nav-tabs > li');
  for (i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener('click', switchTab);
  }

  function switchTab(event) {
    event.preventDefault();
    document.querySelector('ul.nav-tabs li.active').classList.remove('active');
    document.querySelector('.tab-pane.active').classList.remove('active');
    const clickedTab = event.currentTarget;
    const anchor = event.target;
    const activePaneID = anchor.getAttribute('href');
    clickedTab.classList.add('active');
    document.querySelector(activePaneID).classList.add('active');
  }

  /* Modal */
  let step = 1;
  let isEditAll = false;
  let listIdsDelete = [];
  const modalOverlay = document.getElementById('overlay');
  const modalAddBtn = document.getElementById('modal-add-btn');
  const modalEditBtn = document.getElementById('modal-edit-btn');
  const modalDeleteBtn = document.getElementById('modal-delete-btn');
  const modalAdd = document.getElementById('modal-add');
  const modalClose = document.getElementById('modal-close');
  const modalNext = document.getElementById('modal-next');
  const modalPrev = document.getElementById('modal-prev');
  const modalUpdate = document.getElementById('modal-update');
  const listCheckbox = document.getElementsByName('checkRank');
  const buttonDeleteWrapper = document.getElementById('button-delete-wrapper');
  const checkboxAll = document.getElementById('checkAllRank');
  const modalDeleteAll = document.getElementById('modal-delete-all-ranking');

  checkboxAll.addEventListener('click', function(e) {
    if (e.target.checked) {
      buttonDeleteWrapper.classList.remove('d-none');
      listCheckbox.forEach((item) => {
        listIdsDelete.push(item.value);
      })
    } else {
      buttonDeleteWrapper.classList.add('d-none');
      listIdsDelete = [];
    }
    listIdsDelete = [...new Set(listIdsDelete)];
    console.log(listIdsDelete);
  });

  listCheckbox.forEach((item) => {
    item.addEventListener('click', function() {
      if (item.checked) {
        buttonDeleteWrapper.classList.remove('d-none');
        listIdsDelete.push(item.value);
        console.log(listIdsDelete);
      } else {
        listIdsDelete = listIdsDelete.filter(id => Number(id) !== Number(item.value));
        console.log(listIdsDelete);
        check = 0;
        listCheckbox.forEach((item2) => {
          if (item2.checked) {
            check++;
          }
        });

        if (check === 0) {
          buttonDeleteWrapper.classList.add('d-none');
        }
      }
    });
  });

  modalAddBtn.addEventListener('click', function() {
    openModal();
  });

  modalEditBtn.addEventListener('click', function() {
    // openModal();
  });

  modalDeleteBtn.addEventListener('click', function() {
    modalOverlay.classList.remove(hiddenClass);
    modalDeleteAll.classList.remove(hiddenClass);
  });

  modalClose.addEventListener('click', function() {
    modalOverlay.classList.add(hiddenClass);
    modalAdd.classList.add(hiddenClass);
  });

  modalNext.addEventListener('click', function() {
    step++;
    changeModalContent();
    modalPrev.classList.remove(hiddenClass);
    document.getElementById(`step-${step}`).classList.add('current');
    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}`).classList.add('active');
    }
    if (step === 4) {
      modalNext.classList.add(hiddenClass);
      modalUpdate.classList.remove(hiddenClass);
    }
  });

  modalPrev.addEventListener('click', function() {
    document.getElementById(`step-${step}`).classList.remove('current');
    step--;
    document.getElementById(`step-${step}`).classList.remove('active');
    if (step === 1 || (step === 2 && isEditAll === false)) {
      modalPrev.classList.add(hiddenClass);
    }
    if (step === 3) {
      modalNext.classList.remove(hiddenClass);
      modalUpdate.classList.add(hiddenClass);
    }
    changeModalContent();
  });

  function openModal() {
    step = isEditAll ? 1 : 2;
    modalPrev.classList.add(hiddenClass);
    modalUpdate.classList.add(hiddenClass);
    modalOverlay.classList.remove(hiddenClass);
    modalAdd.classList.remove(hiddenClass);
    modalNext.classList.remove(hiddenClass);
    for (let i = 1; i <= 4; i++) {
      document.getElementById(`step-${i}`).classList.remove('current');
      document.getElementById(`step-${i}`).classList.remove('active');
    }
    document.getElementById(`step-${step}`).classList.add('current');
    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}`).classList.add('active');
    }
    changeModalContent();
  }

  function changeModalContent() {
    for (let i = 1; i <= 4; i++) {
      if (i === step) {
        document.getElementById(`content-step-${i}`).classList.remove(hiddenClass);
      } else {
        document.getElementById(`content-step-${i}`).classList.add(hiddenClass);
      }
    }
  }

  /* Image Upload */
  const uploadImageBtn = document.getElementById('upload-image-button');
  uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    aw_uploader = wp.media({
      title: 'Thêm hình ảnh',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      var attachment = aw_uploader.state().get('selection').first().toJSON();
      document.getElementById('image-url').value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = document.getElementById('image-wrapper');
      wrapper.innerHTML = '';
      wrapper.appendChild(img);
    })
    .open();
  });

  function handleChangeValue(tags, classes) {
    tags.forEach((tag, index) => {
      tag.addEventListener('change', function(e) {
        classes[index].innerHTML = e.target.value;
      });
    });
  }

  /* Handle rank name */
  const rankNames = document.getElementsByName('name');
  const showRanks = document.getElementsByClassName('show-rank');
  const showRanksFinal = document.getElementsByClassName('show-rank-final');
  handleChangeValue(rankNames, showRanks);
  handleChangeValue(rankNames, showRanksFinal);

  /* Handle minimum spending */
  const minimumSpending = document.getElementsByName('minimum_spending');
  const showMinimumSpendingFinal = document.getElementsByClassName('show-minimum-spending-final');
  handleChangeValue(minimumSpending, showMinimumSpendingFinal);

  /* Handle price sale off */
  const priceSaleOff = document.getElementsByName('price_sale_off');
  const showPriceSaleOffFinal = document.getElementsByClassName('show-price-sale-off-final');
  handleChangeValue(priceSaleOff, showPriceSaleOffFinal);

  /* Handle price sale off max */
  const priceSaleOffMax = document.getElementsByName('price_sale_off_max');
  const showPriceSaleOffMaxFinal = document.getElementsByClassName('show-price-sale-off-max-final');
  handleChangeValue(priceSaleOffMax, showPriceSaleOffMaxFinal);

  /* Handle limit input */
  const inputs = document.getElementsByName('is_limit');
  const div = document.getElementsByClassName('is-limit-content');
  const divFinal = document.getElementsByClassName('is-limit-content-final');
  const priceSaleOffMaxByClass = document.getElementsByClassName('price-sale-off-max');
  inputs.forEach((input, index) => {
    input.addEventListener('click', function() {
      if (input.checked) {
        div[index].classList.remove(hiddenClass);
        div[index].previousElementSibling.classList.add(hiddenClass);
        divFinal[index].classList.remove(hiddenClass);
        divFinal[index].previousElementSibling.classList.add(hiddenClass);
      } else {
        priceSaleOffMaxByClass[index].value = '';
        div[index].classList.add(hiddenClass);
        div[index].previousElementSibling.classList.remove(hiddenClass);
        divFinal[index].classList.add(hiddenClass);
        divFinal[index].previousElementSibling.classList.remove(hiddenClass);
      }
    });
  });
});
