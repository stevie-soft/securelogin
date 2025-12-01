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

function displayError(errorCode) {
  const errorBox = document.getElementById("errorBox");
  errorBox.textContent = getErrorMessage(errorCode);
}

async function triggerPolice() {
  setTimeout(() => {
    window.location.replace("https://police.hu");
  }, 3000);
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
