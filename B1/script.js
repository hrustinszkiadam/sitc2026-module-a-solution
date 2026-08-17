// B1 — render the buildings as a skyline, tallest first.

const TALLEST_HEIGHT_PX = 800;

const skyline = document.getElementById("skyline");

// Tallest building first.
const sorted = [...buildings].sort((a, b) => b.height - a.height);
const tallest = sorted[0].height;

for (const building of sorted) {
  const figure = document.createElement("figure");
  figure.className = "building";

  const image = document.createElement("img");
  image.src = "images/" + building.image;
  image.alt = building.name;
  // Every building is scaled against the tallest one, the aspect ratio is kept
  // because only the height is set.
  image.style.height = (building.height / tallest) * TALLEST_HEIGHT_PX + "px";

  const caption = document.createElement("figcaption");
  caption.innerHTML =
    '<span class="name">' + building.name + "</span>" +
    '<span class="height">' + building.height + " m</span>";

  figure.append(image, caption);
  skyline.append(figure);
}
