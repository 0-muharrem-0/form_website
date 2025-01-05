// Snowflake effect
function createSnowflake() {
  const snowflake = document.createElement("div");
  snowflake.classList.add("snowflake");
  snowflake.style.left = Math.random() * window.innerWidth + "px";
  snowflake.style.animationDuration = Math.random() * 3 + 5 + "s"; // Falling speed (increased duration for slower fall)
  snowflake.style.opacity = Math.random();
  snowflake.style.fontSize = Math.random() * 5 + 5 + "px"; // Smaller size

  snowflake.innerHTML = "&#10052;"; // Snowflake symbol

  document.body.appendChild(snowflake);

  setTimeout(() => {
      snowflake.remove();
  }, 8000); // Extended lifespan to match slower fall
}

setInterval(createSnowflake, 150); // Slower interval for less frequent snowflakes
