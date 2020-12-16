window.onload = function() {
	
    initRedactor();
	initButton();
} 
// Переменные
let objError = {
    'copy': "Для копирования нужно выделить два символа в тексте!",
    'cut': "Для того, чтобы вырезать часть текста, нужно выделить два символа в тексте!",
    'insert': "Для вставки текста нужно выделить один символ в тексте!",
    'insertBufferEmpty' : "Буфер обмена пуст!"
};
let textRedactor = document.getElementById('redactor');
let firstPoint = null;
let secondPoint = null;
let bufer = null;
let firstPointInput = document.getElementById("first_point");
let secondPointInput = document.getElementById("second_point");
let copyBut = document.getElementById("copy_but");
let cutBut = document.getElementById("cut_but");
let insertBut = document.getElementById("insert_but");
let copyInp = document.getElementById("copy_inp");
let cutInp = document.getElementById("cut_inp");
let insertInp = document.getElementById("insert_inp");
let backBut = document.getElementById("back_but");
let forwardBut = document.getElementById("forward_but");
let backInp = document.getElementById("back_inp");
let forwardInp = document.getElementById("forward_inp");
let resetBut = document.getElementById("reset_but");
let resetInp = document.getElementById("reset_inp");
let errorBlock = document.getElementById("error");
let redactorForm = document.getElementById("button_redactor_form");
let actionForm = document.getElementById("button_action_form");


// Инициализирует поле редактора при загрузке страницы и при редактировании текста
function initRedactor() {
    let text = textRedactor.innerText;
    let newText = addSpan(text);
	textRedactor.innerHTML = newText;
    let allSpan = textRedactor.querySelectorAll('span');
	
	for (let i = 0; i < allSpan.length; i++) {
		allSpan[i].addEventListener("click", clickSpan);
    }
    
}
// Вставляет теги Span для каждого символа текста.
function addSpan(text) {
    let newText = ''; 
    for (let i = 0; i < text.length; i++) {
        newText += `<span data-number="${i}">${text[i]}</span>`
    }
    return newText;
}

// Выполняет действия при клике на один из тегов span в редакторе
function clickSpan(e) {
    errorBlock.textContent = '';
    if (firstPoint === null) {
        firstPoint = e.target;
        firstPointInput.value = firstPoint.dataset.number;
    } else if (secondPoint === null) {
        secondPoint = e.target;
        secondPointInput.value = secondPoint.dataset.number;
    } else if (firstPoint !== null && secondPoint !== null) {
        delPoint();
        firstPoint = e.target;
        firstPointInput.value = firstPoint.dataset.number;
    }
    e.target.style.backgroundColor = "red";
}

// Функция очищает данные о выделенных точках в null
function delPoint() {
    if (firstPoint !== null) {
        firstPoint.style.backgroundColor = "inherit";
        firstPointInput.value = -1;
        firstPoint = null;
    }
    if (secondPoint !== null) {
        secondPoint.style.backgroundColor = "inherit";
        secondPointInput.value = -1;
        secondPoint = null;
    }
}

// Функция вешающая обработчик событий на все кнопки и формы
function initButton() {
    copyBut.addEventListener('click', clickCopy, false);
    cutBut.addEventListener('click', clickCut, false);
    insertBut.addEventListener('click', clickInsert, false);
    backBut.addEventListener('click', clickBack, false);
    forwardBut.addEventListener('click', clickForward, false);
    resetBut.addEventListener('click', clickReset, false);

    redactorForm.addEventListener('submit', formRedactor, false);
    actionForm.addEventListener('submit', formAction, false);
}

// Функция которая проверяет валидность операци  "Копирования"
function clickCopy(e) {
    if(firstPoint === null || secondPoint === null) {
        e.preventDefault();
        errorBlock.textContent = objError.copy;
    } else {
        clearInputOperation();
        copyInp.value = 1;
        bufer = [firstPointInput.value, secondPointInput.value];
    }
}
// Функция проверяющая валидность операции "Вырезать"
function clickCut(e) {
    if(firstPoint === null || secondPoint === null) {
        e.preventDefault();
        errorBlock.textContent = objError.cut;
    } else {
        clearInputOperation();
        cutInp.value = 1;
        bufer = [firstPointInput.value, secondPointInput.value];
    }
}
// Функция проверяющая валидность операции "Вставка"
function clickInsert(e) {
    if(firstPoint === null || secondPoint !== null) {
        e.preventDefault();
        errorBlock.textContent = objError.insert;
    } else if (!bufer) {
        e.preventDefault();
        errorBlock.textContent = objError.insertBufferEmpty;
    } else {
        clearInputOperation();
        insertInp.value = 1;
    }
}

// Функция чистит инпуты операций
function clearInputOperation() {
    copyInp.value = -1;
    cutInp.value = -1;
    insertInp.value = -1;
    forwardInp.value = -1;
    backInp.value = -1;
    resetInp.value = -1;
}

// Действие при нажатии на кнопку "back"
function clickBack() {
    clearInputOperation()
    backInp.value = 1;
}

// Действие при нажатии на кнопку "forward"
function clickForward() {
    clearInputOperation();
    forwardInp.value = 1;
}

// Действие при нажатии на кнопку "Сброс"
function clickReset() {
    clearInputOperation();
    resetInp.value = 1;
}

// Функция отправляющая данные из формы редактора на сервер и ожидающая его ответа.
async function formRedactor(e) {
    e.preventDefault();

    let formData = new FormData(e.target);
    formData = Object.fromEntries(formData);
    
    const response = await fetch('Redactor.php', {
        method: 'Post',
        headers: {
            'Content-Type': 'application/json', 
        },
        body: JSON.stringify(formData)
    });
    let answer = await response.json();
    delPoint();
    
    if(answer.data[1] === 'copy') {
        console.log("Копирование прошло успешно!");
    } else if (answer.data[1] === 'cut') {
        
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Вырезание информации из текста прошло успешно!");
    } else if (answer.data[1] === 'insert') {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Вставка информации в текст прошло успешно!");
    } else if (answer.data[1] === 'errorCutInsert') {
        errorBlock.textContent = answer.data[0];
        console.log(answer.data[0]);
        
    }
}

// Функция отправляющая данные из формы отмены и возврата операций на сервер и ожидающая его ответа.
async function formAction(e) {
    e.preventDefault();

    let formData = new FormData(e.target);
    formData = Object.fromEntries(formData);
    
    const response = await fetch('Redactor.php', {
        method: 'Post',
        headers: {
            'Content-Type': 'application/json', 
        },
        body: JSON.stringify(formData)
    });
    let answer = await response.json();
    delPoint();
    
    if (answer.data[1] === "backErr") {
        console.log(answer.data[0]);
    } else if (answer.data[1] === "insertBack") {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Отмена вставки прошла успешно!");
    } else if (answer.data[1] === "cutBack") {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Отмена вырезания прошла успешно!");
    } else if (answer.data[1] === "forwardErr") {
        console.log(answer.data[0]);
    } else if (answer.data[1] === "insertForward") {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Возврат вставки прошел успешно!");
    } else if (answer.data[1] === 'cutForward') {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        console.log("Возврат вырезания прошел успешно!");
    } else if (answer.data[1] === 'reset') {
        textRedactor.innerText = answer.data[0];
        initRedactor();
        errorBlock.textContent = '';
        console.log("Сброс редактора прошел успешно!");
    } 
}