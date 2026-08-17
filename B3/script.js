// B3 — Lines and Dots Animation

const COLORS = ["#f7d354", "#e0574a", "#4ec9b0", "#5aa9e6", "#c77dff", "#ff9f68"];
const DOT_COUNT = 40;
const RANGE = 260; // a line is drawn to every dot closer than this to the cursor

const dots = [];

function random(min, max) {
  return Math.random() * (max - min) + min;
}

// Every dot gets a random position, a random size and a random colour from the array.
for (let i = 0; i < DOT_COUNT; i++) {
  const color = COLORS[Math.floor(Math.random() * COLORS.length)];
  const size = random(6, 20);
  const x = random(40, window.innerWidth - 40);
  const y = random(40, window.innerHeight - 40);

  const dot = document.createElement("div");
  dot.className = "dot";
  dot.style.width = size + "px";
  dot.style.height = size + "px";
  dot.style.left = x + "px";
  dot.style.top = y + "px";
  dot.style.background = color;
  dot.style.boxShadow = "0 0 12px " + color;

  // one line per dot, drawn from the cursor to that dot
  const line = document.createElement("div");
  line.className = "line";
  line.style.background = color;
  line.style.opacity = 0;

  document.body.append(line, dot);
  dots.push({ dot, line, x, y });
}

document.addEventListener("mousemove", function (event) {
  for (const item of dots) {
    const dx = item.x - event.clientX;
    const dy = item.y - event.clientY;
    const distance = Math.sqrt(dx * dx + dy * dy);

    if (distance < RANGE) {
      const strength = 1 - distance / RANGE;

      item.line.style.left = event.clientX + "px";
      item.line.style.top = event.clientY + "px";
      item.line.style.width = distance + "px";
      item.line.style.transform = "rotate(" + Math.atan2(dy, dx) + "rad)";
      item.line.style.opacity = strength;

      item.dot.style.transform = "translate(-50%, -50%) scale(" + (1 + strength) + ")";
    } else {
      item.line.style.opacity = 0;
      item.dot.style.transform = "translate(-50%, -50%)";
    }
  }
});
