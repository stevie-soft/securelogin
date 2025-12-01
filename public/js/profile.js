const Colors = {
  RED: "piros",
  GREEN: "zold",
  YELLOW: "sarga",
  BLUE: "kek",
  BLACK: "fekete",
  WHITE: "feher",
};

const COLOR_TO_HEX = {
  [Colors.RED]: "#bf2e2e",
  [Colors.GREEN]: "#1e8215",
  [Colors.YELLOW]: "#dbc744",
  [Colors.BLUE]: "#298ccf",
  [Colors.BLACK]: "#000000",
  [Colors.WHITE]: "#ffffff",
};

const COLOR_TO_IMAGE = {
  [Colors.RED]: "red_flower.png",
  [Colors.GREEN]: "green_flower.png",
  [Colors.YELLOW]: "yellow_flower.png",
  [Colors.BLUE]: "blue_flower.png",
  [Colors.BLACK]: "black_flower.png",
  [Colors.WHITE]: "white_flower.png",
};

class FavColor {
  constructor(key) {
    this.key = key;
  }

  asHexColor() {
    return COLOR_TO_HEX[this.key];
  }

  asImagePath() {
    const imageName = COLOR_TO_IMAGE[this.key];
    const imagePath = `/images/${imageName}`;
    return imagePath;
  }
}

class FavColorBox {
  constructor() {
    this.container = document.getElementById("favColorBox");
  }

  getText() {
    return this.container.textContent;
  }

  setColor(hexColor) {
    this.container.style.color = hexColor;
  }
}

class FavFlowerImg {
  constructor() {
    this.img = document.getElementById("favFlowerImg");
  }

  setSource(src) {
    this.img.src = src;
  }
}

function displayFavoriteColor() {
  const favColorBox = new FavColorBox();
  const favFlowerImg = new FavFlowerImg();

  const favoriteColor = new FavColor(favColorBox.getText());

  favColorBox.setColor(favoriteColor.asHexColor());
  favFlowerImg.setSource(favoriteColor.asImagePath());
}

window.onload = displayFavoriteColor;
