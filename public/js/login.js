const ErrorCode = {
  BAD_USER: 4011,
  BAD_PASSWORD: 4012,
};

const ERROR_MESSAGES = {
  [ErrorCode.BAD_USER]: "Nincs ilyen felhasználó!",
  [ErrorCode.BAD_PASSWORD]: "Hibás jelszó!",
};
const DEFAULT_ERROR_MESSAGE = "Valami hiba történt!";

function getErrorMessage(errorCode) {
  return ERROR_MESSAGES?.[errorCode] ?? DEFAULT_ERROR_MESSAGE;
}

function getErrorCode() {
  const queryParams = new URLSearchParams(window.location.search);
  const errorCode = Number(queryParams.get("error") ?? 0);

  return errorCode;
}

class ErrorBox {
  constructor() {
    this.container = document.getElementById("errorBox");
  }

  setText(text) {
    this.container.textContent = text;
  }
}

async function sleep(milliseconds) {
  return new Promise((resolve) => setTimeout(resolve, milliseconds));
}

class CountdownBox {
  constructor(seconds, url) {
    this.url = url;
    this.container = document.getElementById("countdownBox");
    this.counter = seconds;
  }

  async start() {
    this.refresh();

    for (let i = 0; i < this.counter + 2; i++) {
      await sleep(1000);

      this.decrease();
    }

    window.location.replace(this.url);
  }

  decrease() {
    this.counter--;
    this.refresh();

    if (this.counter === 0) {
      window.location.replace(this.url);
    }
  }

  refresh() {
    this.container.textContent = "Átirányítás " + this.counter + "...";
  }
}

function displayError(errorCode) {
  const errorBox = new ErrorBox();
  errorBox.setText(getErrorMessage(errorCode));
}

async function triggerPolice() {
  const countdownBox = new CountdownBox(3, "https://police.hu");
  countdownBox.start();
}

function handleError() {
  const errorCode = getErrorCode();

  if (!errorCode) {
    return;
  }

  displayError(errorCode);

  if (errorCode === ErrorCode.BAD_PASSWORD) {
    triggerPolice();
  }
}

window.onload = handleError;
