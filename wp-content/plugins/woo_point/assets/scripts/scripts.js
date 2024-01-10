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

function changeModalContent() {
  for (let i = 1; i <= 4; i++) {
    const contentStep = document.getElementsByClassName(`content-step-${i}-edit`);
    if (i === step) {
      for (let j = 0; j < contentStep.length; j++) {
        contentStep[j].classList.remove(hiddenClass);
      }
    } else {
      for (let j = 0; j < contentStep.length; j++) {
        contentStep[j].classList.add(hiddenClass);
      }
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

function handleInputChange(modal) {
  /* Handle rank name */
  const rankNames = modal.querySelectorAll('input[name="name[]"]');
  const showRanks = modal.querySelectorAll('.show-rank');
  const showRanksFinal = modal.querySelectorAll('.show-rank-final');
  handleChangeValue(rankNames, showRanks);
  handleChangeValue(rankNames, showRanksFinal);

  /* Handle minimum spending */
  const minimumSpending = modal.querySelectorAll('input[name="minimum_spending[]"]');
  const showMinimumSpendingFinal = modal.querySelectorAll('.show-minimum-spending-final');
  handleChangeValue(minimumSpending, showMinimumSpendingFinal);

  /* Handle price sale off */
  const priceSaleOff = modal.querySelectorAll('input[name="price_sale_off[]"]');
  const showPriceSaleOffFinal = modal.querySelectorAll('.show-price-sale-off-final');
  handleChangeValue(priceSaleOff, showPriceSaleOffFinal);

  /* Handle price sale off max */
  const priceSaleOffMax = modal.querySelectorAll('input[name="price_sale_off_max[]"]');
  const showPriceSaleOffMaxFinal = modal.querySelectorAll('.show-price-sale-off-max-final');
  handleChangeValue(priceSaleOffMax, showPriceSaleOffMaxFinal);

  /* Handle limit input */
  const inputs = modal.querySelectorAll('input[name="is_limit[]"]');
  const div = modal.querySelectorAll('.is-limit-content');
  const divFinal = modal.querySelectorAll('.is-limit-content-final');
  const priceSaleOffMaxByClass = modal.querySelectorAll('.price-sale-off-max');
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
}

function openEditModal(id) {
  step = isEditAll ? 1 : 2;
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-edit-ranking-${id}`);
  const modalNextStep2 = document.getElementById(`modal-next-edit-step-2-${id}`);
  const modalNextStep3 = document.getElementById(`modal-next-edit-step-3-${id}`);
  const modalPrev = document.getElementById(`modal-prev-edit-${id}`);
  const modalUpdate = document.getElementById(`modal-update-edit-${id}`);
  const uploadImageBtn = document.getElementById(`upload-image-button-edit-${id}`);
  modalOverlay.classList.remove(hiddenClass);
  modal.classList.remove(hiddenClass);
  modalPrev.classList.add(hiddenClass);
  modalUpdate.classList.add(hiddenClass);
  modalNextStep2.classList.remove(hiddenClass);
  modalNextStep3.classList.add(hiddenClass);

  for (let i = 1; i <= 4; i++) {
    document.getElementById(`step-${i}-edit-${id}`).classList.remove('current');
    document.getElementById(`step-${i}-edit-${id}`).classList.remove('active');
  }

  document.getElementById(`step-${step}-edit-${id}`).classList.add('current');

  for (let i = step - 1; i > 0; i--) {
    document.getElementById(`step-${i}-edit-${id}`).classList.add('active');
  }

  changeModalContent(id);

  !ids.includes(id) && modalNextStep2.addEventListener('click', function() {
    let checkPassStep2 = 0;
    if (step === 2) {
      const contentStep2 = document.querySelector('.content-step-2-edit');
      const requireInputStep2 = contentStep2.querySelectorAll('.require-field');

      requireInputStep2.forEach((input) => {
        if (input.value === '') {
          input.nextElementSibling.classList.remove(hiddenClass);
        } else {
          checkPassStep2++;
          input.nextElementSibling.classList.add(hiddenClass);
        }
      });

      if (checkPassStep2 === requireInputStep2.length) {
        nextStep();
      }
    }
  });

  !ids.includes(id) && modalNextStep3.addEventListener('click', function() {
    let checkPassStep3 = 0;
    if (step === 3) {
      const contentStep3 = document.querySelector('.content-step-3-edit');
      const requireInputStep3 = contentStep3.querySelectorAll('.require-field');
      const limitInput = contentStep3.querySelector('.is-limit-input');
      
      requireInputStep3.forEach((input) => {
        if (input.value === '') {
          input.nextElementSibling.classList.remove(hiddenClass);
        } else {
          checkPassStep3++;
          input.nextElementSibling.classList.add(hiddenClass);
        }
      });

      if (checkPassStep3 === requireInputStep3.length || (checkPassStep3 === 1 && limitInput.checked === false)) {
        nextStep();
      }
    }
  });

  function nextStep() {
    step++;
    changeModalContent(id);
    modalPrev.classList.remove(hiddenClass);
    document.getElementById(`step-${step}-edit-${id}`).classList.add('current');

    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}-edit-${id}`).classList.add('active');
    }

    if (step === 3) {
      modalNextStep2.classList.add(hiddenClass);
      modalNextStep3.classList.remove(hiddenClass);
    }

    if (step === 4) {
      modalNextStep2.classList.add(hiddenClass);
      modalNextStep3.classList.add(hiddenClass);
      modalUpdate.classList.remove(hiddenClass);
    }
  }

  !ids.includes(id) && modalPrev.addEventListener('click', function() {
    document.getElementById(`step-${step}-edit-${id}`).classList.remove('current');
    step--;
    document.getElementById(`step-${step}-edit-${id}`).classList.remove('active');

    if (step === 1 || (step === 2 && isEditAll === false)) {
      modalPrev.classList.add(hiddenClass);
    }

    if (step === 2) {
      modalNextStep2.classList.remove(hiddenClass);
      modalNextStep3.classList.add(hiddenClass);
    }

    if (step === 3) {
      modalNextStep3.classList.remove(hiddenClass);
      modalUpdate.classList.add(hiddenClass);
    }

    changeModalContent(id);
  });

  /* Image Upload */
  !ids.includes(id) && uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    uploader = wp.media({
      title: 'Đổi hình ảnh',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      var attachment = uploader.state().get('selection').first().toJSON();
      document.getElementById(`image-url-edit-${id}`).value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = document.getElementById(`image-wrapper-edit-${id}`);
      wrapper.innerHTML = '';
      wrapper.appendChild(img);
    })
    .open();
  });

  handleInputChange(modal);

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
  let listNamesDelete = [];
  let recordLength = 1;
  const modalOverlay = document.getElementById('overlay');
  const modalAddBtn = document.getElementById('modal-add-btn');
  const modalEditBtn = document.getElementById('modal-edit-btn');
  const modalDeleteBtn = document.getElementById('modal-delete-btn');
  const modalAdd = document.getElementById('modal-add');
  const modalClose = document.getElementById('modal-close');
  const modalNextStep2 = document.getElementById('modal-next-step-2');
  const modalNextStep3 = document.getElementById('modal-next-step-3');
  const modalPrev = document.getElementById('modal-prev');
  const modalUpdate = document.getElementById('modal-update');
  const listCheckbox = document.getElementsByName('checkRank');
  const buttonDeleteWrapper = document.getElementById('button-delete-wrapper');
  const checkboxAll = document.getElementById('checkAllRank');
  const modalDeleteAll = document.getElementById('modal-delete-all-ranking');
  const deleteList = document.getElementById('deleteList');
  const addMoreRecord = document.getElementById('add-more-record');
  const tableStep2 = document.getElementById('table-step-2');
  const tableStep3 = document.getElementById('table-step-3');
  const tableStep4 = document.getElementById('table-step-4');

  addMoreRecord.addEventListener('click', function() {
    recordLength++;
    const rowStep2 = document.getElementById('record-step-2-add-1');
    const cloneStep2 = rowStep2.cloneNode(true);
    cloneStep2.id = `record-step-2-add-${recordLength}`;
    tableStep2.appendChild(cloneStep2);
    reTriggerUploadImage(cloneStep2, recordLength);

    const rowStep3 = document.getElementById('record-step-3-add-1');
    const cloneStep3 = rowStep3.cloneNode(true);
    cloneStep3.id = `record-step-3-add-${recordLength}`;
    tableStep3.appendChild(cloneStep3);

    const rowStep4 = document.getElementById('record-step-4-add-1');
    const cloneStep4 = rowStep4.cloneNode(true);
    cloneStep4.id = `record-step-4-add-${recordLength}`;
    tableStep4.appendChild(cloneStep4);

    handleInputChange(modalAdd);
  });

  function reTriggerUploadImage(parent, number) {
    const uploadImageBtn = parent.querySelector('.upload-image-button');
    uploadImageBtn.addEventListener('click', function(e) {
      e.preventDefault();
      uploader = wp.media({
        title: 'Thêm hình ảnh',
        button: {
          text: 'Sử dụng ảnh này'
        },
        multiple: false
      }).on('select', function() {
        const attachment = uploader.state().get('selection').first().toJSON();
        const tdRecord = document.getElementById(`record-step-2-add-${number}`);
        tdRecord.querySelector('.image-url').value = attachment.url;
        img = document.createElement('img');
        img.src = attachment.url;
        const wrapper = tdRecord.querySelector('.image-wrapper');
        wrapper.innerHTML = '';
        wrapper.appendChild(img);
      })
      .open();
    });
  }

  checkboxAll.addEventListener('click', function(e) {
    if (e.target.checked) {
      buttonDeleteWrapper.classList.remove('d-none');
      listCheckbox.forEach((item) => {
        listIdsDelete.push(item.value.split('-')[0]);
        listNamesDelete.push(item.value.split('-')[1]);
      })
    } else {
      buttonDeleteWrapper.classList.add('d-none');
      listIdsDelete = [];
      listNamesDelete = [];
    }
    listIdsDelete = [...new Set(listIdsDelete)];
    listNamesDelete = [...new Set(listNamesDelete)];
  });

  listCheckbox.forEach((item) => {
    item.addEventListener('click', function() {
      if (item.checked) {
        buttonDeleteWrapper.classList.remove('d-none');
        listIdsDelete.push(item.value.split('-')[0]);
        listNamesDelete.push(item.value.split('-')[1]);
      } else {
        listIdsDelete = listIdsDelete.filter(id => Number(id) !== Number(item.value.split('-')[0]));
        listNamesDelete = listNamesDelete.filter(name => name !== item.value.split('-')[1]);
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
    deleteList.innerHTML = '';
    modalOverlay.classList.remove(hiddenClass);
    modalDeleteAll.classList.remove(hiddenClass);

    listIdsDelete.forEach((id) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id-delete[]';
      input.value = id;
      deleteList.appendChild(input);
    });

    listNamesDelete.forEach((name, index) => {
      const span = document.createElement('span');
      span.innerHTML = index === 0 ? name : `, ${name}`;
      deleteList.appendChild(span);
    });
  });

  modalClose.addEventListener('click', function() {
    modalOverlay.classList.add(hiddenClass);
    modalAdd.classList.add(hiddenClass);
  });

  modalNextStep2.addEventListener('click', function() {
    let checkPassStep2 = 0;
    if (step === 2) {
      const contentStep2 = document.querySelector('.content-step-2');
      const requireInputStep2 = contentStep2.querySelectorAll('.require-field');

      requireInputStep2.forEach((input) => {
        if (input.value === '') {
          input.nextElementSibling.classList.remove(hiddenClass);
        } else {
          checkPassStep2++;
          input.nextElementSibling.classList.add(hiddenClass);
        }
      });

      if (checkPassStep2 === requireInputStep2.length) {
        nextStep();
      }
    }
  });

  modalNextStep3.addEventListener('click', function() {
    let checkPassStep3 = 0;
    if (step === 3) {
      const contentStep3 = document.querySelector('.content-step-3');
      const requireInputStep3 = contentStep3.querySelectorAll('.require-field');
      const limitInput = contentStep3.querySelector('.is-limit-input');
      console.log('requireInputStep3', requireInputStep3);
      console.log('limitInput', limitInput);
      
      requireInputStep3.forEach((input) => {
        if (input.value === '') {
          input.nextElementSibling.classList.remove(hiddenClass);
        } else {
          checkPassStep3++;
          input.nextElementSibling.classList.add(hiddenClass);
        }
      });

      console.log('checkPassStep3', checkPassStep3);

      if (checkPassStep3 === requireInputStep3.length || (checkPassStep3 === 1 && limitInput.checked === false)) {
        nextStep();
      }
    }
  });

  function nextStep() {
    step++;
    changeModalContent();
    modalPrev.classList.remove(hiddenClass);
    document.getElementById(`step-${step}`).classList.add('current');

    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}`).classList.add('active');
    }

    if (step === 3) {
      modalNextStep2.classList.add(hiddenClass);
      modalNextStep3.classList.remove(hiddenClass);
    }

    if (step === 4) {
      modalNextStep2.classList.add(hiddenClass);
      modalNextStep3.classList.add(hiddenClass);
      modalUpdate.classList.remove(hiddenClass);
    }
  }

  modalPrev.addEventListener('click', function() {
    document.getElementById(`step-${step}`).classList.remove('current');
    step--;
    document.getElementById(`step-${step}`).classList.remove('active');

    if (step === 1 || (step === 2 && isEditAll === false)) {
      modalPrev.classList.add(hiddenClass);
    }

    if (step === 2) {
      modalNextStep2.classList.remove(hiddenClass);
      modalNextStep3.classList.add(hiddenClass);
    }

    if (step === 3) {
      modalNextStep3.classList.remove(hiddenClass);
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
    modalNextStep2.classList.remove(hiddenClass);
    modalNextStep3.classList.add(hiddenClass);

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
        document.getElementsByClassName(`content-step-${i}`)[0].classList.remove(hiddenClass);
      } else {
        document.getElementsByClassName(`content-step-${i}`)[0].classList.add(hiddenClass);
      }
    }
  }

  /* Image Upload */
  const uploadImageBtn = document.querySelector('.upload-image-button');
  uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    uploader = wp.media({
      title: 'Thêm hình ảnh',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      const attachment = uploader.state().get('selection').first().toJSON();
      const tdRecord = document.getElementById(`record-step-2-add-1`);
      tdRecord.querySelector('.image-url').value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = tdRecord.querySelector('.image-wrapper');
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

  handleInputChange(modalAdd);
});
