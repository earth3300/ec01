var opts = {
  "screen": {
    "target": { "wide": 1920, "high": 1080 },
    "actual": { "wide": 1366, "high": 768 },
    "menu": { "left": 65, "top": 25, "right": 0, "bottom": 0 },
    "class": '"screen-fill", "border dashed"',
    "aspect": {
      "desktop": 1.3333,
      "imax-film": 1.43,
      "golden": 1.618,
      "hd": 1.7778,
      "cinema": 1.85,
      "imax-digital": 1.9,
      "wide": 2.3333,
      "cinema-w": 2.39,
      "silver": 2.414,
    },
   }
};

function
if (typeof window.innerWidth !== "undefined") {

  var objHtml = document.getElementsByTagName("html");

  var mult = window.innerWidth / opts.target * .05;

  if ( 1 || window.innerWidth > opts.screen.target.wide * mult ) {

    objHtml[0].classList.add("screen-fill");

  }
}
