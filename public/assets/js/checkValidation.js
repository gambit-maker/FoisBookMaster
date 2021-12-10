const tracuuBtn = document.querySelector("#tracuu_btn");
const themBtn = document.querySelector("#them_btn");
const updateBtn = document.querySelector("#update_btn");
const xoaBtn = document.querySelector("#xoa_btn");
const clearBtn = document.querySelector("#clear_btn");
const closeAnchor = document.querySelector("#close_anchor");

const bookId = document.querySelector("#bookId");
const bookTitlte = document.querySelector("#book_title");
const author = document.querySelector("#author");
const publisher = document.querySelector("#publisher");
const year = document.querySelector("#year");
const month = document.querySelector("#month");
const date = document.querySelector("#date");

const messageObj = {
  MSG0001: "Hãy nhập Book ID",
  MSG0002: "Hãy nhập Book ID bằng chữ số anh 1 byte",
  MSG0003: "Sách đã được tìm thấy",
  MSG0004: "Không thể tìm thấy Book ID****",
  MSG0005: "Đã phát sinh ngoại lệ bằng xử lý server",
  MSG0006: "Hãy nhập Book title",
  MSG0007: "Hãy nhập tên tác giả",
  MSG0008: "Hãy nhập nhà xuất bản",
  MSG0009: "Hãy nhập ngày xuất bản",
  MSG0010: "Hãy nhập ngày xuất bản bằng chữ số 1 byte",
  MSG0011: "Book ID****đã được đăng ký. Hãy nhập ID khác",
  MSG0012: "Đã đăng ký sách",
  MSG0013: "Đã update sách",
  MSG0014: "Book ID****không được tìm thấy",
  MSG0015: "Đã xóa Book ID****",
  MSG0016: "Ngày xuất bản không hợp lệ",
};

// check halfwidth fullwidth char
function stringCheck(thisval) {
  var flag = false;
  if (thisval.match(/[^\x00-\x80]/)) {
    flag = true;
  }
  return flag;
}

function checkInputIsNull(input) {
  if (input === "" || input === null) return true;
  return false;
}

// check input có 2 điều kiện
function checkValue2Condition(id, con1, con2) {
  let message = "";
  if (id === null || id === "") {
    message = con1;
  } else if (stringCheck(id)) {
    message = con2;
  }
  return message;
}

function checkAllInput() {
  const message = [];
  const bookIdMessage = checkValue2Condition(
    bookId.value,
    messageObj["MSG0001"],
    messageObj["MSG0002"]
  );
  const yearMessage = checkValue2Condition(
    year.value,
    messageObj["MSG0009"],
    messageObj["MSG0010"]
  );
  const monthMessage = checkValue2Condition(
    month.value,
    messageObj["MSG0009"],
    messageObj["MSG0010"]
  );
  const dateMessage = checkValue2Condition(
    date.value,
    messageObj["MSG0009"],
    messageObj["MSG0010"]
  );
  if (bookIdMessage !== null && bookIdMessage !== "")
    message.push(bookIdMessage);
  if (checkInputIsNull(bookTitlte.value)) message.push(messageObj["MSG0006"]);
  if (checkInputIsNull(author.value)) message.push(messageObj["MSG0007"]);
  if (checkInputIsNull(publisher.value)) message.push(messageObj["MSG0008"]);

  if (yearMessage !== null && yearMessage !== "")
    message.push(yearMessage + " (Năm)");

  if (monthMessage !== null && monthMessage !== "")
    message.push(monthMessage + " (Tháng)");

  if (dateMessage !== null && dateMessage !== "")
    message.push(dateMessage + " (Ngày)");

  return message;
}

function checkBookId() {
  const message = checkValue2Condition(
    bookId.value,
    messageObj["MSG0001"],
    messageObj["MSG0002"]
  );
  return message;
}

tracuuBtn.addEventListener("click", (e) => {
  const message = checkBookId();
  if (message !== null && message !== "") {
    alert(message);
    e.preventDefault();
  }
});

themBtn.addEventListener("click", (e) => {
  const message = checkAllInput();
  if (message !== null && message.length > 0) {
    alert(message.join("\n"));
    console.log(message);
    e.preventDefault();
  }
});

updateBtn.addEventListener("click", (e) => {
  const message = checkAllInput();
  if (message !== null && message.length > 0) {
    alert(message.join("\n"));
    console.log(message);
    e.preventDefault();
  }
});

xoaBtn.addEventListener("click", (e) => {
  const message = checkBookId();
  if (message !== null && message !== "") {
    alert(message);
    e.preventDefault();
  }
});

clearBtn.addEventListener("click", (e) => {
  bookId.value = "";
  bookTitlte.value = "";
  author.value = "";
  publisher.value = "";
  year.value = "";
  month.value = "";
  date.value = "";
  e.preventDefault();
});

closeAnchor.addEventListener("click", function () {
  window.close();
});
