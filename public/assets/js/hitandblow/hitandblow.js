"use strict";
const guessingNum = document.querySelector(".gussingNum");
const guessingButton = document.querySelector(".guessingButton");
const historyTable = document.querySelector(".history_table");
const newGameButton = document.querySelector(".btn_newgame");
let table_tr = document.querySelectorAll("tr");
let inputNum = document.querySelector(".inputNumber");

let tableRow = 1;
let stt = 1;
let playAttempt = 1;
let numberAttempt = 15;
let gameOver = false;

const findDuplicates = (arr) =>
  arr.filter((item, index) => arr.indexOf(item) != index);

const checkInput = function (value) {
  const duplicateArr = findDuplicates(value.split(""));
  if (value < 1000) return false;
  if (duplicateArr.length > 0) return false;
  return true;
};

// create a random Number with 4 digit > 1000 and no dup
const createRandomNumber = function () {
  let randomNumber = Math.floor(Math.random() * 9999) + 1;
  randomNumber = String(randomNumber);

  while (!checkInput(randomNumber)) {
    randomNumber = Math.floor(Math.random() * 9999) + 1;
    randomNumber = String(randomNumber);
  }

  return randomNumber;
};

let randomNumber = createRandomNumber();

console.log(randomNumber);

guessingButton.addEventListener("click", function () {
  let hit = 0;
  let blow = 0;
  const row = historyTable.insertRow(tableRow);
  const cell1 = row.insertCell(0);
  const cell2 = row.insertCell(1);
  const cell3 = row.insertCell(2);
  const cell4 = row.insertCell(3);

  if (gameOver === false) {
    if (checkInput(inputNum.value)) {
      // đoán trúng
      if (randomNumber === inputNum.value) {
        guessingNum.innerHTML = randomNumber;
        hit = randomNumber.length;
        blow = 0;
        console.log(inputNum.value, hit, blow);
        alert("you won that is the correct Number");
        gameOver = true;
      } else {
        for (let i = 0; i < randomNumber.length; i++) {
          // hit
          if (randomNumber[i] === inputNum.value[i]) {
            hit++;
          }
          //blow
          else if (randomNumber.includes(inputNum.value[i])) {
            blow++;
          }
        }
      }
      // hiển thị giá trị đã đoán
      cell1.innerHTML = stt;
      cell2.innerHTML = inputNum.value;
      cell3.innerHTML = hit;
      cell4.innerHTML = blow;
      table_tr = document.querySelectorAll("tr");
      // scroll bảng xuống giá trị đó
      table_tr[tableRow].scrollIntoView({
        behavior: "smooth",
        block: "center",
      });

      tableRow++;
      stt++;
      // reset input
      inputNum.value = "";
      inputNum.autofocus = true;
      playAttempt++;
    } else {
      alert(
        "Input not correct make sure you follow these condition:\n(Input not empty)\n(Input not containt dupplicate)\n(Input > 1000)\n(Input not containt any letters)"
      );
    }

    // thua cuộc
    if (playAttempt > numberAttempt && gameOver === false) {
      alert(
        `you lose\n(player have only ${numberAttempt} attempts)\nThe correct Number is: ${randomNumber}`
      );
      guessingNum.innerHTML = randomNumber;
      gameOver = true;
    }
  }
});

newGameButton.addEventListener("click", function () {
  const tableRowsLength = historyTable.rows.length;
  for (let i = tableRowsLength - 2; i > 0; i--) {
    historyTable.deleteRow(i);
  }
  tableRow = 1;
  stt = 1;
  playAttempt = 1;
  numberAttempt = 15;
  gameOver = false;
  randomNumber = createRandomNumber();
  guessingNum.innerHTML = "? ? ? ?";
  console.log(`new random number: ${randomNumber}`);
});
